document.addEventListener("DOMContentLoaded", () => {
    const loginBtn = document.querySelector(".to-login");
    const signupBtn = document.querySelector(".to-signup");
    const loginForm = document.querySelector(".form.login");
    const signupForm = document.querySelector(".form.signup");

    if (loginBtn && signupBtn) {
        loginBtn.addEventListener("click", () => {
            signupForm.classList.remove("active");
            loginForm.classList.add("active");
        });

        signupBtn.addEventListener("click", () => {
            loginForm.classList.remove("active");
            signupForm.classList.add("active");
        });
    }
});
