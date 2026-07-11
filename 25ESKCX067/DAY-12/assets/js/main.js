// assets/js/main.js

document.addEventListener("DOMContentLoaded", function () {
    highlightActiveNavLink();
    setupPasswordToggles();
    setupFormValidation();
});

// Highlight the current page in the navbar
function highlightActiveNavLink() {
    const currentPage = window.location.pathname.split("/").pop();
    document.querySelectorAll(".nav-links a").forEach(function (link) {
        const href = link.getAttribute("href");
        if (href === currentPage) {
            link.classList.add("active");
        }
    });
}

// Add a Show/Hide toggle button next to every password field
function setupPasswordToggles() {
    document.querySelectorAll('input[type="password"]').forEach(function (input) {
        const wrapper = input.closest(".field-group");
        if (!wrapper) return;

        wrapper.style.position = "relative";

        const toggle = document.createElement("button");
        toggle.type = "button";
        toggle.className = "toggle-password";
        toggle.textContent = "Show";
        toggle.setAttribute("aria-label", "Show password");

        toggle.addEventListener("click", function () {
            const isPassword = input.type === "password";
            input.type = isPassword ? "text" : "password";
            toggle.textContent = isPassword ? "Hide" : "Show";
            toggle.setAttribute("aria-label", isPassword ? "Hide password" : "Show password");
        });

        wrapper.appendChild(toggle);
    });
}

// Lightweight client-side checks before submit (server-side validation still runs)
function setupFormValidation() {
    document.querySelectorAll("form[data-validate]").forEach(function (form) {
        form.addEventListener("submit", function (e) {
            let message = "";

            const password = form.querySelector('input[name="password"], input[name="new_password"]');
            const confirm = form.querySelector('input[name="confirm_password"], input[name="confirm_new_password"]');

            if (password && password.value.length > 0 && password.value.length < 6) {
                message = "Password must be at least 6 characters long.";
            }

            if (!message && password && confirm && password.value !== confirm.value) {
                message = "Passwords do not match.";
            }

            if (message) {
                e.preventDefault();
                showInlineError(form, message);
            }
        });
    });
}

function showInlineError(form, message) {
    let alertBox = form.querySelector(".js-alert");
    if (!alertBox) {
        alertBox = document.createElement("div");
        alertBox.className = "alert alert-error js-alert";
        form.prepend(alertBox);
    }
    alertBox.textContent = message;
}
