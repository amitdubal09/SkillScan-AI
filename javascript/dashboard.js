document.addEventListener("DOMContentLoaded", async () => {
    const content = document.querySelector(".content");
    const userInfoBox = content.querySelector(".user-info");
    const skillsBox = content.querySelector(".skills");
    const expBox = content.querySelector(".experience");
    const eduBox = content.querySelector(".education");
    const projBox = content.querySelector(".projects");
    const atsBox = content.querySelector(".ats");

    const aiResult = await window.analyzeResume(uploadedFile);
    console.log(aiResult);
});