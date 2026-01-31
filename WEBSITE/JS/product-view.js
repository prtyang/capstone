let currentIndex = 0;
let images = [];

document.addEventListener("DOMContentLoaded", () => {

  /* ================= IMAGE SLIDER ================= */
  const mainImage = document.querySelector(".main-image");
  const defaultImage = mainImage.src; // ✅ STEP 3 IS HERE

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

  /* ================= VARIATIONS ================= */
  let selectedColor = null;
  let selectedSize = null;

  const priceEl = document.querySelector(".price");
  const qtyInput = document.getElementById("qtyInput");
  const availableQtyEl = document.getElementById("availableQty");

  /* COLOR CLICK */
  document.querySelectorAll(".color").forEach(color => {
    color.addEventListener("click", () => {

      // UNSELECT COLOR
      if (color.classList.contains("active")) {
        color.classList.remove("active");
        selectedColor = null;

        mainImage.src = defaultImage; // ✅ BACK TO ORIGINAL IMAGE
        availableQtyEl.innerText = "";
        return;
      }

      document.querySelectorAll(".color").forEach(c => c.classList.remove("active"));
      color.classList.add("active");

      selectedColor = color.title;
      updatePrice();
    });
  });

  /* SIZE CLICK */
  document.querySelectorAll(".size-btn").forEach(btn => {
    btn.addEventListener("click", () => {
      document.querySelectorAll(".size-btn").forEach(b => b.classList.remove("active"));
      btn.classList.add("active");

      selectedSize = btn.dataset.size;
      updatePrice();
    });
  });

  /* PRICE + QTY + IMAGE UPDATE */
  function updatePrice() {
    if (!selectedColor || !selectedSize) return;

    const match = variations.find(v =>
      v.color === selectedColor && v.size === selectedSize
    );

    if (!match) return;

    // PRICE
    priceEl.innerHTML = "₱ " + parseFloat(match.price).toFixed(2);

    // QTY
    qtyInput.max = match.qty;
    availableQtyEl.innerText = match.qty + " available";

    if (qtyInput.value > match.qty) {
      qtyInput.value = match.qty;
    }

    // VARIATION IMAGE
    if (match.image) {
      mainImage.src = "../../uploads/" + match.image;
    }
  }

  /* BLOCK OVER-QTY */
  qtyInput.addEventListener("input", () => {
    if (qtyInput.value > qtyInput.max) qtyInput.value = qtyInput.max;
    if (qtyInput.value < 1) qtyInput.value = 1;
  });

});
