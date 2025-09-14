// ========== GLOBALS ==========
let uploadedFile = null;
let fileUploaded = false;

// ========== DOM LOAD ==========
document.addEventListener("DOMContentLoaded", () => {
    // Elements
    let dropArea = document.getElementById("dropArea");
    let fileInput = document.getElementById("resumeInput");
    let fileName = document.getElementById("fileName");
    let fileNameUploaded = document.getElementById("fileNameUploaded");
    let startBtn = document.getElementById("start-analysis");
    let mainSection = document.getElementById("main");
    let previewBox = document.querySelector(".preview");
    let container = document.querySelector(".container");
    let loader = document.querySelector(".loader-wrapper");
    let testbtn = document.querySelector(".test-btn");

    // ATS Elements
    let atsCircle = document.getElementById("atsCircle");
    let atsValue = document.getElementById("atsValue");

    // Resume Info Elements
    let SkillsExtracted = document.getElementById("SkillsExtracted");
    let username = document.querySelector(".username");
    let usercontactinfo = document.querySelector(".contactinfo");
    let useratsscore = document.querySelector(".atsscore");
    let expContainer = document.getElementById("experience");
    let education = document.querySelector(".education");
    let projects = document.querySelector(".projects");

    // Suggestions
    let formattoimprove = document.querySelector(".formattoimprove");
    let formatimprove = document.querySelector(".format-improve");
    let keywordstosuggest = document.querySelector(".keywordstosuggest");
    let keywords = document.querySelector(".keywords");

    // ========== FILE UPLOAD ==========
    dropArea.addEventListener("click", () => fileInput.click());

    fileInput.addEventListener("change", () => {
        handleFile(fileInput.files[0]);
    });

    dropArea.addEventListener("dragover", e => {
        e.preventDefault();
        dropArea.classList.add("dragover");
    });

    dropArea.addEventListener("dragleave", () => {
        dropArea.classList.remove("dragover");
    });

    dropArea.addEventListener("drop", e => {
        e.preventDefault();
        dropArea.classList.remove("dragover");
        handleFile(e.dataTransfer.files[0]);
    });

    // ========== HANDLE FILE ==========
    function handleFile(file) {
        if (file) {
            uploadedFile = file;
            fileUploaded = true;
            fileName.textContent = file.name;
            fileNameUploaded.textContent = file.name;
        } else {
            uploadedFile = null;
            fileUploaded = false;
            fileName.textContent = "No file selected";
            fileNameUploaded.textContent = "No file selected";
        }
    }

    // ========== ATS CIRCLE ==========
    function updateATSCircle(score) {
        score = Math.max(0, Math.min(100, score)); // Clamp

        let color = "#f44336"; // red <50
        if (score >= 50 && score <= 80) color = "#ffeb3b"; // yellow
        else if (score > 80) color = "#10B510"; // green

        const degrees = score * 3.6;
        atsCircle.style.background = `conic-gradient(${color} 0deg ${degrees}deg, #ddd ${degrees}deg 360deg)`;
    }

    // ========== SHOW PREVIEW ==========
    function showPreview(file) {
        if (!file) return;
        let fileURL = URL.createObjectURL(file);

        if (file.type === "application/pdf") {
            previewBox.innerHTML = `<object data="${fileURL}" type="application/pdf" width="100%" style="min-height:100vh;"></object>`;
        } else {
            previewBox.innerHTML = `<p style="color:red;text-align:center;">Only PDF preview supported</p>`;
        }
        //previewBox.style.display = "initial";
    }

    // ========== START ANALYSIS ==========
    startBtn.addEventListener("click", async () => {
        if (!fileUploaded || !uploadedFile) {
            alert("⚠️ Please upload a resume first!");
            return;
        }
        console.log("btn clicked");
        loader.style.display = "flex";
        mainSection.style.display = "initial";
        container.style.width = "60%";
        // container.style.height = "100vh";
        previewBox.style.height = "100vh"
        previewBox.style.display = "initial";
        dropArea.style.display = "none";
        startBtn.style.display = "none"
        testbtn.style.display = "block";
        // Show preview only on click
        showPreview(uploadedFile);

        // 🔹 Call AI analysis function
        const aiResult = await window.analyzeResume(uploadedFile);
        console.log(aiResult);
        
        const contactinfo = aiResult.ContactInformation || {};
        const atsScore = aiResult.ATSScore?.Total || 0;
        const experiences = aiResult.Experience || [];
        const skills = aiResult.Skills || aiResult.skills || [];
        const usereducation = aiResult.Education || [];
        const userprojects = aiResult.Projects || [];
        const FormatImprovements = aiResult.FormatImprovements || [];
        const suggestionbtn = aiResult.Keywordstoadd || [];

        // if (aiResult.length > 0) {


        //     // Extract values
        //     const dataToSend = {
        //         name: contactinfo.Name || "Name not found",
        //         contactinfo: contactinfo["Phone No."],
        //         skills: aiResult.Skills || aiResult.skills || [],
        //         projects: aiResult.Projects || [],
        //         education: aiResult.Education || [],
        //         experience: aiResult.Experience || [],
        //         atsscore: aiResult.ATSScore?.Total || 0,
        //         FormatImprovements: aiResult.FormatImprovements || [],
        //         suggestionbtn: aiResult.Keywordstoadd || []
        //     };

        //     console.log("dataToSend", dataToSend);
        //     // Send JSON to PHP
        //     fetch("extract-info.php", {
        //         method: "POST",
        //         headers: {
        //             "Content-Type": "application/json" // tell PHP it's JSON
        //         },
        //         body: JSON.stringify(dataToSend) // convert JS object → JSON string
        //     })
        //         .then(response => response.text())
        //         .then(data => {
        //             console.log("PHP Response:", data);
        //         })
        //         .catch(error => console.error("Error:", error));

        // }

        // Contact Info
        username.textContent = contactinfo.Name || "Name not found";
        usercontactinfo.innerHTML = `
      <p><strong>Phone:</strong> ${contactinfo["Phone No."] || "Not found"}</p>
      <p><strong>Email:</strong> ${contactinfo.Email || "Not found"}</p>
      <p><strong>LinkedIn:</strong> ${contactinfo.LinkedIn ? `<a href="${contactinfo.LinkedIn}" target="_blank">${contactinfo.LinkedIn}</a>` : "Not found"}</p>
      <p><strong>GitHub:</strong> ${contactinfo.GitHub ? `<a href="${contactinfo.GitHub}" target="_blank">${contactinfo.GitHub}</a>` : "Not Found"}</p>
      <p><strong>Address:</strong> ${contactinfo.Address || "Not found"}</p>
    `;

        // ATS Score
        useratsscore.textContent = `${atsScore}%`;
        atsValue.textContent = `${atsScore}%`;
        updateATSCircle(atsScore);

        // Keywords
        keywordstosuggest.innerHTML = "";
        if (suggestionbtn.length > 0) {
            suggestionbtn.forEach(keys => {
                const div = document.createElement("div");
                div.classList.add("keyword-btn");
                div.textContent = keys;
                keywordstosuggest.appendChild(div);
            });
        } else {
            keywordstosuggest.textContent = "No keywords suggested.";
        }

        // Format Improvements
        formattoimprove.innerHTML = "";
        if (FormatImprovements.length > 0) {
            FormatImprovements.forEach(para => {
                const div = document.createElement("div");
                div.classList.add("suggestion");
                div.textContent = para;
                formattoimprove.appendChild(div);
                loader.style.display = "none";
            });
        } else {
            formattoimprove.textContent = "No format improvements suggested.";
        }

        // Skills
        SkillsExtracted.innerHTML = "";
        skills.forEach(skill => {
            const span = document.createElement("span");
            span.textContent = skill;
            span.classList.add("skill-badge");
            SkillsExtracted.appendChild(span);
        });
        const mockdata = window.MakeMockTest(`${skills}`);
        console.log(mockdata);

        // Education
        education.innerHTML = "";
        if (usereducation.length > 0) {
            usereducation.forEach(edu => {
                const edubox = document.createElement("div");
                edubox.classList.add("education-box");
                edubox.innerHTML = `
          <h4>Degree: ${edu.Degree || "Not found"}</h4>
          <h4>Dates: ${edu.Dates || "Not found"}</h4>
          <p><strong>Institution:</strong> ${edu.Institution || "Not provided"}</p>
        `;
                education.appendChild(edubox);
            });
        } else {
            education.textContent = "No education found";
        }

        // Experience
        expContainer.innerHTML = "";
        if (experiences.length > 0) {
            experiences.forEach(exp => {
                const expBox = document.createElement("div");
                expBox.classList.add("experience-box");
                expBox.innerHTML = `
          <h3><strong>Title:</strong> ${exp.Title || exp.JobTitle || "Not found"}</h3>
          <h4>Company: ${exp.Company || "Not found"}</h4>
          <p><strong>Duration:</strong> ${exp.Dates || "Not provided"}</p>
          <p>${exp.Description || "No description"}</p>
        `;
                expContainer.appendChild(expBox);
            });
        } else {
            expContainer.textContent = "No experience found";
        }

        // Projects
        projects.innerHTML = "";
        if (userprojects.length > 0) {
            userprojects.forEach(proj => {
                const projectsbox = document.createElement("div");
                projectsbox.classList.add("projects-box");
                projectsbox.innerHTML = `
          <h4>Project Name: ${proj.Title || proj.Name || "Not found"}</h4>
          <p>Description: ${proj.Description || "Not provided"}</p>
        `;
                projects.appendChild(projectsbox);
            });
        } else {
            projects.textContent = "No projects found";
        }
    });

    // ========== TOGGLE BUTTONS ==========
    let suggestionbtns = document.querySelectorAll(".suggestions-btn");
    suggestionbtns.forEach(btn => {
        btn.addEventListener("click", function () {
            suggestionbtns.forEach(b => (b.style = "")); // reset all
            btn.style.color = "black";
            btn.style.border = "1px solid black";
        });
    });

    if (formatimprove) {
        formatimprove.addEventListener("click", () => {
            formattoimprove.style.display = "block";
            keywordstosuggest.style.display = "none";
        });
    }

    if (keywords) {
        keywords.addEventListener("click", () => {
            keywordstosuggest.style.display = "flex";
            formattoimprove.style.display = "none";
        });
    }
});
