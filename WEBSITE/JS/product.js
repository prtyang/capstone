document.addEventListener("click", function (e) {
  if (!e.target.classList.contains("heart")) return;

  const heart = e.target;
  const productCard = heart.closest(".product-card");
  const productId = productCard.dataset.id;

  heart.classList.toggle("active");
  heart.textContent = heart.classList.contains("active") ? "♥" : "♡";

  let wishlist = JSON.parse(localStorage.getItem("wishlist")) || [];

  if (wishlist.includes(productId)) {
    wishlist = wishlist.filter(id => id !== productId);
  } else {
    wishlist.push(productId);
  }

  localStorage.setItem("wishlist", JSON.stringify(wishlist));
});
