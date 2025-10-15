const chatbotIcon = document.getElementById("chatbotIcon");
const chatbotPopup = document.getElementById("chatbotPopup");
const closeChat = document.getElementById("closeChat");
const sendBtn = document.getElementById("sendBtn");
const userInput = document.getElementById("userInput");
const chatBody = document.getElementById("chatBody");

// Toggle Chat (open/close)
chatbotIcon.addEventListener("click", () => {
    if (chatbotPopup.style.display === "flex") {
        chatbotPopup.style.display = "none";
    } else {
        chatbotPopup.style.display = "flex";
    }
});


// Close Chat
closeChat.addEventListener("click", () => {
    chatbotPopup.style.display = "none";
});

// Send Message
sendBtn.addEventListener("click", sendMessage);
userInput.addEventListener("keypress", e => {
    if (e.key === "Enter") sendMessage();
});

function sendMessage() {
    const text = userInput.value.trim();
    if (!text) return;

    const userMsg = document.createElement("div");
    userMsg.classList.add("user-msg");
    userMsg.textContent = text;
    chatBody.appendChild(userMsg);
    userInput.value = "";

    chatBody.scrollTop = chatBody.scrollHeight;

    // Simulate bot reply (you can connect this to your PHP or Gemini AI API)
    setTimeout(() => {
        const botMsg = document.createElement("div");
        botMsg.classList.add("bot-msg");
        botMsg.textContent = "🤖 Thinking...";
        chatBody.appendChild(botMsg);

        fetch("api.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ message: text })
        })
            .then(res => res.json())
            .then(data => {
                botMsg.textContent = data.reply || "⚠️ No response from AI.";
                chatBody.scrollTop = chatBody.scrollHeight;
            })
            .catch(err => {
                botMsg.textContent = "❌ Error connecting to AI.";
                console.error(err);
            });
    }, 500);
}