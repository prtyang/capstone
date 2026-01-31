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
  document.querySelectorAll(".single-delete").forEach(btn => {
    btn.addEventListener("click", () => {
      const row = btn.closest(".product-row");
      const id = btn.dataset.id;

      if (!confirm("Delete this product?")) return;

      fetch("../HTML/delete-product.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ ids: [id] })
      })
        .then(res => res.json())
        .then(data => {
          if (data.success) row.remove();
        });
    });
  });

  /* DELETE SELECTED */
  deleteSelectedBtn.addEventListener("click", () => {
    const checked = document.querySelectorAll(".row-checkbox:checked");
    if (checked.length === 0) {
      alert("Select at least one product");
      return;
    }

    const ids = [...checked].map(cb => cb.value);

    fetch("../HTML/delete-product.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ ids })
    })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          checked.forEach(cb => cb.closest(".product-row").remove());
        }
      });
  });

  /* STATUS FILTER */
  statusFilter.addEventListener("change", function () {
    const value = this.value;

    document.querySelectorAll(".product-row").forEach(row => {
      const statusEl = row.querySelector(".status-text");
      if (!statusEl) return;

      const status = statusEl.dataset.status;

      if (value === "all" || status === value) {
        row.style.display = "";
      } else {
        row.style.display = "none";
      }
    });
  });

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
