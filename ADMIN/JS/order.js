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

//shipping
function shipOrder(id) {

  // CONFIRM FIRST
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
      alert("Order is now Shipping ");

      // reload page to update UI
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
function pickupOrder(id) {
  if (!confirm("Are you sure rider picked this order?")) return;

  fetch("update-order-status.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json"
    },
    body: JSON.stringify({
      id: id,
      process_stage: "Completed"
    })
  })
  .then(res => res.json())
  .then(res => {
    if (res.success) {
      alert("Moved to Completed ");
      location.reload();
    }
  });
}

function shipOrder(id) {
  if (!confirm("Are you sure you want to arrange this?")) return;

  fetch("update-status.php", {
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
    alert("Order updated!");
    location.reload();
  });
}

function pickupOrder(id) {
  if (!confirm("Mark as picked up by rider?")) return;

  fetch("update-status.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json"
    },
    body: JSON.stringify({
      id: id,
      status: "Shipped"
    })
  })
  .then(res => res.json())
  .then(() => {
    alert("Order marked as Shipped ");
    location.reload();
  });
}

function completeOrder(id) {
  if (!confirm("Mark this order as completed?")) return;

  fetch("update-status.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json"
    },
    body: JSON.stringify({
      order_id: id,
      status: "Completed" //
    })
  })
  .then(res => res.json())
  .then(res => {
    if (res.success) {
      alert("Order Completed ");
      location.reload();
    } else {
      alert("Failed to update");
    }
  })
  .catch(err => {
    console.error(err);
    alert("Error occurred");
  });
}
