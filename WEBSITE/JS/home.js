document.addEventListener("DOMContentLoaded", function () {

    let wishlist = JSON.parse(localStorage.getItem("wishlist")) || [];

    document.querySelectorAll(".heart").forEach(heart => {

        let productId = parseInt(heart.dataset.id);

        // On page load check if already in wishlist
        if (wishlist.includes(productId)) {
            heart.classList.add("active");
            heart.innerHTML = "♥";
        } else {
            heart.innerHTML = "♡";
        }

        heart.addEventListener("click", function (e) {

            e.stopPropagation();
            e.preventDefault();

            if (wishlist.includes(productId)) {
                // REMOVE
                wishlist = wishlist.filter(id => id !== productId);
                heart.classList.remove("active");
                heart.innerHTML = "♡";
            } else {
                // ADD
                wishlist.push(productId);
                heart.classList.add("active");
                heart.innerHTML = "♥";
            }

            localStorage.setItem("wishlist", JSON.stringify(wishlist));
        });

    });

});
