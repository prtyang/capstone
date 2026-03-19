
function goBack() {
  const referrer = document.referrer;

  // If user came from same website
  if (referrer && referrer.includes(window.location.hostname)) {
    window.location.href = referrer;
  } else {
    // fallback if opened directly
    window.location.href = "order-cancel.php";
  }
}

let currentOrderId = null;

function approveRefund(id, btn) {
  if (!confirm("Approve this refund?")) return;

  fetch("update-order-status.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json"
    },
    body: JSON.stringify({
      id: id,
      status: "Waiting to Refund"
    })
  })
  .then(res => res.json())
  .then(res => {
    if (res.success) {

      // CHANGE BUTTON TO "REFUND NOW"
      btn.innerText = "Refund Now";
      btn.classList.remove("approve-btn");
      btn.classList.add("refund-btn");

      // change action
      btn.onclick = function () {
        processRefund(id, btn);
      };

    }
  });
}

function openPinModal() {
  document.getElementById("pinModal").style.display = "flex";
}

function closePinModal() {
  document.getElementById("pinModal").style.display = "none";
  document.getElementById("actionPinInput").value = "";
  document.getElementById("pinError").innerText = "";
}

function cancelOrder(orderId) {

    if (!confirm("Are you sure you want to cancel this order?")) return;

    fetch("cancel-order.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            order_id: orderId
        })
    })
    .then(res => res.json())
    .then(data => {

        if (data.success) {

            // 🔥 CHANGE BUTTON UI
            const btn = document.querySelector(".cancel-btn");

            btn.innerText = "Cancelled";
            btn.disabled = true;
            btn.style.background = "gray";
            btn.style.cursor = "not-allowed";

            alert("Order cancelled successfully!");

        } else {
            alert("Failed to cancel order.");
        }

    })
    .catch(err => {
        console.error(err);
        alert("Error cancelling order.");
    });
}

//
function approveRefund(id) {
  if (!confirm("Approve this refund?")) return;

  fetch("update-order-status.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json"
    },
    body: JSON.stringify({
      id: id,
      status: "Waiting to Refund"
    })
  })
  .then(res => res.json())
  .then(res => {
    if (res.success) {
      alert("Refund approved");
      location.reload();
    }
  });
}

function processRefund(id, btn) {
  if (!confirm("Mark as refunded?")) return;

  fetch("update-order-status.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json"
    },
    body: JSON.stringify({
      id: id,
      status: "Refunded"
    })
  })
  .then(res => res.json())
  .then(res => {
    if (res.success) {

      // CHANGE BUTTON TO FINAL STATE
      btn.innerText = "Refunded";
      btn.classList.remove("refund-btn");
      btn.classList.add("disabled-btn");

      btn.disabled = true;

    }
  });
}

