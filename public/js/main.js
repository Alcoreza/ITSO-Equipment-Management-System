/* Global app JS — updated equipment modal handlers and filters */

/* ... (existing code for auth/forms/users remains above in your file) ...
   The file below replaces the equipment-related parts with working handlers.
*/

document.addEventListener("DOMContentLoaded", function () {
  // --- Setup base for fetch URLs ---
  const BASE = (typeof BASE_URL !== "undefined") ? String(BASE_URL).replace(/\/$/, "") : "";

  // --- Filter logic (search + category + status) ---
  const filterCategory = document.getElementById("filterCategory");
  const filterStatus = document.getElementById("filterStatus");
  const searchInput = document.getElementById("searchInput");

  function filterCards() {
    const category = filterCategory ? filterCategory.value.toLowerCase() : "";
    const status = filterStatus ? filterStatus.value.toLowerCase() : "";
    const search = searchInput ? searchInput.value.toLowerCase() : "";

    document.querySelectorAll(".equipment-card").forEach(card => {
      const c = (card.dataset.type || "").toLowerCase();
      const s = (card.dataset.status || "").toLowerCase();
      const name = (card.dataset.name || "").toLowerCase();

      const matchCategory = !category || c === category;
      const matchStatus = !status || s === status;
      const matchSearch = !search || name.includes(search);

      card.style.display = (matchCategory && matchStatus && matchSearch) ? "block" : "none";
    });
  }

  if (filterCategory) filterCategory.addEventListener("change", filterCards);
  if (filterStatus) filterStatus.addEventListener("change", filterCards);
  if (searchInput) searchInput.addEventListener("input", filterCards);

  // --- VIEW modal: populate from data attributes on trigger button ---
  const modalView = document.getElementById("modalViewEquipment");
  if (modalView) {
    modalView.addEventListener("show.bs.modal", function (event) {
      const button = event.relatedTarget;
      if (!button) return;

      const id = button.getAttribute("data-id") || "";
      const name = button.getAttribute("data-name") || "";
      const type = button.getAttribute("data-type") || "";
      const status = button.getAttribute("data-status") || "";
      const available = button.getAttribute("data-available") === "1" ? "Yes" : "No";
      const image = button.getAttribute("data-image") || "";
      const description = button.getAttribute("data-description") || "No description available.";

      const imgEl = document.getElementById("viewEquipmentImage");
      const nameEl = document.getElementById("viewEquipmentName");
      const typeEl = document.getElementById("viewEquipmentType");
      const statusEl = document.getElementById("viewEquipmentStatus");
      const availEl = document.getElementById("viewEquipmentAvailable");
      const descEl = document.getElementById("viewEquipmentDescription");

      if (imgEl) imgEl.src = image;
      if (nameEl) nameEl.textContent = name;
      if (typeEl) typeEl.textContent = type;
      if (statusEl) statusEl.textContent = status || "";
      if (availEl) availEl.textContent = available;
      if (descEl) descEl.textContent = description;
    });
  }

  // --- EDIT modal: populate and handle Save (AJAX) ---
  const modalEdit = document.getElementById("modalEditEquipment");
  if (modalEdit) {
    modalEdit.addEventListener("show.bs.modal", function (event) {
      const button = event.relatedTarget;
      if (!button) return;

      const id = button.getAttribute("data-id") || "";
      const name = button.getAttribute("data-name") || "";
      const type = button.getAttribute("data-type") || "";
      const status = button.getAttribute("data-status") || "";
      const available = button.getAttribute("data-available") || "1";
      const image = button.getAttribute("data-image") || "";
      const description = button.getAttribute("data-description") || "";

      const idEl = document.getElementById("editEquipmentID");
      const nameEl = document.getElementById("editEquipmentName");
      const typeEl = document.getElementById("editEquipmentType");
      const statusEl = document.getElementById("editEquipmentStatus");
      const availEl = document.getElementById("editEquipmentAvailable");
      const imgEl = document.getElementById("editEquipmentImage");
      const descEl = document.getElementById("editEquipmentDescription");

      if (idEl) idEl.value = id;
      if (nameEl) nameEl.value = name;
      if (typeEl) typeEl.value = type || "";
      if (statusEl) statusEl.value = status || "active";
      if (availEl) availEl.value = available;
      if (imgEl) imgEl.src = image;
      if (descEl) descEl.value = description;
    });

    // Save changes button
    const saveBtn = document.getElementById("saveEditEquipment");
    if (saveBtn) {
      saveBtn.addEventListener("click", function (e) {
        e.preventDefault();
        const id = document.getElementById("editEquipmentID").value;
        if (!id) {
          alert("Missing equipment ID");
          return;
        }

        // Collect fields
        const payload = {
          equipment_name: document.getElementById("editEquipmentName").value,
          equipment_type: document.getElementById("editEquipmentType").value,
          status: document.getElementById("editEquipmentStatus").value,
          available: document.getElementById("editEquipmentAvailable").value,
          description: document.getElementById("editEquipmentDescription").value
        };

        const url = BASE ? `${BASE}/equipment/update/${id}` : `/equipment/update/${id}`;

        fetch(url, {
          method: "POST",
          headers: { "Content-Type": "application/json", "X-Requested-With": "XMLHttpRequest" },
          body: JSON.stringify(payload),
        })
        .then(res => {
          if (!res.ok) throw new Error("Network response was not ok");
          return res.json();
        })
        .then(data => {
          if (data.success) {
            // Update card DOM so UI matches result without refresh
            const card = document.querySelector(`.equipment-card[data-id="${id}"]`);
            if (card) {
              card.dataset.name = payload.equipment_name;
              card.dataset.type = payload.equipment_type;
              card.dataset.status = payload.status;
              card.dataset.available = payload.available;
              card.dataset.description = payload.description;
              // Update visible texts
              const nameNode = card.querySelector(".equipment-name");
              const metaNode = card.querySelector(".equipment-meta");
              if (nameNode) nameNode.textContent = payload.equipment_name;
              if (metaNode) metaNode.textContent = `${payload.equipment_type} • Available: ${payload.available === "1" ? "Yes" : "No"}`;
              // Adjust inactive class
              if (payload.status === "inactive") card.classList.add("inactive-item");
              else card.classList.remove("inactive-item");
            }

            // Close modal
            const bsModal = bootstrap.Modal.getInstance(modalEdit);
            if (bsModal) bsModal.hide();
          } else {
            alert("Update failed: " + (data.error || "unknown"));
          }
        })
        .catch(err => {
          console.error("Error saving equipment:", err);
          alert("An error occurred while saving. Check console.");
        });
      });
    }
  }

  // --- CONFIRM toggle modal (activate/deactivate) ---
  const modalConfirm = document.getElementById("modalConfirmStatus");
  if (modalConfirm) {
    modalConfirm.addEventListener("show.bs.modal", function (event) {
      const button = event.relatedTarget;
      if (!button) return;

      const id = button.getAttribute("data-id") || "";
      const name = button.getAttribute("data-name") || "this item";
      const status = button.getAttribute("data-status") || "active";

      const actionLabel = (status === "active") ? "Deactivate" : "Activate";

      const labelEl = document.getElementById("confirmActionLabel");
      const inlineEl = document.getElementById("confirmActionLabelInline");
      const nameEl = document.getElementById("confirmEquipmentName");
      const idEl = document.getElementById("confirmEquipmentID");

      if (labelEl) labelEl.textContent = actionLabel;
      if (inlineEl) inlineEl.textContent = actionLabel.toLowerCase();
      if (nameEl) nameEl.textContent = name;
      if (idEl) idEl.value = id;
    });

    const proceedBtn = document.getElementById("confirmProceedBtn");
    if (proceedBtn) {
      proceedBtn.addEventListener("click", function () {
        const id = document.getElementById("confirmEquipmentID").value;
        if (!id) {
          alert("Missing equipment ID");
          return;
        }

        const url = BASE ? `${BASE}/equipment/toggle/${id}` : `/equipment/toggle/${id}`;

        fetch(url, {
          method: "POST",
          headers: { "X-Requested-With": "XMLHttpRequest" }
        })
        .then(res => {
          if (!res.ok) throw new Error("Network response was not ok");
          return res.json();
        })
        .then(data => {
          if (data.success) {
            // Update card DOM
            const card = document.querySelector(`.equipment-card[data-id="${id}"]`);
            if (card) {
              card.dataset.status = data.status;
              const inlineLabel = document.getElementById("confirmActionLabel");
              if (data.status === "inactive") card.classList.add("inactive-item");
              else card.classList.remove("inactive-item");
              // Update any visible status text if present
            }

            // Close modal
            const bsModal = bootstrap.Modal.getInstance(modalConfirm);
            if (bsModal) bsModal.hide();
          } else {
            alert("Toggle failed: " + (data.error || "unknown"));
          }
        })
        .catch(err => {
          console.error("Error toggling equipment:", err);
          alert("An error occurred while toggling. Check console.");
        });
      });
    }
  }

  // --- Add equipment (front-end only placeholder) ---
  const btnAdd = document.getElementById("btnAddEquipment");
  if (btnAdd) {
    btnAdd.addEventListener("click", function () {
      // Currently frontend-only: you can extend to POST to /equipment/create
      alert("Add equipment is currently frontend-only. Implement create endpoint to persist.");
      const bsModal = bootstrap.Modal.getInstance(document.getElementById("modalAddEquipment"));
      if (bsModal) bsModal.hide();
    });
  }
});