const chatBox = document.getElementById("chat-box");
const userInput = document.getElementById("userInput");
const sendBtn = document.getElementById("sendBtn");

sendBtn.addEventListener("click", sendMessage);
userInput.addEventListener("keypress", e => {
    if (e.key === "Enter") sendMessage();
});

function addMessage(text, sender) {
    const msg = document.createElement("div");
    msg.classList.add("message", sender === "user" ? "user-msg" : "bot-msg");
    msg.textContent = text;
    chatBox.appendChild(msg);
    chatBox.scrollTop = chatBox.scrollHeight;
}

async function sendMessage() {
    const text = userInput.value.trim();
    if (!text) return;

    addMessage(text, "user");
    userInput.value = "";

    // Show typing indicator
    const typing = document.createElement("div");
    typing.classList.add("message", "bot-msg");
    typing.textContent = "SkillScan AI is typing...";
    chatBox.appendChild(typing);
    chatBox.scrollTop = chatBox.scrollHeight;

    const response = await fetch("api.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ message: text })
    });

    const data = await response.json();
    chatBox.removeChild(typing);
    addMessage(data.reply, "bot");
}
