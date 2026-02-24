function pickupOrder(id) {

  if (!confirm("Are you sure rider picked up this order?")) return;

  fetch("update-order-status.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json"
    },
    body: JSON.stringify({
      id: id,
      status: "Shipped"   // ✅ FINAL STATUS
    })
  })
  .then(res => res.json())
  .then(res => {

    if (res.success) {
      alert("Order marked as Shipped ✅");

      // ✅ REDIRECT TO COMPLETED PAGE
      window.location.href = "to-ship-completed.php";
    }

  })
  .catch(err => {
    console.error(err);
    alert("Error updating order");
  });
}