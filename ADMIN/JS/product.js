let deleteIds = []; // store IDs to delete

document.addEventListener("DOMContentLoaded", () => {

});
/* =============================
   FILTER + SEARCH
============================= */
document.addEventListener("DOMContentLoaded", () => {

  const searchInput = document.getElementById("searchInput");
  const statusFilter = document.getElementById("statusFilter");
  const stockFilter = document.getElementById("stockFilter");

  const rows = document.querySelectorAll(".product-row");

  function filterProducts() {
    const searchValue = searchInput.value.toLowerCase();
    const statusValue = statusFilter.value;
    const stockValue = stockFilter.value;

    rows.forEach(row => {
      const text = row.innerText.toLowerCase();
      const status = row.dataset.status;
      const stock = row.dataset.stock;

      let show = true;

      // SEARCH
      if (!text.includes(searchValue)) {
        show = false;
      }

      // STATUS FILTER
      if (statusValue !== "all" && status !== statusValue) {
        show = false;
      }

      // STOCK FILTER
      if (stockValue !== "all" && stock !== stockValue) {
        show = false;
      }

      row.style.display = show ? "grid" : "none";
    });
  }

  // EVENTS
  searchInput.addEventListener("input", filterProducts);
  statusFilter.addEventListener("change", filterProducts);
  stockFilter.addEventListener("change", filterProducts);

});
/* =============================
   STATUS TOGGLE (OUTSIDE)
============================= */
document.addEventListener("click", function (e) {
  const el = e.target;

  if (!el.classList.contains("status-text")) return;
  if (!el.dataset.id || !el.dataset.status) return;

  const id = el.dataset.id;
  const current = el.dataset.status;
  const next = current === "active" ? "inactive" : "active";

  fetch("update-status.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ id: id, status: next })
  })
    .then(res => res.json())
    .then(data => {
      if (!data.success) return;

      el.dataset.status = next;
      el.textContent = next.charAt(0).toUpperCase() + next.slice(1);

      el.classList.remove("active", "inactive");
      el.classList.add(next);
    });
});


/* =============================
   PIN MODAL
============================= */
function openPinModal() {
  document.getElementById("pinModal").style.display = "flex";
}

function closePinModal() {
  document.getElementById("pinModal").style.display = "none";
  document.getElementById("actionPinInput").value = "";
  document.getElementById("pinError").innerText = "";
}

function confirmDeleteWithPin() {
  const pin = document.getElementById("actionPinInput").value.trim();

  fetch("../HTML/verify-action-pin.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded"
    },
    body: "pin=" + encodeURIComponent(pin)
  })
  .then(res => res.text())
  .then(response => {

    if (response.trim() === "success") {

      fetch("../HTML/delete-product.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ ids: deleteIds })
      })
      .then(res => res.text())
      .then(text => {

        const data = JSON.parse(text);

        if (data.success) {
          deleteIds.forEach(id => {
            if (!data.blocked.includes(id)) {
              const row = document
                .querySelector(`.single-delete[data-id="${id}"]`)
                ?.closest(".product-row");

              if (row) row.remove();
            }
          });
        }

        closePinModal();
      });

    } else {
      document.getElementById("pinError").innerText = "Incorrect PIN";
    }

  });
}

/* =============================
   DELETE BUTTON (SINGLE)
============================= */
document.addEventListener("click", function (e) {
  if (e.target.classList.contains("single-delete")) {
    
    const id = e.target.dataset.id;

    deleteIds = [id]; // store single ID
    openPinModal();   // open modal

  }
});


/* =============================
   DELETE SELECTED
============================= */
document.getElementById("deleteSelectedBtn")?.addEventListener("click", () => {

  const checked = document.querySelectorAll(".row-checkbox:checked");

  if (checked.length === 0) {
    alert("Please select at least one product");
    return;
  }

  deleteIds = [];

  checked.forEach(cb => {
    deleteIds.push(cb.value);
  });

  openPinModal();
});

/* =============================
   RETURN MODAL
============================= */
function openReturnModal(orderId) {
  document.getElementById("returnModal").style.display = "flex";
  document.getElementById("returnOrderId").value = orderId;
}

function closeReturnModal() {
  document.getElementById("returnModal").style.display = "none";
}


/* =============================
   IMAGE PREVIEW (SAFE)
============================= */
const fileInput = document.getElementById("returnFiles");
const preview = document.getElementById("previewContainer");

if (fileInput) {
  fileInput.addEventListener("change", () => {
    preview.innerHTML = "";

    Array.from(fileInput.files).forEach(file => {
      const reader = new FileReader();

      reader.onload = function(e) {
        const img = document.createElement("img");
        img.src = e.target.result;
        preview.appendChild(img);
      };

      reader.readAsDataURL(file);
    });
  });
}


// CLICK UPLOAD BOX
const uploadBox = document.querySelector(".upload-box");
if (uploadBox && fileInput) {
  uploadBox.addEventListener("click", () => {
    fileInput.click();
  });
}


/* =============================
   SUBMIT RETURN
============================= */
function submitReturn() {
  let orderId = document.getElementById("returnOrderId").value;
  let message = document.getElementById("returnMessage").value;
  let files = document.getElementById("returnFiles").files;

  let formData = new FormData();
  formData.append("order_id", orderId);
  formData.append("message", message);

  for (let i = 0; i < files.length; i++) {
    formData.append("media[]", files[i]);
  }

  fetch("submit_return.php", {
    method: "POST",
    body: formData
  })
  .then(res => res.text())
  .then(data => {
    alert("Return submitted!");
    closeReturnModal();
  });
}