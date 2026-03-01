// VARIABLES
const fileInput = document.getElementById("fileInput");
const profileImg = document.getElementById("profileImg");
const placeholder = document.getElementById("placeholder");

const saveBtn = document.querySelector(".save-btn");
const inputs = document.querySelectorAll("input, select");
const avatar = document.querySelector(".avatar");

const passwordInput = document.getElementById("password");
const togglePassword = document.getElementById("togglePassword");

const ordersContainer = document.getElementById("ordersContainer");
const tabs = document.querySelectorAll(".tab");
const name = document.getElementById("name");

let activeButton = null;
let isSaved = false;
let currentTab = "orderplace";

fileInput.addEventListener("change", function () {
  const file = this.files[0];

  if (file) {
    const reader = new FileReader();

    reader.onload = function (e) {
      profileImg.src = e.target.result;

      // show image
      profileImg.style.display = "block";
      placeholder.style.display = "none";
    };

    reader.readAsDataURL(file);
  }
});

// PASSWORD TOGGLE
togglePassword.addEventListener("click", function () {
  if (passwordInput.type === "password") {
    passwordInput.type = "text";
    this.classList.replace("fa-eye", "fa-eye-slash");
  } else {
    passwordInput.type = "password";
    this.classList.replace("fa-eye-slash", "fa-eye");
  }
});

//  SAVE / EDIT PROFILE
saveBtn.addEventListener("click", function () {

  if (!isSaved) {
    const profileData = {
      name: name.value.trim(),
      dob: dob.value,
      gender: gender.value,
      email: email.value,
      username: username.value,
      password: password.value,
      image: profileImg.src || ""
    };

    localStorage.setItem("profile", JSON.stringify(profileData));

    inputs.forEach(input => input.disabled = true);

    saveBtn.textContent = "Edit";
    saveBtn.classList.add("saved");

    isSaved = true;

  } else {
    inputs.forEach(input => input.disabled = false);

    saveBtn.textContent = "Save";
    saveBtn.classList.remove("saved");

    isSaved = false;
  }

});

// LOAD PROFILE
window.addEventListener("load", function () {
  const saved = JSON.parse(localStorage.getItem("profile"));

  if (saved) {
    name.value = saved.name || "";
    dob.value = saved.dob || "";
    gender.value = saved.gender || "";
    email.value = saved.email || "";
    username.value = saved.username || "";
    password.value = saved.password || "";

    if (saved.image) {
      profileImg.src = saved.image;
      profileImg.style.display = "block";
      placeholder.style.display = "none";
    }

    inputs.forEach(input => input.disabled = true);
    saveBtn.textContent = "Edit";
    saveBtn.classList.add("saved");
    isSaved = true;
  }

  loadOrders(); 
});

//  LOAD ORDERS (FILTERED)
function loadOrders() {

  fetch("../HTML/get-orders.php")
    .then(res => res.json())
    .then(orders => {

      ordersContainer.innerHTML = "";

function mapStatus(status) {

  status = status.toLowerCase();

  if (status === "to ship") return "orderplace";
  if (status === "shipping") return "toship";
  if (status === "shipped") return "shipping";
  if (status === "completed") return "completed"; 
  if (status === "cancel") return "cancel";

  return status;
}

const filtered = orders.filter(order =>
  mapStatus(order.status) === currentTab
);

  if (filtered.length === 0) {
    ordersContainer.innerHTML = "<p>No orders</p>";
    return;
  }

    filtered.forEach(order => {
    let itemsHTML = "";

        order.items.forEach(item => {
          itemsHTML += `
            <div class="product-row">
              <div class="img">
                <img src="../../uploads/${item.image}" 
                style="width:100%; height:100%; object-fit:cover;">
              </div>

              <div class="details">
                <p>${item.product_name}</p>
                <small>${item.color}</small><br>
                <small>${item.size}</small>
              </div>

              <div class="qty">x${item.qty}</div>
              <div class="price">₱${item.price * item.qty}</div>
            </div>
          `;
        });

        ordersContainer.innerHTML += `
          <div class="order-card">
            <div class="order-header">
              <div class="tag">Ordered on ${order.date}</div>
              <div class="status ${order.status}">${order.status}</div>
            </div>

            ${itemsHTML}

          <div class="total-row">
            <span>Total</span>
            <span>₱${order.total}</span>
          </div>

<!-- SHIPPING → Order Received -->
${order.status.toLowerCase() === "shipped" ? `
  <div class="action-container">
    <button onclick="completeOrder(${order.id})" class="received-btn">
      Order Received
    </button>
  </div>
` : ""}

<!-- COMPLETED → Review -->
${order.status.toLowerCase() === "completed" ? `
  <div class="action-container">
    ${order.items.map(item => `
      ${
      item.reviewed
  ?     `<button class="reviewed-btn" disabled>Done</button>`: 
          `<button class="review-btn" onclick="openReviewModal(${order.id}, ${item.product_id}, this)">
          Review
          </button>`
      }
    `).join("")}
  </div>
` : ""}
`;
  });
    })
    .catch(err => {
      console.error("Fetch error:", err);
    });
}

// TAB CLICK FUNCTION
tabs.forEach(tab => {
  tab.addEventListener("click", function () {

    tabs.forEach(t => t.classList.remove("active"));
    this.classList.add("active");

    currentTab = this.dataset.tab;

    loadOrders();
  });
});

function completeOrder(orderId) {

  if (!confirm("Confirm you received the order?")) return;

  fetch("../HTML/update-order-status.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json"
    },
    body: JSON.stringify({
      id: orderId,         
      status: "Completed"
    })  
  })
  .then(res => res.json())
  .then(res => {
    if (res.success) {
      alert("Order marked as Completed ");
      loadOrders(); 
    } else {
      alert("Failed to update");
    }
  })
  .catch(err => {
    console.error(err);
    alert("Error occurred");
  });
}
//REVIEW
let selectedRating = 0;
let currentOrderId = null;

function closeModal() {
  document.getElementById("reviewModal").style.display = "none";
}

let stars;

window.addEventListener("load", () => {
  stars = document.querySelectorAll("#starRating i");


});

// RESET when opening modal
let currentProductId = null;

function openReviewModal(orderId, productId, btn) {
  currentOrderId = orderId;
  currentProductId = productId;
  activeButton = btn; 

  document.getElementById("reviewModal").style.display = "flex";

  selectedRating = 0;
  resetStars();
}

// RESET stars
function resetStars() {
  stars.forEach(star => star.classList.remove("active"));
}

// CLICK ONLY (simple + accurate)
window.addEventListener("load", () => {
  stars = document.querySelectorAll("#starRating i");

  stars.forEach((star, index) => {
    star.addEventListener("click", () => {
      selectedRating = index + 1;

      stars.forEach((s, i) => {
        if (i < selectedRating) {
          s.classList.add("active");
        } else {
          s.classList.remove("active");
        }
      });
    });
  });
});

function submitReview() {
  const profile = JSON.parse(localStorage.getItem("profile")) || {};

  const userName = profile.name;

  if (!userName) {
    alert("Please set your name in Profile first.");
  return;
}
  const userImage = profile.image || "";

  
  console.log("Product ID:", currentProductId);
  const comment = document.getElementById("reviewComment").value;

  if (selectedRating == 0) {
    alert("Please select rating");
    return;
  }

const formData = new FormData();

formData.append("product_id", currentProductId);
formData.append("order_id", currentOrderId);
formData.append("rating", selectedRating);
formData.append("comment", comment);

formData.append("user_name", userName);
formData.append("user_image", userImage);

fetch("../HTML/submit-review.php", {
  method: "POST",
  body: formData
})

.then(res => res.json())
  .then(res => {
    console.log(res);

    if (res.success) {
      alert("Review saved!");
      closeModal();
      if (activeButton) {
      activeButton.innerText = "Done";
      activeButton.classList.remove("review-btn");
      activeButton.classList.add("reviewed-btn");
      activeButton.disabled = true;
    }
    } else {
      alert("Error: " + res.error);
    }
  })
  .catch(err => {
    console.error(err);
    alert("Failed to connect to server");
  });
}
// back btn
function goBack() {
  window.history.back();
}