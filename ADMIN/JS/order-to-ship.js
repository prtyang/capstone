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

function shipOrder(id) {
  if (!confirm("Are you sure you want to arrange shipment?")) return;

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
      alert("Order is now Shipping");
      location.reload();
    }
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
      status: "Shipped" // THIS IS THE KEY
    })
  })
  .then(res => res.json())
  .then(res => {
    if (res.success) {
      alert("Order moved to Shipping");
      
      // REDIRECT TO SHIPPING PAGE
      window.location.href = "order-shipping.php";
    }
  });
}


function viewOrder(id) {
  window.location.href = "order-view.php?id=" + id;
}