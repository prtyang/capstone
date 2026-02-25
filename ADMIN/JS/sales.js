let chart;

/* =========================
  LOAD CHART FUNCTION
========================= */
function loadChart(type) {

  const ctx = document.getElementById('salesChart').getContext('2d');

  if (chart) chart.destroy();

  // 🔥dynamic Y-axis depending on type
  const isWeekly = type === "weekly";

  const maxValue = isWeekly ? 50000 : 100000;
  const stepSize = isWeekly ? 5000 : 10000;

  // GET DATA
  let labels = dataSets[type].labels;
  let data = dataSets[type].data;

  /* =========================
      FORCE X-AXIS TO SHOW
  ========================= */

  if (type === "weekly") {

    // if no data
    if (!labels || labels.length === 0) {
      labels = ["No Data"];
      data = [0];
    }
    if (labels.length === 1) {
      labels = [labels[0], ""];
      data = [data[0], data[0]];
    }
  }

  chart = new Chart(ctx, {
    type: 'line',

    data: {
      labels: labels,
      datasets: [{
        data: data,

        borderWidth: 4,
        tension: 0.5,
        borderColor: '#d81b60',

        fill: true,
        backgroundColor: (context) => {
          const ctx = context.chart.ctx;
          const gradient = ctx.createLinearGradient(0, 0, 0, 350);

          gradient.addColorStop(0, 'rgba(216,27,96,0.35)');
          gradient.addColorStop(1, 'rgba(216,27,96,0.05)');

          return gradient;
        },

        pointRadius: 0,
        pointHoverRadius: 0
      }]
    },

    options: {
      responsive: true,
      maintainAspectRatio: false,

      animation: {
        duration: 1200,
        easing: 'easeInOutQuart'
      },

      plugins: {
        legend: { display: false }
      },

      scales: {

        /* X AXIS */
        x: {
          display: true,
          grid: {
            display: false
          },
          ticks: {
            display: true,
            color: '#999',
            autoSkip: false,
            maxRotation: 0,
            minRotation: 0
          }
        },

        /* Y AXIS */
        y: {
          beginAtZero: true,
          min: 0,
          max: maxValue,

          grid: {
            color: '#eee'
          },

          ticks: {
            stepSize: stepSize,
            color: '#999',

            callback: (value) => {
              if (value === 0) return '0';
              return (value / 1000) + 'K';
            }
          }
        }
      }
    }
  });
}


/* =========================
   BUTTON SWITCH + FILTER
========================= */
const buttons = document.querySelectorAll(".filter-box button");
const monthFilter = document.getElementById("monthFilter");

buttons.forEach(btn => {
  btn.addEventListener("click", () => {

    const type = btn.dataset.type;

    if (type === "weekly") {
      window.location.href = "sales.php?type=weekly&month=" + monthFilter.value;
    }

    if (type === "monthly") {
      window.location.href = "sales.php?type=monthly";
    }

  });
});

/* =========================
   MONTH FILTER
========================= */
function changeMonth(month) {
  window.location.href = "sales.php?type=weekly&month=" + month;
}

/* =========================
   DEFAULT LOAD
========================= */
window.onload = () => {

  loadChart(currentType);

  // show/hide month dropdown
  if (monthFilter) {
    monthFilter.style.display = currentType === "weekly"
      ? "inline-block"
      : "none";
  }

};

function openPinModal() {
  document.getElementById("pinModal").style.display = "flex";
}

function closePinModal() {
  document.getElementById("pinModal").style.display = "none";
}

function submitPin() {
  const pin = document.getElementById("userPin").value.trim();

  fetch("../HTML/verify-pin.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded"
    },
    body: "pin=" + encodeURIComponent(pin)
  })
  .then(res => res.text())
  .then(response => {

    console.log("SERVER RESPONSE:", response); 

    if (response.trim() === "success") {
      document.getElementById("pinMessage").innerText =
        " Money will be transferred";

      setTimeout(() => {
        closePinModal();
      }, 2000);

    } else {
      document.getElementById("pinMessage").innerText =
        "Incorrect PIN";
    }

  });
}