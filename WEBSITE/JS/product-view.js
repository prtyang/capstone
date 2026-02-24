let currentIndex = 0;
let images = [];

document.addEventListener("DOMContentLoaded", () => {

  console.log("JS LOADED ✅");

  /* IMAGE SLIDER */
  const mainImage = document.querySelector(".main-image");
  const defaultImage = mainImage.src;

  const thumbnailImages = document.querySelectorAll(".thumbnails img");
  const prevBtn = document.querySelector(".image-arrow.prev");
  const nextBtn = document.querySelector(".image-arrow.next");

  thumbnailImages.forEach((img, index) => {
    images.push(img.src);
    img.addEventListener("click", () => {
      currentIndex = index;
      updateMainImage();
    });
  });

  prevBtn.addEventListener("click", () => {
    currentIndex = (currentIndex - 1 + images.length) % images.length;
    updateMainImage();
  });

  nextBtn.addEventListener("click", () => {
    currentIndex = (currentIndex + 1) % images.length;
    updateMainImage();
  });

  function updateMainImage() {
    mainImage.src = images[currentIndex];
  }

  /* VARIATIONS */
  let selectedColor = null;
  let selectedSize = null;

  const priceEl = document.querySelector(".price");
  const qtyInput = document.getElementById("qtyInput");
  const availableQtyEl = document.getElementById("availableQty");

  function updateSizeAvailability() {
    document.querySelectorAll(".size-btn").forEach(btn => {
      const size = btn.dataset.size;

      if (!selectedColor) {
        btn.classList.remove("disabled");
        return;
      }

      const match = variations.find(v =>
        v.color.toLowerCase() === selectedColor.toLowerCase() &&
        v.size === size
      );

      if (!match || match.qty <= 0) {
        btn.classList.add("disabled");

        if (btn.classList.contains("active")) {
          btn.classList.remove("active");
          selectedSize = null;
        }
      } else {
        btn.classList.remove("disabled");
      }
    });
  }

  /* EDIT MODE LOAD */
  if (typeof cartEditIndex !== "undefined" && cartEditIndex >= 0) {
    const cart = JSON.parse(localStorage.getItem("cart")) || [];
    const item = cart[cartEditIndex];

    if (item) {
      selectedColor = item.color;
      selectedSize = item.size;

      setTimeout(() => {

        document.querySelectorAll(".color").forEach(c => {
          if (c.dataset.color === selectedColor) {
            c.classList.add("active");
          }
        });

        document.querySelectorAll(".size-btn").forEach(b => {
          if (b.dataset.size === selectedSize) {
            b.classList.add("active");
          }
        });

        qtyInput.value = item.qty;

        updateSizeAvailability();
        updatePrice();

      }, 100);
    }
  }

  /* COLOR CLICK */
  document.querySelectorAll(".color").forEach(color => {
    color.addEventListener("click", () => {

      if (color.classList.contains("active")) {
        color.classList.remove("active");
        selectedColor = null;
        mainImage.src = defaultImage;
        availableQtyEl.innerText = "";
        return;
      }

      document.querySelectorAll(".color").forEach(c => c.classList.remove("active"));
      color.classList.add("active");

      selectedColor = color.dataset.color;

      updateSizeAvailability();
      updatePrice();
    });
  });

  /* SIZE CLICK */
  document.querySelectorAll(".size-btn").forEach(btn => {
    btn.addEventListener("click", () => {

      if (btn.classList.contains("disabled")) return;

      document.querySelectorAll(".size-btn").forEach(b => b.classList.remove("active"));
      btn.classList.add("active");

      selectedSize = btn.dataset.size;

      updatePrice();
    });
  });

  /* PRICE UPDATE */
  function updatePrice() {
    if (!selectedColor || !selectedSize) return;

    const match = variations.find(v =>
      v.color.toLowerCase() === selectedColor.toLowerCase() &&
      v.size === selectedSize
    );

    if (!match) return;

    priceEl.innerHTML = "₱ " + parseFloat(match.price).toFixed(2);

    qtyInput.max = match.qty;
    availableQtyEl.innerText = match.qty + " available";

    if (qtyInput.value > match.qty) {
      qtyInput.value = match.qty;
    }

    if (match.image) {
      mainImage.src = "../../uploads/" + match.image;
    }
  }

  /* QTY LIMIT */
  qtyInput.addEventListener("input", () => {
    if (qtyInput.value > qtyInput.max) qtyInput.value = qtyInput.max;
    if (qtyInput.value < 1) qtyInput.value = 1;
  });

  /* ADD TO CART */
  const addToCartBtn = document.getElementById("addToCartBtn");

  addToCartBtn.addEventListener("click", () => {

    if (!selectedColor) return alert("Please select color.");
    if (!selectedSize) return alert("Please select size.");

    const match = variations.find(v =>
      v.color.toLowerCase() === selectedColor.toLowerCase() &&
      v.size === selectedSize
    );

    if (!match || match.qty <= 0) {
      return alert("This variation is out of stock.");
    }

    const qty = parseInt(qtyInput.value);

    const cartItem = {
      id: productData.id,
      brand: productData.brand,
      name: productData.name,
      image: match.image || productData.image,
      color: selectedColor,
      size: selectedSize,
      price: parseFloat(match.price),
      qty: qty,
      stock: parseInt(match.qty)
    };

    let cart = JSON.parse(localStorage.getItem("cart")) || [];

    /* EDIT MODE */
    if (typeof cartEditIndex !== "undefined" && cartEditIndex >= 0) {
      cart[cartEditIndex] = cartItem;
    } else {
      const existingItem = cart.find(item =>
        item.id === cartItem.id &&
        item.color === cartItem.color &&
        item.size === cartItem.size
      );

      if (existingItem) {
        existingItem.qty += qty;
      } else {
        cart.push(cartItem);
      }
    }

    localStorage.setItem("cart", JSON.stringify(cart));

    alert("Added to cart!");
  });

  /* ✅ CHECKOUT BUTTON */
  const checkoutBtn = document.getElementById("checkoutBtn");

  checkoutBtn.addEventListener("click", () => {

    console.log("CHECKOUT CLICKED ✅");

    if (!selectedColor) return alert("Please select color.");
    if (!selectedSize) return alert("Please select size.");

    const match = variations.find(v =>
      v.color.toLowerCase() === selectedColor.toLowerCase() &&
      v.size === selectedSize
    );

    if (!match) return alert("Variation not found.");
    if (match.qty <= 0) return alert("Out of stock.");

    const qty = parseInt(qtyInput.value);

    const checkoutItem = {
      id: productData.id,
      brand: productData.brand,
      name: productData.name,
      image: match.image || productData.image,
      color: selectedColor,
      size: selectedSize,
      price: parseFloat(match.price),
      qty: qty
    };

    localStorage.setItem("checkoutItem", JSON.stringify(checkoutItem));

    window.location.href = "checkout.php";
  });

  updateSizeAvailability();

});
