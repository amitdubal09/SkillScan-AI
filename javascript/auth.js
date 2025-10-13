// javascript/auth.js — Combined toggle + registration validation

document.addEventListener("DOMContentLoaded", () => {
    // --- FORM TOGGLE ---
    const loginBtn = document.querySelector(".to-login");
    const signupBtn = document.querySelector(".to-signup");
    const loginDiv = document.querySelector(".form.login");
    const signupDiv = document.querySelector(".form.signup");

    if (loginBtn && signupBtn) {
        loginBtn.addEventListener("click", () => {
            signupDiv.classList.remove("active");
            loginDiv.classList.add("active");
        });

        signupBtn.addEventListener("click", () => {
            loginDiv.classList.remove("active");
            signupDiv.classList.add("active");
        });
    }

    // --- REGISTRATION VALIDATION ---
    const signupForm = signupDiv.querySelector("form");

    // --- Regex Patterns ---
    const usernameRe = /^[A-Za-z0-9_]{3,20}$/;
    const emailRe = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const passwordRe = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^\w\s]).{8,}$/;

    // --- Helper functions ---
    function createError(msg) {
        const el = document.createElement("small");
        el.className = "input-error";
        el.style.color = "#e74c3c";
        el.style.display = "block";
        el.style.marginTop = "6px";
        el.textContent = msg;
        return el;
    }

    function clearError(input) {
        input.removeAttribute("aria-invalid");
        const next = input.nextElementSibling;
        if (next && next.classList.contains("input-error")) next.remove();
    }

    function showError(input, msg) {
        clearError(input);
        input.setAttribute("aria-invalid", "true");
        input.insertAdjacentElement("afterend", createError(msg));
    }

    // --- Validate Signup Form ---
    if (signupForm) {
        signupForm.addEventListener("submit", (e) => {
            let valid = true;

            const username = signupForm.querySelector('#username');
            const email = signupForm.querySelector('#email');
            const password = signupForm.querySelector('#password');
            const checkbox = signupForm.querySelector("#signupCheck");
            const label = signupForm.querySelector('label[for="signupCheck"]');

            clearError(username);
            clearError(email);
            clearError(password);

            // Username validation
            if (!usernameRe.test(username.value.trim())) {
                showError(username, "Username must be 3–20 characters (letters, numbers, underscores).");
                valid = false;
            }

            // Email validation
            if (!emailRe.test(email.value.trim())) {
                showError(email, "Enter a valid email address.");
                valid = false;
            }

            // Password validation
            if (!passwordRe.test(password.value)) {
                showError(password, "Password must have 8+ chars, upper, lower, number & symbol.");
                valid = false;
            }

            // Checkbox validation
            if (!checkbox.checked) {
                const next = label.nextElementSibling;
                if (!next || !next.classList.contains("input-error")) {
                    label.insertAdjacentElement("afterend", createError("You must accept terms & conditions."));
                }
                valid = false;
            } else {
                const next = label.nextElementSibling;
                if (next && next.classList.contains("input-error")) next.remove();
            }

            if (!valid) e.preventDefault(); // prevent form submission
        });

        // Clear error messages on input
        signupForm.querySelectorAll("input").forEach((input) => {
            input.addEventListener("input", () => clearError(input));
        });
    }
});
