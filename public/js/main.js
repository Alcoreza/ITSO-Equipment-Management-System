/* ----------------------------------------------------------
   ITSO EMS — AUTH FORMS JS
   - HTML5 validation enhancement
   - Reset password custom checks
   - Login: password visibility toggle
   - Register: live password match check
---------------------------------------------------------- */

(function () {
  "use strict";

  const forms = document.querySelectorAll("form[novalidate]");

  forms.forEach(function (form) {
    form.addEventListener(
      "submit",
      function (event) {
        let valid = true;

        // RESET FORM — custom validation
        if (form.id === "resetForm") {
          const newPass = form.querySelector("#new_password");
          const confirmPass = form.querySelector("#confirm_password");

          if (newPass && newPass.value.length < 8) {
            newPass.classList.add("is-invalid");
            valid = false;
          } else if (newPass) {
            newPass.classList.remove("is-invalid");
          }

          if (newPass && confirmPass && newPass.value !== confirmPass.value) {
            confirmPass.classList.add("is-invalid");
            const feedback = document.getElementById("confirmFeedback");
            if (feedback) feedback.textContent = "Passwords must match.";
            valid = false;
          } else if (confirmPass) {
            confirmPass.classList.remove("is-invalid");
          }
        }

        // NORMAL HTML5 VALIDATION
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

// ----------------------------------------------------------
// DOM-READY HANDLERS (login + register)
// ----------------------------------------------------------
document.addEventListener("DOMContentLoaded", function () {
  // LOGIN: Toggle password visibility
  const pwdInput = document.getElementById("password");
  const toggleBtn = document.getElementById("togglePwd");

  if (pwdInput && toggleBtn) {
    toggleBtn.addEventListener("click", function () {
      const isHidden = pwdInput.type === "password";
      pwdInput.type = isHidden ? "text" : "password";
      this.textContent = isHidden ? "Hide" : "Show";
    });
  }

  // REGISTER: live password match
  const pass = document.getElementById("reg_password");
  const confirm = document.getElementById("reg_confirm_password");

  function checkRegisterPasswords() {
    if (!pass || !confirm) return;
    if (confirm.value && confirm.value !== pass.value) {
      confirm.classList.add("is-invalid");
    } else {
      confirm.classList.remove("is-invalid");
    }
  }

  if (pass && confirm) {
    pass.addEventListener("input", checkRegisterPasswords);
    confirm.addEventListener("input", checkRegisterPasswords);
  }
});
