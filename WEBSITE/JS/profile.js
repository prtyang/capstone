// VARIABLES
const returnFilesInput = document.getElementById("returnFiles");
const preview = document.getElementById("previewContainer");
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

let selectedFiles = [];

function initReturnUploader() {
  const input = document.getElementById("returnFiles");
  const preview = document.getElementById("previewContainer");

  if (!input) return;

  input.onchange = function () {
    const files = Array.from(this.files);

    files.forEach(file => {

      const isImage = file.type.startsWith("image/");
      const isVideo = file.type.startsWith("video/");

      const imageCount = selectedFiles.filter(f => f.type.startsWith("image/")).length;
      const videoCount = selectedFiles.filter(f => f.type.startsWith("video/")).length;

      //  LIMIT RULES
      if (isImage && imageCount >= 4) {
        alert("Maximum 4 images only");
        return;
      }

      if (isVideo && videoCount >= 1) {
        alert("Only 1 video allowed");
        return;
      }

      selectedFiles.push(file);
    });

    renderPreview(preview);
    input.value = ""; // reset input
  };
}

function renderPreview(preview) {
  preview.innerHTML = "";

  selectedFiles.forEach((file, index) => {
    const reader = new FileReader();

    reader.onload = function (e) {
      const wrapper = document.createElement("div");
      wrapper.className = "preview-item";

      let media;

      if (file.type.startsWith("image/")) {
        media = document.createElement("img");
      } else {
        media = document.createElement("video");
        media.controls = true;
      }

      media.src = e.target.result;

      const removeBtn = document.createElement("span");
      removeBtn.innerHTML = "×";
      removeBtn.className = "remove-btn";

      removeBtn.onclick = () => {
        selectedFiles.splice(index, 1);
        renderPreview(preview);
      };

      wrapper.appendChild(media);
      wrapper.appendChild(removeBtn);
      preview.appendChild(wrapper);
    };

    reader.readAsDataURL(file);
  });
}

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
// PROFILE IMAGE UPLOAD (FIX)
const fileInput = document.getElementById("fileInput");

if (fileInput) {
  fileInput.addEventListener("change", function () {

    const file = this.files[0];
    if (!file) return;

    const formData = new FormData();
    formData.append("image", file);

    // FIX: GET EMAIL DIRECTLY FROM INPUT
    const emailInput = document.getElementById("email").value;
    formData.append("email", emailInput);

   fetch("upload-profile.php", {
  method: "POST",
  body: formData
})
.then(res => res.text())
.then(response => {

  console.log("SERVER RESPONSE:", response);

  let res;

  try {
    res = JSON.parse(response);
  } catch (e) {
    alert("❌ PHP ERROR — Check console (F12)");
    return;
  }

  if (res.success) {

    const newImage = "../" + res.path;

    profileImg.src = newImage;
    profileImg.style.display = "block";
    placeholder.style.display = "none";

    let profile = JSON.parse(localStorage.getItem("profile")) || {};
    profile.image = newImage;

    localStorage.setItem("profile", JSON.stringify(profile));

  } else {
    alert("Upload failed: " + res.error);
  }

})
.catch(err => {
  console.error("FETCH ERROR:", err);
  alert("Fetch failed");
});

  });
}

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

  if (
    status === "request return" ||
    status === "waiting to refund" ||
    status === "refunded"
  ) return "refund";

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

${["request return", "waiting to refund", "refunded"].includes(order.status.toLowerCase()) ? `
  <div class="action-container return-box">

    <div class="return-status"
      style="
        color: ${
          order.status.toLowerCase() === "request return" ? "orange" :
          order.status.toLowerCase() === "waiting to refund" ? "#3498db" :
          "#2ecc71"
        };
        font-weight: 600;
      "
    >
      ${
        order.status.toLowerCase() === "request return" ? "Return Requested" :
        order.status.toLowerCase() === "waiting to refund" ? "Waiting to Refund" :
        "Completed"
      }
    </div>

    <div class="refund-message">
      <strong>Reason:</strong> ${order.refund_message || "No message"}
    </div>

    <div class="refund-images">
      ${
        order.refund_images && order.refund_images.length > 0
        ? order.refund_images.map(file => {

          if (file.match(/\.(mp4|webm|ogg)$/i)) {
            return `<video src="../../uploads/${file}" controls></video>`;
          } else {
            return `<img src="../../uploads/${file}" />`;
          }

        }).join("")
        : ""
      }
    </div>

  </div>
` : ""}

<!-- CANCEL BUTTON (ONLY ORDER PLACE) -->
${order.status.toLowerCase() === "to ship" ? `
  <div class="action-container">
    <button onclick="cancelOrder(${order.id})" class="cancel-btn">
      Cancel Order
    </button>
  </div>
` : ""}

<!-- SHIPPING → Order Received -->
${order.status.toLowerCase() === "shipped" ? `
  <div class="action-container">

    <button onclick="completeOrder(${order.id})" class="received-btn">
      Order Received
    </button>

    <button onclick="returnOrder(${order.id})" class="return-btn">
      Return / Refund
    </button>

  </div>
` : ""}

<!-- COMPLETED → Review -->
${order.status.toLowerCase() === "completed" ? `
  <div class="action-container">
    ${order.items.map(item => `
      ${
        item.reviewed
        ? `<button class="reviewed-btn" disabled>Done</button>` 
        : `<button class="review-btn" onclick="openReviewModal(${order.id}, ${item.product_id}, this)">
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

function cancelOrder(orderId) {

  if (!confirm("Are you sure you want to cancel this order?")) return;

  fetch("../HTML/update-order-status.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json"
    },
    body: JSON.stringify({
      id: orderId,
      status: "Cancel"
    })
  })
  .then(res => res.json())
  .then(res => {

    if (res.success) {
      alert("Order cancelled successfully");

      //  RELOAD ORDERS (moves to Cancel tab automatically)
      loadOrders();

    } else {
      alert("Failed to cancel order");
    }

  })
  .catch(err => {
    console.error(err);
    alert("Error occurred");
  });
}

let pendingReturnOrderId = null;

function returnOrder(orderId) {
  pendingReturnOrderId = orderId;

  document.getElementById("policyModal").style.display = "flex";
}

function closePolicyModal() {
  document.getElementById("policyModal").style.display = "none";
}

function proceedToReturn() {
  closePolicyModal();

  if (pendingReturnOrderId) {
    openReturnModal(pendingReturnOrderId);
  }
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

function completeOrder(orderId) {

  if (!confirm("Confirm you received this order?")) return;

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
      alert("Order marked as completed!");

      // reload orders → automatically moves to Completed tab
      loadOrders();

    } else {
      alert("Failed to update order");
    }

  })
  .catch(err => {
    console.error(err);
    alert("Error occurred");
  });
}

//
function openReturnModal(orderId) {
  document.getElementById("returnModal").style.display = "flex";
  document.getElementById("returnOrderId").value = orderId;

  initReturnUploader(); 
}

function closeReturnModal() {
  document.getElementById("returnModal").style.display = "none";
}

function submitReturn() {

  const orderId = document.getElementById("returnOrderId").value;
  const message = document.getElementById("returnMessage").value;

  if (!message.trim()) {
    alert("Please enter your reason");
    return;
  }

  const formData = new FormData();

  formData.append("order_id", orderId);
  formData.append("message", message);

  // 🔥 VERY IMPORTANT — SEND selectedFiles
  if (selectedFiles.length === 0) {
    alert("Please upload at least 1 file");
    return;
  }

  selectedFiles.forEach(file => {
    formData.append("files[]", file);
  });

  // 🔍 DEBUG
  console.log("FILES SENT:", selectedFiles);

  fetch("../HTML/submit-return.php", {
    method: "POST",
    body: formData
  })
  .then(res => res.json())
  .then(res => {
    console.log(res);

    if (res.success) {
      alert("Return request submitted!");

      selectedFiles = []; // reset

      closeReturnModal();
      loadOrders();

    } else {
      alert("Upload failed");
    }
  })
  .catch(err => {
    console.error(err);
    alert("Server error");
  });
}

