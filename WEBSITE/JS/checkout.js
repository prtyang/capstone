document.addEventListener("DOMContentLoaded", () => {

  /* =============================
        LOAD PRODUCT IN SUMMARY
  ============================== */
const checkoutItems = JSON.parse(localStorage.getItem("checkoutItems"));

// CHECK FIRST
if (!checkoutItems || checkoutItems.length === 0) {
  alert("No item selected.");
  window.location.href = "cart.php";
  return;
}

const totalItems = checkoutItems.reduce((sum, item) => {
  return sum + item.qty;
}, 0);

const orderItemContainer = document.getElementById("orderItem");

orderItemContainer.innerHTML = "";

// REMOVE DUPLICATE subtotal
checkoutItems.forEach(item => {

  const itemTotal = item.price * item.qty;

  orderItemContainer.innerHTML += `
    <div class="order-item">
      <div class="img-placeholder">
        <img src="../../uploads/${item.image}" 
        style="width:100%; height:100%; object-fit:cover;">
      </div>

      <div>
        <p>${item.name}</p>
        <small>Size: ${item.size}</small><br>
        <small>Color: ${item.color}</small>
      </div>

      <div class="order-price">
        ₱${itemTotal}
        <div class="qty">x${item.qty}</div>
      </div>
    </div>
  `;
});

  /* =============================
          PRICE COMPUTATION
  ============================== */
  const itemCountEl = document.getElementById("itemCount");
  const subtotalEl = document.getElementById("subtotal");
  const deliveryEl = document.getElementById("delivery");
  const totalEl = document.getElementById("total");

  let subtotal = checkoutItems.reduce((sum, item) => {
  return sum + (item.price * item.qty);
}, 0);
  let baseDelivery = 45;
  let expressExtra = 0;
  let discount = 0;

  function updateTotal() {
  const deliveryTotal = baseDelivery + expressExtra;

  subtotalEl.innerText = "₱" + subtotal;
  deliveryEl.innerText = "₱" + deliveryTotal;
  totalEl.innerText = "₱" + (subtotal + deliveryTotal - discount);

  itemCountEl.innerText = totalItems === 1 
    ? "1 item" 
    : totalItems + "";
}

  updateTotal();

  /* =============================
        DELIVERY METHOD
  ============================== */
  document.querySelectorAll("input[name='delivery']").forEach(r => {
    r.addEventListener("change", () => {
      expressExtra = r.parentElement.textContent.includes("Express") ? 15 : 0;
      updateTotal();
    });
  });

  /* =============================
        PAYMENT METHOD
  ============================== */
  const paymentRadios = document.querySelectorAll("input[name='payment']");
  const cardFields = document.querySelector(".card-fields");

  cardFields.style.display = "none";

  paymentRadios.forEach(radio => {
    radio.addEventListener("change", () => {
      cardFields.style.display =
        radio.parentElement.textContent.includes("Card") ? "block" : "none";
    });
  });

  /* =============================
        SAVE CONTACT INFO
  ============================== */
  const firstName = document.querySelector("input[placeholder='First Name']");
  const lastName = document.querySelector("input[placeholder='Last Name']");
  const email = document.querySelector("input[type='email']");
  const phone = document.getElementById("phoneInput");
  const contactCheck = document.querySelectorAll(".checkbox input")[0];

  const savedUser = JSON.parse(localStorage.getItem("userInfo"));

  if (savedUser) {
    firstName.value = savedUser.firstName || "";
    lastName.value = savedUser.lastName || "";
    email.value = savedUser.email || "";
    phone.value = savedUser.phone || "";
    contactCheck.checked = true;
  }

  function saveUser() {
    if (!contactCheck.checked) return;

    localStorage.setItem("userInfo", JSON.stringify({
      firstName: firstName.value,
      lastName: lastName.value,
      email: email.value,
      phone: phone.value
    }));
  }

  [firstName, lastName, email, phone].forEach(i => {
    i.addEventListener("input", saveUser);
  });

  contactCheck.addEventListener("change", () => {
    contactCheck.checked ? saveUser() : localStorage.removeItem("userInfo");
  });

  /* =============================
        VALIDATIONS
  ============================== */
  // phone numbers only
  phone.addEventListener("input", () => {
    phone.value = phone.value.replace(/[^0-9]/g, "");
  });

  // postal 4 digits
  const postal = document.getElementById("postalInput");
  postal.addEventListener("input", () => {
    postal.value = postal.value.replace(/[^0-9]/g, "").slice(0, 4);
  });

  // email validation
  email.addEventListener("blur", () => {
    const pattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!pattern.test(email.value)) {
      alert("Invalid email");
      email.focus();
    }
  });

  const fullAddress = document.getElementById("fullAddressInput");
  const addressCheck = document.querySelectorAll(".checkbox input")[1];

  /* =============================
      LOCATION API + FILTE
  ============================== */

  let provinces = [];
  let cities = [];
  let barangays = [];

  const provinceInput = document.getElementById("provinceInput");
  const cityInput = document.getElementById("cityInput");
  const brgyInput = document.getElementById("brgyInput");

  const provinceList = document.getElementById("provinceList");
  const cityList = document.getElementById("cityList");
  const brgyList = document.getElementById("brgyList");

// LOAD SAVED ADDRESS
const savedAddress = JSON.parse(localStorage.getItem("addressInfo"));

if (savedAddress) {
  provinceInput.value = savedAddress.province || "";
  cityInput.value = savedAddress.city || "";
  brgyInput.value = savedAddress.brgy || "";
  postal.value = savedAddress.postal || "";
  fullAddress.value = savedAddress.fullAddress || "";

  cityInput.disabled = false;
  brgyInput.disabled = false;

  addressCheck.checked = true;
}

  // LOAD PROVINCES
  fetch("https://psgc.gitlab.io/api/provinces/")
    .then(res => res.json())
    .then(data => {
      provinces = data;
    });

  function showDropdown(list, items, input, callback) {
    list.innerHTML = "";

    if (!items.length) {
      list.style.display = "none";
      return;
    }

    list.style.display = "block";

    items.slice(0, 20).forEach(item => {
      const div = document.createElement("div");
      div.textContent = item.name;

      div.onclick = () => {
        input.value = item.name;
        list.style.display = "none";
        callback(item);
      };

      list.appendChild(div);
    });
  }

  provinceInput.addEventListener("focus", () => {
  showDropdown(provinceList, provinces, provinceInput, (p) => {

    cityInput.disabled = false;
    cityInput.value = "";
    brgyInput.value = "";
    brgyInput.disabled = true;

    fetch(`https://psgc.gitlab.io/api/provinces/${p.code}/cities-municipalities/`)
      .then(res => res.json())
      .then(data => {
        cities = data;
      });
  });
});

  // PROVINCE
  provinceInput.addEventListener("input", () => {
    const val = provinceInput.value.toLowerCase();

    const filtered = provinces.filter(p =>
      p.name.toLowerCase().includes(val)
    );

    showDropdown(provinceList, filtered, provinceInput, (p) => {

      cityInput.disabled = false;
      cityInput.value = "";
      brgyInput.value = "";
      brgyInput.disabled = true;

      fetch(`https://psgc.gitlab.io/api/provinces/${p.code}/cities-municipalities/`)
        .then(res => res.json())
        .then(data => {
          cities = data;
        });

    });
  });

  // CITY
  cityInput.addEventListener("input", () => {
    const val = cityInput.value.toLowerCase();

    const filtered = cities.filter(c =>
      c.name.toLowerCase().includes(val)
    );

    showDropdown(cityList, filtered, cityInput, (c) => {

      brgyInput.disabled = false;
      brgyInput.value = "";

      fetch(`https://psgc.gitlab.io/api/cities-municipalities/${c.code}/barangays/`)
        .then(res => res.json())
        .then(data => {
          barangays = data;
        });

    });
  });

  cityInput.addEventListener("focus", () => {
  showDropdown(cityList, cities, cityInput, (c) => {

    brgyInput.disabled = false;
    brgyInput.value = "";

    fetch(`https://psgc.gitlab.io/api/cities-municipalities/${c.code}/barangays/`)
      .then(res => res.json())
      .then(data => {
        barangays = data;
      });
  });
});

  // BARANGAY
  brgyInput.addEventListener("focus", () => {
  showDropdown(brgyList, barangays, brgyInput, () => {});
});

  brgyInput.addEventListener("input", () => {
    const val = brgyInput.value.toLowerCase();

    const filtered = barangays.filter(b =>
      b.name.toLowerCase().includes(val)
    );

    showDropdown(brgyList, filtered, brgyInput, () => {});
  });

  // CLOSE DROPDOWN
  document.addEventListener("click", (e) => {
    if (!e.target.closest(".custom-select")) {
      provinceList.style.display = "none";
      cityList.style.display = "none";
      brgyList.style.display = "none";
    }
  });

  function saveAddress() {
  if (!addressCheck.checked) return;

  localStorage.setItem("addressInfo", JSON.stringify({
    province: provinceInput.value,
    city: cityInput.value,
    brgy: brgyInput.value,
    postal: postal.value,
    fullAddress: fullAddress.value
  }));
}
[provinceInput, cityInput, brgyInput, postal, fullAddress].forEach(i => {
  i.addEventListener("input", saveAddress);
});

addressCheck.addEventListener("change", () => {
  addressCheck.checked
    ? saveAddress()
    : localStorage.removeItem("addressInfo");
});

/* =============================
        FINAL CHECKOUT
============================= */
const payBtn = document.getElementById("payBtn");

payBtn.addEventListener("click", () => {

  if (!document.querySelector("input[name='payment']:checked")) {
    alert("Select payment method");
    return;
  }

const data = {
  firstName: firstName.value,
  lastName: lastName.value,
  email: email.value,
  phone: phone.value,

  province: provinceInput.value,
  city: cityInput.value,
  barangay: brgyInput.value,
  postal_code: postal.value,
  full_address: fullAddress.value,

  payment_method: document.querySelector("input[name='payment']:checked")?.parentElement.textContent.trim(),
  delivery_method: document.querySelector("input[name='delivery']:checked")?.parentElement.textContent.trim(),

  total: subtotal + baseDelivery + expressExtra,

  items: checkoutItems.map(item => ({
    product_name: item.name,
    price: Number(item.price),
    qty: Number(item.qty),
    size: item.size || '',
    color: item.color || '',
    image: item.image || ''
  }))
};

console.log("FINAL DATA:", data);

fetch("place-order.php", {
  method: "POST",
  headers: {
    "Content-Type": "application/json"
  },
  body: JSON.stringify(data)
})

.then(res => res.text()) 

.then(res => {
  console.log("SERVER RESPONSE:", res);
  alert("Order sent!");

  // GET CURRENT CART
  let cart = JSON.parse(localStorage.getItem("cart")) || [];

  // REMOVE CHECKED ITEMS 
  cart = cart.filter(cartItem => {
    return !checkoutItems.some(item =>
      item.name === cartItem.name &&
      item.size === cartItem.size &&
      item.color === cartItem.color
    );
  });

  // SAVE UPDATED CART
  localStorage.setItem("cart", JSON.stringify(cart));

  // CLEAR CHECKOUT ITEMS
  localStorage.removeItem("checkoutItems");

  // REDIRECT TO CART PAGE
  window.location.href = "cart.php";
})

.catch(err => {
  console.error("FETCH ERROR:", err);
});

});


document.addEventListener("DOMContentLoaded", () => {

  const item = JSON.parse(localStorage.getItem("checkoutItem"));

  console.log("Loaded item:", item); // DEBUG

  if (!item) {
    alert("No item selected.");
    return;
  }

  // Example: display data (you can adjust this)
  document.getElementById("productName").innerText = item.name;
  document.getElementById("productPrice").innerText = "₱ " + item.price;
  document.getElementById("productQty").innerText = item.qty;
  document.getElementById("productImage").src = "../../uploads/" + item.image;

});

});