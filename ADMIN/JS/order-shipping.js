  // ADD STATUS = shipped
document.querySelector(".export").addEventListener("click", () => {
  const range = document.getElementById("calendarRange").value;

  if (!range) {
    alert("Please select date");
    return;
  }

  let dates = range.split(" to ");
  let start = dates[0];
  let end = dates[1] || dates[0];

  window.location.href = `export_orders.php?start=${start}&end=${end}&status=Shipped`;
});

//COMPLETED ONLY
document.querySelector(".export").addEventListener("click", () => {
  const range = document.getElementById("calendarRange").value;

  if (!range) {
    alert("Please select date");
    return;
  }

  let dates = range.split(" to ");
  let start = dates[0];
  let end = dates[1] || dates[0];

  window.location.href = `export_orders.php?start=${start}&end=${end}&status=Completed`;
});

  //  CANCEL ONLY
document.querySelector(".export").addEventListener("click", () => {
  const range = document.getElementById("calendarRange").value;

  if (!range) {
    alert("Please select date");
    return;
  }

  let dates = range.split(" to ");
  let start = dates[0];
  let end = dates[1] || dates[0];

  window.location.href = `export_orders.php?start=${start}&end=${end}&status=Cancel`;
});

//RETURN/REFUND
document.querySelector(".export").addEventListener("click", () => {
  const range = document.getElementById("calendarRange").value;

  if (!range) {
    alert("Please select date");
    return;
  }

  let dates = range.split(" to ");
  let start = dates[0];
  let end = dates[1] || dates[0];

  // ✅ MULTIPLE STATUS (IMPORTANT)
  window.location.href = `export_orders.php?start=${start}&end=${end}&status=Request Return,Waiting to Refund,Refunded`;
});
