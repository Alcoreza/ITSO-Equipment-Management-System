/* ----------------------------------------------------------
   ITSO EMS — AUTH FORMS JS
   Handles:
   - Basic HTML5 validation enhancement
   - Password match check (reset form)
   - Password visibility toggle (login page)
---------------------------------------------------------- */

// -------------------------------
// 1. Enhance validation for all forms with novalidate
// -------------------------------

(function () {
  "use strict";

  const forms = document.querySelectorAll("form[novalidate]");

  forms.forEach(function (form) {
    form.addEventListener(
      "submit",
      function (event) {
        let valid = true;

        // -----------------------------
        // RESET FORM — custom validation
        // -----------------------------
        if (form.id === "resetForm") {
          const newPass = form.querySelector("#new_password");
          const confirmPass = form.querySelector("#confirm_password");

          if (newPass.value.length < 8) {
            newPass.classList.add("is-invalid");
            valid = false;
          } else {
            newPass.classList.remove("is-invalid");
          }

          if (newPass.value !== confirmPass.value) {
            confirmPass.classList.add("is-invalid");
            const feedback = document.getElementById("confirmFeedback");
            if (feedback) feedback.textContent = "Passwords must match.";
            valid = false;
          } else {
            confirmPass.classList.remove("is-invalid");
          }
        }

        // -----------------------------
        // NORMAL HTML5 VALIDATION
        // -----------------------------
        if (!form.checkValidity()) {
          Array.from(form.elements).forEach(function (el) {
            if (el.checkValidity && !el.checkValidity()) {
              el.classList.add("is-invalid");
            }
          });
          valid = false;
        }

        if (!valid) {
          event.preventDefault();
          event.stopPropagation();
        }
      },
      false
    );
  });
})();

// -------------------------------
// 2. Login Page — Toggle Password Visibility
// -------------------------------

document.addEventListener("click", function (e) {
  if (e.target && e.target.id === "togglePwd") {
    const button = e.target;
    const input = document.querySelector("#password");
    if (!input) return;

    if (input.type === "password") {
      input.type = "text";
      button.textContent = "Hide";
    } else {
      input.type = "password";
      button.textContent = "Show";
    }
  }
});

// Register page - password match check
document.addEventListener("input", function () {
  const pass = document.getElementById("reg_password");
  const confirm = document.getElementById("reg_confirm_password");

  if (!pass || !confirm) return;

  if (confirm.value !== pass.value) {
    confirm.classList.add("is-invalid");
  } else {
    confirm.classList.remove("is-invalid");
  }
});
