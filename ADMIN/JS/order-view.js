
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

function cancelOrder(orderId) {
  currentOrderId = orderId;
  openPinModal();
}

function openPinModal() {
  document.getElementById("pinModal").style.display = "flex";
}

function closePinModal() {
  document.getElementById("pinModal").style.display = "none";
  document.getElementById("actionPinInput").value = "";
  document.getElementById("pinError").innerText = "";
}

function confirmCancelWithPin() {
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

      // ✅ CANCEL ORDER
      fetch("cancel-order.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json"
        },
        body: JSON.stringify({
          id: currentOrderId
        })
      })
      .then(res => res.json())
      .then(res => {

        if (res.success) {
          alert("Order cancelled successfully");
          window.location.href = "order-cancel.php";
        } else {
          document.getElementById("pinError").innerText =
            res.message || "Cancel failed";
        }

      });

      closePinModal();

    } else {
      document.getElementById("pinError").innerText = "Incorrect PIN";
    }

  });
}