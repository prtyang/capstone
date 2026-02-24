const tabs = document.querySelectorAll('.tabs span');

tabs.forEach(tab => {
  tab.addEventListener('click', () => {
    document.querySelector('.tabs .active').classList.remove('active');
    tab.classList.add('active');
  });
});

function filterOrders() {
  const range = document.getElementById("calendarRange").value;

  if (!range.includes("to")) return;

  const [from, to] = range.split(" to ");

  console.log("From:", from);
  console.log("To:", to);
}

//////////////////////////////////////////////////////
// ✅ ADD THIS PART BELOW (IMPORTANT 🔥)
//////////////////////////////////////////////////////

function shipOrder(id) {

  // ✅ CONFIRM FIRST
  if (!confirm("Are you sure you want to arrange shipment?")) {
    return;
  }

  fetch("update-order-status.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json"
    },
    body: JSON.stringify({
      id: id,
      status: "Shipping"
    })
  })
  .then(res => res.json())
  .then(res => {

    if (res.success) {
      alert("Order is now Shipping ✅");

      // 🔥 reload page to update UI
      location.reload();
    } else {
      alert("Failed to update order");
    }

  })
  .catch(err => {
    console.error(err);
    alert("Error updating order");
  });
}

function shipOrder(id) {

  // ✅ CONFIRM FIRST
  if (!confirm("Are you sure you want to arrange shipment?")) {
    return;
  }

  fetch("update-order-status.php", { // ⚠️ IMPORTANT (correct file)
    method: "POST",
    headers: {
      "Content-Type": "application/json"
    },
    body: JSON.stringify({
      id: id,
      status: "Shipping"
    })
  })
  .then(res => res.json())
  .then(res => {

    if (res.success) {
      alert("Order is now Shipping ✅");

      // 🔥 REDIRECT HERE (THIS IS WHAT YOU WANT)
      window.location.href = "to-ship-process.php";

    } else {
      alert("Failed to update order ❌");
    }

  })
  .catch(err => {
    console.error(err);
    alert("Error updating order");
  });
}
