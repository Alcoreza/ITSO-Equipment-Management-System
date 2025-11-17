document.addEventListener("DOMContentLoaded", function () {
  const deleteButtons = document.querySelectorAll(".delete-btn");

  deleteButtons.forEach((button) => {
    button.addEventListener("click", function (e) {
      e.preventDefault();
      const deleteUrl = this.getAttribute("data-url");
      const isProductPage = document
        .querySelector("h1")
        ?.textContent.toLowerCase()
        .includes("product");
      const itemType = isProductPage ? "Product" : "User";

      Swal.fire({
        title: `Delete ${itemType}?`,
        text: "This action cannot be undone.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#f97316",
        cancelButtonColor: "#6c757d",
        confirmButtonText: `Yes, delete ${itemType.toLowerCase()}!`,
        cancelButtonText: "Cancel",
        customClass: {
          popup: "rounded-4 shadow-lg",
        },
      }).then((result) => {
        if (result.isConfirmed) {
          window.location.href = deleteUrl;
        }
      });
    });
  });
});

document.addEventListener("DOMContentLoaded", function () {
  const forms = ["addUserForm", "editUserForm"];

  forms.forEach((formId) => {
    const form = document.getElementById(formId);
    if (!form) return;

    const password = form.querySelector("#password");
    const confirmPassword = form.querySelector("#confirmpassword");
    const errorMsg = form.querySelector("#passwordError");

    form.addEventListener("submit", function (e) {
      if (password.value.trim() === "" && confirmPassword.value.trim() === "") {
        return;
      }
      if (password.value.trim() !== confirmPassword.value.trim()) {
        errorMsg.style.display = "block";
        confirmPassword.classList.add("is-invalid");
        e.preventDefault();
      } else {
        errorMsg.style.display = "none";
        confirmPassword.classList.remove("is-invalid");
      }
    });
  });
});
