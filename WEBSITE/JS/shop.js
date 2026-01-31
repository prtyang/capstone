function addToWishlist(heart) {
  const card = heart.closest(".product-card");
  const productId = card.dataset.id;

  let wishlist = JSON.parse(localStorage.getItem("wishlist")) || [];

  if (heart.classList.contains("active")) {
    // ❌ REMOVE from wishlist
    heart.classList.remove("active");
    heart.innerHTML = "♡";

    wishlist = wishlist.filter(id => id !== productId);
  } else {
    // ✅ ADD to wishlist
    heart.classList.add("active");
    heart.innerHTML = "♥";

    if (!wishlist.includes(productId)) {
      wishlist.push(productId);
    }
  }

  localStorage.setItem("wishlist", JSON.stringify(wishlist));
}

document.addEventListener("DOMContentLoaded", () => {
  const wishlist = JSON.parse(localStorage.getItem("wishlist")) || [];

  document.querySelectorAll(".product-card").forEach(card => {
    const id = card.dataset.id;
    const heart = card.querySelector(".heart");

    if (wishlist.includes(id)) {
      heart.classList.add("active");
      heart.innerHTML = "♥";
    }
  });
});

 //SEARCH
document.querySelector('.search-box').addEventListener('input', function () {
  const value = this.value;
  window.location.href = value
    ? '?search=' + encodeURIComponent(value)
    : 'shop.php';
});

window.onload = function () {
  const search = document.querySelector('.search-box');
  if (search) {
    search.focus();
    search.setSelectionRange(search.value.length, search.value.length);
  }
};
