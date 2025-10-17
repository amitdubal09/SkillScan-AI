const API_KEY = "AIzaSyAilJiss4NNdQdCyOLU_TZmafZyfimz4i8"; 
const API_URL = `https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=${API_KEY}`;
/**
 * @param {File} file 
 * @returns {Promise<string>}
 */
function fileToBase64(file) {
  return new Promise((resolve, reject) => {
    const reader = new FileReader();
    reader.readAsDataURL(file);
    reader.onload = () => resolve(reader.result.split(',')[1]); // Sirf Base64 part
    reader.onerror = error => reject(error);
  });
}
window.analyzeResume = async function (file) {
  try {
    const base64Data = await fileToBase64(file);
    // 🔒 Strict rules for ATS Score (deterministic)
    const prompt = `
You are a strict resume analyzer. From this resume, extract the following information and return it ONLY in valid JSON format (no extra text, no explanations).
ATS SCORE RULES (always deterministic, no randomness):
- Skills Match (40 points): 
   > 80% relevant skills present = 40
   50–79% = 25
   < 50% = 10
- Experience Match (30 points):
   3+ years relevant = 30
   1–2 years = 20
   <1 year or unrelated = 10
   No experience = 0
- Education Match (10 points):
   Relevant degree (CS/IT/Engineering) = 10
   Other field = 5
   Missing = 0
- Formatting (20 points):
   Clean & structured = 20
   Minor issues = 10
   Major issues = 5
   Poor = 0
Always use this exact scoring. Same resume → same score.
{
  "ContactInformation": {
    "Name": "",
    "Phone No.": "",
    "Email": "",
    "LinkedIn": "",
    "GitHub":""
    "Address": ""
  },
  "Skills": [],
  "Experience": [
        "Title":"",
        "Company":"",
        "Description":""
        "Dates":"";
    ],
  "Education": [
        "Degree":"",
        "Dates":"",
        "Institution":""        
  ],
  "Projects": [
    {
      "Title": "",
      "Description": ""
    }
  ],
  "ATSScore": {
    "Total": 100,
    "Breakdown": {
  "ContactInfo": {
    "Total": 10,
    "Breakdown": {
      "BasicDetails": 2,
      "ProfessionalEmail": 1,
      "Links": 3,
      "Location": 2,
      "Readability": 2
    }
  },
  "Skills": {
    "Total": 20,
    "Breakdown": {
      "RelevantSkills": 5,
      "SoftSkills": 2,
      "LogicalGrouping": 3,
      "ATSFriendly": 5,
      "KeywordMatch": 5
    }
  },
  "Experience": {
    "Total": 30,
    "Breakdown": {
      "WorkExperience": 10,
      "ActionVerbs": 8,
      "QuantifiedAchievements": 5,
      "ChronologicalOrder": 2,
      "Consistency": 5
    }
  },
  "Education": {
    "Total": 15,
    "Breakdown": {
      "DegreeDetails": 5,
      "Coursework": 2,
      "CGPA": 2,
      "Certifications": 3,
      "Order": 3
    }
  },
  "Formatting_Design": {
    "Total": 15,
    "Breakdown": {
      "Consistency": 5,
      "Bullets": 3,
      "Length": 2,
      "ATSFriendly": 3,
      "Headings": 2
    }
  },
  "Grammar_Language": {
    "Total": 10,
    "Breakdown": {
      "NoErrors": 5,
      "ProfessionalTone": 3,
      "AvoidsPersonalInfo": 2
    }
  }
}
  },
  "Keywordstoadd": [],
  "FormatImprovements": []
}
        `;
    const response = await fetch(API_URL, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        contents: [
          {
            parts: [
              { text: prompt },
              {
                inlineData: {
                  mimeType: file.type,
                  data: base64Data
                }
              }
            ]
          }
        ],
        // 🔒 Force determinism
        generationConfig: {
          temperature: 0,
          topK: 1,
          topP: 1
        }
      })
    });
    // ✅ Get JSON response
    const data = await response.json();
    const rawText = data?.candidates?.[0]?.content?.parts?.[0]?.text || "{}";
    console.log("Raw AI text:", rawText);
    // ✅ Extract clean JSON
    let result = {};
    try {
      const match = rawText.match(/\{[\s\S]*\}/);
      if (match) {
        result = JSON.parse(match[0]);
      } else {
        console.warn("No JSON found in AI text:", rawText);
      }
    } catch (err) {
      console.error("Error parsing JSON:", err, "Raw text:", rawText);
    }
    // ✅ Only send if result has meaningful data
    if (result && Object.keys(result).length > 0 && result.ContactInformation) {
      const contactinfo = result.ContactInformation || {};

      const dataToSend = {
        saveResumeData:"SAVE",
        name: contactinfo.Name || "Name not found",
        contactinfo: contactinfo["Phone No."] || "",
        skills: result.Skills || result.skills || [],
        projects: result.Projects || [],
        education: result.Education || [],
        experience: result.Experience || [],
        ats: result.ATSScore?.Total || 0,
        FormatImprovements: result.FormatImprovements || [],
        suggestionbtn: result.Keywordstoadd || []
      };

      console.log("✅ Sending data to PHP:", dataToSend);

      fetch("extract-info.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(dataToSend)
      })
        .then(response => response.text())
        .then(data => {
          console.log("PHP Response:", data);
        })
        .catch(error => console.error("Error:", error));
    } else {
      console.warn("⚠️ No valid AI result found, skipping DB insert.");
    }
    return result;
  } catch (error) {
    console.error("Error calling Gemini API:", error);
    return {};
  }
};

// test generrator api
window.MakeMockTest = async function (skills) {
  try {
    const prompt = `
       You are an MCQ Test Generator.  
Input: A list of extracted skills from a resume.   ${skills } 
Task: Create a multiple-choice test worth 15 marks.  

Rules:  
- Remove Verbal languages like English, Hindi, Marathi
- doens not give questions on soft skills like communication, collaboration, team work i.e technical questions more
- Each question must carry 1 mark.  
- Provide exactly 15 questions.  
- Each question must be directly related to the given skills.  
- Each question must have 4 options (A, B, C, D).  
- Only one option should be correct.  
- Return the result strictly in valid JSON format with the following structure:
[
  {
    "question": "Your question text here?",
    "options": ["Option A", "Option B", "Option C", "Option D"],
    "answer": "Correct Option"
  }
]
Do not add explanations or extra text. Only return the JSON.
       `
    const response = await fetch(API_URL, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        contents: [
          {
            parts: [
              { text: prompt },
            ]
          }
        ],
        // 🔒 Force determinism
        generationConfig: {
          temperature: 0,
          topK: 1,
          topP: 1
        }
      })
    });
    // ✅ Get JSON response
    const data = await response.json();
    console.log("Test Data : ")
    //console.log(data)
    const rawText = data?.candidates?.[0]?.content?.parts?.[0]?.text || "{}";
    console.log("Raw Mock text:", rawText);
    // ✅ Extract clean JSON
    let result = {};
    try {
      // Match either an object { ... } or an array [ ... ]
      const match = await rawText.match(/(\{[\s\S]*\}|\[[\s\S]*\])/);
      if (match) {
        result = await JSON.parse(match[0]);
        // console.log("Parsed JSON:", result);
      } else {
        console.warn("No JSON found in AI text:", rawText);
      }
    } catch (err) {
      console.error("Error parsing JSON:", err, "Raw text:", rawText);
    }
    return result;
  } catch (error) {
    console.error("Error calling Gemini API:", error);
    return {};
  }
};
