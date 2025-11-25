/* ----------------------------------------------------------
   ITSO EMS — AUTH FORMS JS
   - HTML5 validation enhancement
   - Reset password custom checks
   - Login: password visibility toggle
   - Register: live password match check
---------------------------------------------------------- */

(function () {
  "use strict";

  // EDIT USER MODAL: populate form fields from data attributes on trigger button
  const editUserModal = document.getElementById("modalEditUser");
  if (editUserModal) {
    editUserModal.addEventListener("show.bs.modal", function (event) {
      const button = event.relatedTarget;
      if (!button) return;

      const id = button.getAttribute("data-id") || "";
      const first = button.getAttribute("data-first") || "";
      const last = button.getAttribute("data-last") || "";
      const email = button.getAttribute("data-email") || "";
      const role = button.getAttribute("data-role") || "";

      const idEl = document.getElementById("editUserId");
      const firstEl = document.getElementById("editFirstName");
      const lastEl = document.getElementById("editLastName");
      const emailEl = document.getElementById("editEmail");
      const roleEl = document.getElementById("editRole");
      const passEl = document.querySelector('#modalEditUser input[name="password"]');
      const passConfEl = document.querySelector('#modalEditUser input[name="password_confirm"]');

      if (idEl) idEl.value = id;
      if (firstEl) firstEl.value = first;
      if (lastEl) lastEl.value = last;
      if (emailEl) emailEl.value = email;

      if (roleEl) {
        const roleVal = (typeof role === "string") ? role.trim() : "";
        if (roleVal === "") {
          roleEl.value = "";
        } else {
          const existing = Array.from(roleEl.options).find(opt => opt.value === roleVal);
          if (existing) {
            roleEl.value = roleVal;
          } else {
            const newOpt = document.createElement("option");
            newOpt.value = roleVal;
            newOpt.text = roleVal.charAt(0).toUpperCase() + roleVal.slice(1);
            newOpt.selected = true;
            roleEl.appendChild(newOpt);
            roleEl.value = roleVal;
          }
        }
      }

      if (passEl) passEl.value = "";
      if (passConfEl) passConfEl.value = "";
    });
  }

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

  // REGISTER: Live password match
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

document.addEventListener("DOMContentLoaded", () => {
  // Activate / Deactivate Confirmation Modal Handler
  document.addEventListener("click", function (e) {
    const btn = e.target.closest(".users-action-toggle");
    if (!btn) return;

    const name = btn.getAttribute("data-user-name") || "this user";
    const action = btn.getAttribute("data-action") || "deactivate";

    const label = action === "activate" ? "Activate" : "Deactivate";

    const titleSpan = document.getElementById("confirmActionLabel");
    const inlineSpan = document.getElementById("confirmActionLabelInline");
    const nameSpan = document.getElementById("confirmUserName");

    if (titleSpan) titleSpan.textContent = label;
    if (inlineSpan) inlineSpan.textContent = label.toLowerCase();
    if (nameSpan) nameSpan.textContent = name;

    // Hidden form fields inside confirm modal
    const confirmId = document.getElementById("confirmToggleId");
    const confirmAction = document.getElementById("confirmToggleAction");
    if (confirmId) confirmId.value = btn.getAttribute("data-id") || "";
    if (confirmAction) confirmAction.value = btn.getAttribute("data-action") || action;
  });
});

/* ===========================================
   EQUIPMENT MODULE — FRONTEND ONLY
=========================================== */

document.addEventListener("DOMContentLoaded", () => {
  const filterCategory = document.getElementById("filterCategory");
  const filterStatus = document.getElementById("filterStatus");
  const searchInput = document.getElementById("searchInput");
  const cards = document.querySelectorAll(".equipment-card-item");

  function applyFilters() {
    const category = filterCategory.value.toLowerCase();
    const status = filterStatus.value.toLowerCase();
    const search = searchInput.value.toLowerCase();

    cards.forEach((card) => {
      const c = card.dataset.category.toLowerCase();
      const s = card.dataset.status.toLowerCase();
      const name = card.querySelector(".equipment-name").textContent.toLowerCase();

      const matchCategory = category === "" || c === category;
      const matchStatus = status === "" || s === status;
      const matchSearch = name.includes(search);

      card.style.display = matchCategory && matchStatus && matchSearch ? "flex" : "none";
    });
  }

  filterCategory.addEventListener("change", applyFilters);
  filterStatus.addEventListener("change", applyFilters);
  searchInput.addEventListener("input", applyFilters);

  // Equipment confirm modal
  const confirmModal = document.getElementById("modalConfirmStatus");
  if (confirmModal) {
    confirmModal.addEventListener("show.bs.modal", (event) => {
      const button = event.relatedTarget;
      const itemName = button.getAttribute("data-name") || "Unknown Item";
      const action = button.getAttribute("data-action") || "deactivate";

      confirmModal.querySelector("#confirmEquipmentName").textContent = itemName;
      confirmModal.querySelector("#confirmEquipmentAction").textContent = action;
      confirmModal.querySelector("#confirmEquipmentActionInline").textContent = action;
    });
  }

  // VIEW, EDIT, CONFIRM POPULATORS (from second script)
  function populateViewModal(el) {
    const card = el.closest(".equipment-card");
    document.getElementById("viewEquipmentName").textContent = card.dataset.name;
    document.getElementById("viewEquipmentType").textContent = card.dataset.type;
    document.getElementById("viewEquipmentStatus").textContent = card.dataset.statusText;
    document.getElementById("viewEquipmentAvailable").textContent = card.dataset.available;
    document.getElementById("viewEquipmentImage").src = card.querySelector("img").src;
    document.getElementById("viewEquipmentDescription").textContent =
      card.dataset.description || "No description available.";
  }

  function populateEditModal(el) {
    const card = el.closest(".equipment-card");
    document.getElementById("editEquipmentName").value = card.dataset.name;
    document.getElementById("editEquipmentType").value = card.dataset.type;
    document.getElementById("editEquipmentStatus").value = card.dataset.statusText;
    document.getElementById("editEquipmentAvailable").value = card.dataset.available;
    document.getElementById("editEquipmentID").value = card.dataset.id;
    document.getElementById("editEquipmentImage").src = card.querySelector("img").src;
    document.getElementById("editEquipmentDescription").value = card.dataset.description || "";
  }

  function populateConfirmModal(el) {
    const card = el.closest(".equipment-card");
    document.getElementById("confirmEquipmentName").textContent = card.dataset.name;
    document.getElementById("confirmEquipmentID").value = card.dataset.id;

    const action = card.dataset.statusText === "active" ? "Deactivate" : "Activate";
    document.getElementById("confirmActionLabel").textContent = action;
    document.getElementById("confirmActionLabelInline").textContent = action.toLowerCase();
  }

  document.getElementById("filterCategory").addEventListener("change", filterCards);
  document.getElementById("filterStatus").addEventListener("change", filterCards);

  function filterCards() {
    const category = document.getElementById("filterCategory").value.toLowerCase();
    const status = document.getElementById("filterStatus").value.toLowerCase();

    document.querySelectorAll(".equipment-card").forEach(card => {
      const matchCategory = !category || card.dataset.category.toLowerCase() === category;
      const matchStatus = !status || card.dataset.status.toLowerCase() === status;

      card.style.display = (matchCategory && matchStatus) ? "block" : "none";
    });
  }
});

document.addEventListener("DOMContentLoaded", function () {
  const viewUserModal = document.getElementById("modalViewUser");

  if (viewUserModal) {
    viewUserModal.addEventListener("show.bs.modal", function (event) {
      const button = event.relatedTarget;
      const userId = button ? button.getAttribute("data-id") : null;

      if (!userId) {
        console.error("modalViewUser: no userId found on trigger button", button);
        return;
      }

      const base = (typeof BASE_URL !== "undefined") ? String(BASE_URL).replace(/\/$/, "") : "";
      const fetchUrl = base ? `${base}/admin/user/${userId}` : `admin/user/${userId}`;

      fetch(fetchUrl)
        .then(response => {
          if (!response.ok) throw new Error("Network response was not ok: " + response.status);
          return response.json();
        })
        .then(data => {
          if (data.error) {
            console.error("Error from server:", data.error);
            return;
          }

          const nameEl = document.getElementById("viewUserName");
          const emailEl = document.getElementById("viewUserEmail");
          const roleEl = document.getElementById("viewUserRole");
          const statusEl = document.getElementById("viewUserStatus");

          if (nameEl) nameEl.textContent = `${data.first_name} ${data.last_name}`;
          if (emailEl) emailEl.textContent = data.email || "";
          if (roleEl) roleEl.textContent = data.role ? data.role.charAt(0).toUpperCase() + data.role.slice(1) : "";
          if (statusEl) statusEl.textContent = data.status_text || "";
        })
        .catch(error => console.error("Error fetching user details:", error));
    });
  }
});
