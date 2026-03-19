let deleteIds = []; // store IDs to delete

document.addEventListener("DOMContentLoaded", () => {

  /* =============================
     DISPLAY ORDERS (WITH BUTTON)
  ============================= */
  const ordersContainer = document.getElementById("ordersContainer");

  const orders = [
    { id: 101, status: "Completed" },
    { id: 102, status: "Shipping" }
  ];

  orders.forEach(order => {
    const card = document.createElement("div");
    card.classList.add("order-card");

    card.innerHTML = `
      <p><strong>Order #${order.id}</strong></p>
      <p>Status: ${order.status}</p>
    `;

    if (order.status === "Completed") {
      const btn = document.createElement("button");
      btn.textContent = "Return / Refund";
      btn.classList.add("return-btn");

      btn.onclick = () => openReturnModal(order.id);

      card.appendChild(btn);
    }

    ordersContainer.appendChild(card);
  });

}); // ✅ ONLY ONE closing


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

          if (data.blocked.length > 0) {
            document.getElementById("pinError").innerText =
              "Cannot delete: product has existing orders";
          } else {
            document.getElementById("pinError").innerText = "";
          }
        }

        closePinModal();
      });

    } else {
      document.getElementById("pinError").innerText = "Incorrect PIN";
    }

  });
}


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