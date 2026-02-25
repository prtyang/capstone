let deleteIds = []; // store IDs to delete
document.addEventListener("DOMContentLoaded", () => {

  const deleteSelectedBtn = document.getElementById("deleteSelectedBtn");

/* SEARCH FILTER */
  const searchInput = document.getElementById("searchInput");

  searchInput.addEventListener("input", function () {
    const keyword = this.value.toLowerCase();

    document.querySelectorAll(".product-row").forEach(row => {
      const name = row.querySelector("h4")?.textContent.toLowerCase() || "";
      const desc = row.querySelector("p")?.textContent.toLowerCase() || "";

      if (name.includes(keyword) || desc.includes(keyword)) {
        row.style.display = "";
      } else {
        row.style.display = "none";
      }
    });
  });


  /* SINGLE DELETE */
/* SINGLE DELETE */
document.querySelectorAll(".single-delete").forEach(btn => {
  btn.addEventListener("click", () => {
    const id = btn.dataset.id;

    deleteIds = [id];
    openPinModal();
  });
});

/* DELETE SELECTED */
deleteSelectedBtn.addEventListener("click", () => {
  const checked = document.querySelectorAll(".row-checkbox:checked");

  if (checked.length === 0) {
    alert("Select at least one product");
    return;
  }

  deleteIds = [...checked].map(cb => cb.value);
  openPinModal();
});

/* STATUS FILTER */
const statusFilter = document.getElementById("statusFilter");
const stockFilter  = document.getElementById("stockFilter");

function applyFilters() {
  const statusValue = statusFilter.value;
  const stockValue  = stockFilter.value;

  document.querySelectorAll(".product-row").forEach(row => {
    const rowStatus = row.dataset.status; // active / inactive
    const rowStock  = row.dataset.stock;  // low / medium / high

    const statusMatch =
      statusValue === "all" || rowStatus === statusValue;

    const stockMatch =
      stockValue === "all" || rowStock === stockValue;

    if (statusMatch && stockMatch) {
      row.style.display = "";
    } else {
      row.style.display = "none";
    }
  });
}

statusFilter.addEventListener("change", applyFilters);
stockFilter.addEventListener("change", applyFilters);

  /* STATUS TOGGLE */
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

});

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

      // DELETE NOW
      fetch("../HTML/delete-product.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ ids: deleteIds })
      })
      .then(res => res.text())
    .then(text => {
      console.log("RAW RESPONSE:", text); // 🔥 DEBUG

  const data = JSON.parse(text); // convert to JSON
        if (data.success) {

          // REMOVE ONLY DELETED ITEMS
          deleteIds.forEach(id => {
            if (!data.blocked.includes(id)) {
              const row = document
                .querySelector(`.single-delete[data-id="${id}"]`)
                ?.closest(".product-row");

              if (row) row.remove();
            }
          });

          // ❌ SHOW ERROR IF BLOCKED
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