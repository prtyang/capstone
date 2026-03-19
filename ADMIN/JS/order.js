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
      status: "Shipped"
    })
  })
  .then(res => res.json())
  .then(res => {
    if (res.success) {
      alert("Order marked as Shipped");
      location.reload();
    }
  });
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
    } else {
      alert("Failed to update order");
    }
  });
}


function completeOrder(id) {

  if (!confirm("Mark this order as completed?")) return;

  fetch("update-order-status.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json"
    },
    body: JSON.stringify({
      id: id,
      status: "Completed"
    })
  })
  .then(res => res.json())
  .then(res => {
    if (res.success) {
      alert("Order Completed");
      location.reload();
    } else {
      alert("Failed to update");
    }
  });
}

function openReturnDetails(order) {

  document.getElementById("returnDetailsModal").style.display = "flex";

  // MESSAGE
  document.getElementById("returnMessage").innerText =
    "Reason: " + (order.refund_message || "No message");

  // IMAGES
  const container = document.getElementById("returnImages");
  container.innerHTML = "";

  if (order.refund_images) {
    try {
      const files = JSON.parse(order.refund_images);

      files.forEach(file => {
        let el;

        if (file.match(/\.(mp4|webm|ogg)$/i)) {
          el = document.createElement("video");
          el.controls = true;
        } else {
          el = document.createElement("img");
        }

        el.src = "../../uploads/" + file;
        container.appendChild(el);
      });

    } catch (e) {
      console.error("Invalid JSON", e);
    }
  }
}

function closeReturnDetails() {
  document.getElementById("returnDetailsModal").style.display = "none";
}

//
document.querySelector(".export").addEventListener("click", () => {
  const range = document.getElementById("calendarRange").value;

  if (!range) {
    alert("Please select a date");
    return;
  }

  let dates = range.split(" to ");
  let start = dates[0];
  let end = dates[1] || dates[0];

  window.location.href = `export_orders.php?start=${start}&end=${end}`;
});

//
let timeout;

document.querySelector(".search-input").addEventListener("input", function(){
  clearTimeout(timeout);

  timeout = setTimeout(() => {
    this.closest("form").submit();
  }, 500);
});


