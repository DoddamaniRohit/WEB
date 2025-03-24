document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("registrationForm");
    const nameInput = document.getElementById("name");
    const emailInput = document.getElementById("email");
    const passwordInput = document.getElementById("password");

    const nameError = document.getElementById("nameError");
    const emailError = document.getElementById("emailError");
    const passwordError = document.getElementById("passwordError");

    form.addEventListener("submit", function (event) {
        let isValid = true;

        // Name validation: Minimum 3 characters
        if (nameInput.value.trim().length < 3) {
            nameError.style.display = "block";
            isValid = false;
        } else {
            nameError.style.display = "none";
        }

        // Email validation
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailPattern.test(emailInput.value.trim())) {
            emailError.style.display = "block";
            isValid = false;
        } else {
            emailError.style.display = "none";
        }

        // Password validation: 8+ chars, 1 number, 1 special char
        const passwordPattern = /^(?=.*[0-9])(?=.*[!@#$%^&*]).{8,}$/;
        if (!passwordPattern.test(passwordInput.value.trim())) {
            passwordError.style.display = "block";
            passwordError.textContent = "Password must be at least 8 characters, include 1 number & 1 special character (!@#$%^&*).";
            isValid = false;
        } else {
            passwordError.style.display = "none";
        }

        // Prevent form submission if validation fails
        if (!isValid) {
            event.preventDefault();
        }
    });
});
