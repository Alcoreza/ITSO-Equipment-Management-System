/* ----------------------------------------------------------
   ITSO EMS – AUTH FORMS JS
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

        // RESET FORM – custom validation
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

// ----------------------------------------------------------
// USER MANAGEMENT
// ----------------------------------------------------------
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

// ADD USER MODAL: Password matching validation
document.addEventListener("DOMContentLoaded", function () {
  const addUserForm = document.getElementById("addUserForm");
  
  if (addUserForm) {
    const addPassword = document.getElementById("addPassword");
    const addConfirmPassword = document.getElementById("addConfirmPassword");
    
    // Real-time password match check
    function checkAddUserPasswords() {
      if (!addPassword || !addConfirmPassword) return;
      
      if (addConfirmPassword.value && addConfirmPassword.value !== addPassword.value) {
        addConfirmPassword.classList.add("is-invalid");
      } else {
        addConfirmPassword.classList.remove("is-invalid");
      }
    }
    
    if (addPassword && addConfirmPassword) {
      addPassword.addEventListener("input", checkAddUserPasswords);
      addConfirmPassword.addEventListener("input", checkAddUserPasswords);
    }
    
    // Form submission validation
    addUserForm.addEventListener("submit", function(e) {
      const password = addPassword.value;
      const confirmPassword = addConfirmPassword.value;
      
      if (password !== confirmPassword) {
        e.preventDefault();
        addConfirmPassword.classList.add("is-invalid");
        return false;
      }
      
      // Show loading state
      const btnText = this.querySelector('.btn-text');
      const btnSpinner = this.querySelector('.btn-spinner');
      if (btnText) btnText.style.display = 'none';
      if (btnSpinner) btnSpinner.style.display = 'inline-block';
    });
    
    // Reset form when modal closes
    const modal = document.getElementById("modalAddUser");
    if (modal) {
      modal.addEventListener("hidden.bs.modal", function () {
        addUserForm.reset();
        addUserForm.querySelectorAll(".is-invalid").forEach(el => {
          el.classList.remove("is-invalid");
        });
        
        // Reset button state
        const btnText = addUserForm.querySelector('.btn-text');
        const btnSpinner = addUserForm.querySelector('.btn-spinner');
        if (btnText) btnText.style.display = 'inline';
        if (btnSpinner) btnSpinner.style.display = 'none';
      });
    }
  }
});

// USER FILTERS - Make role and status filters functional
document.addEventListener("DOMContentLoaded", function () {
  const filterRole = document.getElementById("filterRole");
  const filterStatus = document.getElementById("filterStatus");
  const userCards = document.querySelectorAll(".user-card");

  function applyUserFilters() {
    const selectedRole = filterRole ? filterRole.value.toLowerCase() : "";
    const selectedStatus = filterStatus ? filterStatus.value.toLowerCase() : "";

    userCards.forEach((card) => {
      const cardRole = card.getAttribute("data-role") ? card.getAttribute("data-role").toLowerCase() : "";
      const cardStatus = card.getAttribute("data-status") ? card.getAttribute("data-status").toLowerCase() : "";

      const matchRole = selectedRole === "" || cardRole === selectedRole;
      const matchStatus = selectedStatus === "" || cardStatus === selectedStatus;

      // Show card if both filters match (or are empty)
      if (matchRole && matchStatus) {
        card.style.display = "flex";
      } else {
        card.style.display = "none";
      }
    });

    // Count visible cards
    updateUserCount();
  }

  function updateUserCount() {
    const visibleCards = Array.from(userCards).filter(card => card.style.display !== "none");
    const totalCards = userCards.length;
    
    console.log(`Showing ${visibleCards.length} of ${totalCards} users`);
    
    // Optional: Add a count display
    const countDisplay = document.querySelector(".users-table-footer .text-muted");
    if (countDisplay && visibleCards.length !== totalCards) {
      countDisplay.textContent = `Showing ${visibleCards.length} of ${totalCards} users`;
    }
  }

  // Attach event listeners
  if (filterRole) {
    filterRole.addEventListener("change", applyUserFilters);
  }

  if (filterStatus) {
    filterStatus.addEventListener("change", applyUserFilters);
  }

  // Initial count
  updateUserCount();
});

// VIEW USER MODAL - Fetch user details
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

/* ===========================================
   EQUIPMENT MODULE – FRONTEND + MODALS
=========================================== */

document.addEventListener("DOMContentLoaded", () => {
  const filterCategory = document.getElementById("filterCategory");
  const filterStatus = document.getElementById("filterStatus");
  const searchInput = document.getElementById("searchInput");
  const cards = document.querySelectorAll(".equipment-card");

  // Apply filters to equipment cards
  function applyFilters() {
    const category = filterCategory ? filterCategory.value.toLowerCase() : "";
    const status = filterStatus ? filterStatus.value.toLowerCase() : "";
    const search = searchInput ? searchInput.value.toLowerCase() : "";

    cards.forEach((card) => {
      const c = (card.dataset.category || "").toLowerCase();
      const s = (card.dataset.status || "").toLowerCase();
      const name = (card.dataset.name || "").toLowerCase();

      const matchCategory = category === "" || c === category;
      const matchStatus = status === "" || s === status;
      const matchSearch = search === "" || name.includes(search);

      card.style.display = matchCategory && matchStatus && matchSearch ? "flex" : "none";
    });
  }

  if (filterCategory) filterCategory.addEventListener("change", applyFilters);
  if (filterStatus) filterStatus.addEventListener("change", applyFilters);
  if (searchInput) searchInput.addEventListener("input", applyFilters);

  /* ===================================
     VIEW EQUIPMENT MODAL (Aggregate)
  =================================== */
  const viewModal = document.getElementById("modalViewEquipment");
  if (viewModal) {
    viewModal.addEventListener("show.bs.modal", (event) => {
      const button = event.relatedTarget;
      const name = button.getAttribute("data-name");
      const category = button.getAttribute("data-category");
      const status = button.getAttribute("data-status");
      const available = button.getAttribute("data-available");
      const total = button.getAttribute("data-total");
      const image = button.getAttribute("data-image");

      // Populate modal with aggregate data
      document.getElementById("viewName").textContent = name || "N/A";
      document.getElementById("viewCategory").textContent = category || "N/A";
      document.getElementById("viewStatus").textContent = status ? status.charAt(0).toUpperCase() + status.slice(1) : "N/A";
      document.getElementById("viewTotal").textContent = total || "0";
      document.getElementById("viewAvailable").textContent = available || "0";
      document.getElementById("viewImage").src = image || "/Envizio/public/img/indextech.avif";
      document.getElementById("viewDescription").textContent = "Aggregate view - description from first item";
    });
  }

  /* ===================================
     EDIT EQUIPMENT MODAL (Individual)
  =================================== */
  const editModal = document.getElementById("modalEditEquipment");
  let currentGroupItems = [];

  if (editModal) {
    editModal.addEventListener("show.bs.modal", async (event) => {
      const button = event.relatedTarget;
      const name = button.getAttribute("data-name");
      const category = button.getAttribute("data-category");
      const status = button.getAttribute("data-status");

      // Get the selector element
      const selector = document.getElementById("editEquipmentIdSelector");
      if (!selector) {
        console.error("Edit ID selector not found");
        return;
      }

      // Show loading state
      selector.innerHTML = '<option value="">-- Loading IDs... --</option>';

      // Fetch all items in this group
      try {
        const response = await fetch(`equipment/getGroupItems?name=${encodeURIComponent(name)}&type=${encodeURIComponent(category)}&status=${encodeURIComponent(status)}`);
        const data = await response.json();
        
        if (data.items && data.items.length > 0) {
          currentGroupItems = data.items;

          // Populate ID selector
          selector.innerHTML = '<option value="">-- Select Equipment ID --</option>';
          currentGroupItems.forEach(item => {
            const option = document.createElement("option");
            option.value = item.equipment_id;
            option.textContent = `ID: ${item.equipment_id} - ${item.available == 1 ? 'Available' : 'Not Available'}`;
            selector.appendChild(option);
          });

          // Auto-select first item
          selector.value = currentGroupItems[0].equipment_id;
          populateEditForm(currentGroupItems[0]);

          // Handle ID selection change (remove old listeners first)
          const newSelector = selector.cloneNode(true);
          selector.parentNode.replaceChild(newSelector, selector);
          
          newSelector.addEventListener("change", function() {
            const selectedId = this.value;
            const selectedItem = currentGroupItems.find(item => item.equipment_id == selectedId);
            if (selectedItem) {
              populateEditForm(selectedItem);
            }
          });
        } else {
          selector.innerHTML = '<option value="">-- No items found --</option>';
        }
      } catch (error) {
        console.error("Error fetching group items:", error);
        selector.innerHTML = '<option value="">-- Error loading IDs --</option>';
      }
    });
  }

  function populateEditForm(item) {
    document.getElementById("editName").value = item.equipment_name || "";
    document.getElementById("editCategory").value = item.equipment_type || "";
    document.getElementById("editStatus").value = item.status || "active";
    document.getElementById("editAvailable").value = item.available || 0;
    document.getElementById("editDescription").value = item.description || "";
    
    const imageSrc = item.image ? `img/${item.image}` : "img/indextech.avif";
    document.getElementById("editImagePreview").src = imageSrc;
  }

  // Handle Edit Form Submission
  const saveEditBtn = document.getElementById("saveEditEquipment");
  if (saveEditBtn) {
    saveEditBtn.addEventListener("click", async function() {
      const selector = document.getElementById("editEquipmentIdSelector");
      const selectedId = selector ? selector.value : null;

      if (!selectedId) {
        alert("Please select an equipment ID to edit");
        return;
      }

      const formData = new FormData();
      formData.append("equipment_name", document.getElementById("editName").value);
      formData.append("equipment_type", document.getElementById("editCategory").value);
      formData.append("status", document.getElementById("editStatus").value);
      formData.append("available", document.getElementById("editAvailable").value);
      formData.append("description", document.getElementById("editDescription").value);

      // Handle image upload if file is selected
      const imageInput = document.querySelector("#modalEditEquipment input[type='file']");
      if (imageInput && imageInput.files.length > 0) {
        formData.append("equipment_image", imageInput.files[0]);
      }

      try {
        const response = await fetch(`equipment/update/${selectedId}`, {
          method: "POST",
          body: formData
        });

        const result = await response.json();
        
        if (result.success) {
          alert("Equipment updated successfully!");
          location.reload();
        } else {
          alert("Failed to update equipment: " + (result.error || "Unknown error"));
        }
      } catch (error) {
        console.error("Error updating equipment:", error);
        alert("An error occurred while updating equipment");
      }
    });
  }

  /* ===================================
     ADD EQUIPMENT MODAL (Batch Create)
  =================================== */
  const addModal = document.getElementById("modalAddEquipment");
  if (addModal) {
    const addForm = document.querySelector("#modalAddEquipment form");
    if (!addForm) {
      // Create form if not exists
      const saveBtn = document.querySelector("#modalAddEquipment .equipment-btn");
      if (saveBtn) {
        saveBtn.addEventListener("click", async function() {
          const name = document.querySelector("#modalAddEquipment input[placeholder*='Laptop']").value;
          const category = document.querySelector("#modalAddEquipment select").value;
          const quantity = document.querySelector("#modalAddEquipment input[type='number']").value;
          const description = document.querySelector("#modalAddEquipment textarea").value;
          const imageFile = document.querySelector("#modalAddEquipment input[type='file']").files[0];

          if (!name || !category || !quantity || quantity < 1) {
            alert("Please fill all required fields");
            return;
          }

          const formData = new FormData();
          formData.append("equipment_name", name);
          formData.append("equipment_type", category);
          formData.append("quantity", quantity);
          formData.append("description", description);
          if (imageFile) {
            formData.append("equipment_image", imageFile);
          }

          try {
            const response = await fetch("equipment/add", {
              method: "POST",
              body: formData
            });

            if (response.ok) {
              alert(`${quantity} equipment item(s) added successfully!`);
              location.reload();
            } else {
              alert("Failed to add equipment");
            }
          } catch (error) {
            console.error("Error adding equipment:", error);
            alert("An error occurred while adding equipment");
          }
        });
      }
    }
  }

  /* ===================================
     DEACTIVATE/ACTIVATE MODAL (Individual)
  =================================== */
  const confirmModal = document.getElementById("modalConfirmStatus");
  let confirmGroupItems = [];

  if (confirmModal) {
    confirmModal.addEventListener("show.bs.modal", async (event) => {
      const button = event.relatedTarget;
      const name = button.getAttribute("data-name");
      const category = button.getAttribute("data-category");
      const status = button.getAttribute("data-status");

      document.getElementById("confirmEquipmentName").textContent = name || "Unknown";
      
      const action = status === "active" ? "Deactivate" : "Activate";
      document.getElementById("confirmActionLabel").textContent = action;
      document.getElementById("confirmActionLabelInline").textContent = action.toLowerCase();

      // Get the selector element
      const selector = document.getElementById("confirmEquipmentIdSelector");
      if (!selector) {
        console.error("Confirm ID selector not found");
        return;
      }

      // Show loading state
      selector.innerHTML = '<option value="">-- Loading IDs... --</option>';

      // Fetch all items in this group
      try {
        const response = await fetch(`equipment/getGroupItems?name=${encodeURIComponent(name)}&type=${encodeURIComponent(category)}&status=${encodeURIComponent(status)}`);
        const data = await response.json();
        
        if (data.items && data.items.length > 0) {
          confirmGroupItems = data.items;

          // Populate ID selector
          selector.innerHTML = '<option value="">-- Select Equipment ID --</option>';
          confirmGroupItems.forEach(item => {
            const option = document.createElement("option");
            option.value = item.equipment_id;
            option.textContent = `ID: ${item.equipment_id} - ${item.available == 1 ? 'Available' : 'Not Available'}`;
            selector.appendChild(option);
          });

          // Auto-select first item
          if (confirmGroupItems.length > 0) {
            selector.value = confirmGroupItems[0].equipment_id;
          }
        } else {
          selector.innerHTML = '<option value="">-- No items found --</option>';
        }
      } catch (error) {
        console.error("Error fetching group items for confirm:", error);
        selector.innerHTML = '<option value="">-- Error loading IDs --</option>';
      }
    });
  }

  // Handle Confirm Button
  const confirmProceedBtn = document.getElementById("confirmProceedBtn");
  if (confirmProceedBtn) {
    confirmProceedBtn.addEventListener("click", async function() {
      const selector = document.getElementById("confirmEquipmentIdSelector");
      const selectedId = selector ? selector.value : null;

      if (!selectedId) {
        alert("Please select an equipment ID");
        return;
      }

      try {
        const response = await fetch(`equipment/toggleStatus/${selectedId}`, {
          method: "POST"
        });

        const result = await response.json();
        
        if (result.success) {
          alert(result.message || "Equipment status updated!");
          location.reload();
        } else {
          alert("Failed to update status: " + (result.error || "Unknown error"));
        }
      } catch (error) {
        console.error("Error toggling status:", error);
        alert("An error occurred while updating status");
      }
    });
  }
});

// ----------------------------------------------------------
// RESET PASSWORD FORM VALIDATION
// ----------------------------------------------------------
document.addEventListener('DOMContentLoaded', function() {
    const resetForm = document.getElementById('resetForm');
    const newPassword = document.getElementById('new_password');
    const confirmPassword = document.getElementById('confirm_password');

    function checkPasswordMatch() {
        if (confirmPassword.value && confirmPassword.value !== newPassword.value) {
            confirmPassword.classList.add('is-invalid');
        } else {
            confirmPassword.classList.remove('is-invalid');
        }
    }

    if (newPassword && confirmPassword) {
        newPassword.addEventListener('input', checkPasswordMatch);
        confirmPassword.addEventListener('input', checkPasswordMatch);
    }

    if (resetForm) {
        resetForm.addEventListener('submit', function(e) {
            if (newPassword.value !== confirmPassword.value) {
                e.preventDefault();
                confirmPassword.classList.add('is-invalid');
                return false;
            }
        });
    }
});

// ----------------------------------------------------------
// FORGOT PASSWORD FORM - LOADING STATE
// ----------------------------------------------------------
document.addEventListener('DOMContentLoaded', function() {
    const forgotForm = document.getElementById('forgotForm');
    const btnSendReset = document.getElementById('btnSendReset');
    
    if (forgotForm && btnSendReset) {
        forgotForm.addEventListener('submit', function() {
            const btnText = btnSendReset.querySelector('.btn-text');
            const btnSpinner = btnSendReset.querySelector('.btn-spinner');
            
            if (btnText) btnText.style.display = 'none';
            if (btnSpinner) btnSpinner.style.display = 'inline';
            btnSendReset.disabled = true;
        });
    }
});