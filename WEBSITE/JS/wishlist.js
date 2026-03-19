document.addEventListener("DOMContentLoaded", function () {
    
    let wishlist = JSON.parse(localStorage.getItem("wishlist")) || [];
    const container = document.getElementById("wishlist-container");

    if (!container) return;

    if (wishlist.length === 0) {
        container.innerHTML = "<p class='empty-msg'>No items in wishlist.</p>";
        return;
    }

    function formatPrice(min, max) {
        min = parseFloat(min);
        max = parseFloat(max);

        if (min === max) {
            return `₱${min.toFixed(2)}`;
        } else {
            return `₱${min.toFixed(2)} - ₱${max.toFixed(2)}`;
        }
    }

    fetch("fetch-wishlist.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({ ids: wishlist })
    })
    .then(response => response.json())
    .then(products => {

        if (products.length === 0) {
            container.innerHTML = "<p class='empty-msg'>No items in wishlist.</p>";
            return;
        }

        container.innerHTML = "";

        products.forEach(product => {

            container.innerHTML += `
                <div class="cart-item clickable" data-id="${product.id}">

                    <div class="checkbox-area">
                        <input type="checkbox" value="${product.id}">
                    </div>
                    
                    <div class="product-image">
                        <img src="/CAPSTONE/uploads/${product.image}">
                    </div>

                    <div class="product-name">
                        ${product.brand} - ${product.name}
                    </div>

                    <div class="price">
                        ${formatPrice(product.min_price, product.max_price)}
                    </div>

                    <div class="delete-btn">
                        <button class="remove-item" data-id="${product.id}">
                            Delete
                        </button>
                    </div>

                </div>
            `;
        });

        // ✅ SINGLE DELETE
        document.querySelectorAll(".remove-item").forEach(button => {
            button.addEventListener("click", function (e) {

                e.stopPropagation();

                const productId = parseInt(this.dataset.id);

                // REMOVE FROM WISHLIST
                wishlist = wishlist.filter(id => parseInt(id) !== productId);
                localStorage.setItem("wishlist", JSON.stringify(wishlist));

                // 🔥 REMOVE FROM CART
                let cart = JSON.parse(localStorage.getItem("cart")) || [];
                cart = cart.filter(item => parseInt(item.id) !== productId);
                localStorage.setItem("cart", JSON.stringify(cart));

                // REMOVE UI
                this.closest(".cart-item").remove();

                if (wishlist.length === 0) {
                    container.innerHTML = "<p class='empty-msg'>No items in wishlist.</p>";
                }
            });
        });

        // ✅ DELETE SELECTED
        const deleteSelectedBtn = document.getElementById("deleteSelected");

        if (deleteSelectedBtn) {
            deleteSelectedBtn.addEventListener("click", function () {

                const checkedItems = document.querySelectorAll(
                    ".checkbox-area input:checked"
                );

                if (checkedItems.length === 0) {
                    alert("Please select items to delete.");
                    return;
                }

                if (!confirm("Are you sure you want to delete selected items?")) {
                    return;
                }

                let cart = JSON.parse(localStorage.getItem("cart")) || [];

                checkedItems.forEach(checkbox => {

                    const productId = parseInt(checkbox.value);

                    // REMOVE FROM WISHLIST
                    wishlist = wishlist.filter(id => parseInt(id) !== productId);

                    // 🔥 REMOVE FROM CART
                    cart = cart.filter(item => parseInt(item.id) !== productId);

                    checkbox.closest(".cart-item").remove();
                });

                localStorage.setItem("wishlist", JSON.stringify(wishlist));
                localStorage.setItem("cart", JSON.stringify(cart));

                if (wishlist.length === 0) {
                    container.innerHTML = "<p class='empty-msg'>No items in wishlist.</p>";
                }
            });
        }

        // ✅ CLICK PRODUCT (GO TO VIEW PAGE)
        document.querySelectorAll(".cart-item").forEach(item => {
            item.addEventListener("click", function (e) {

                if (
                    e.target.closest(".remove-item") ||
                    e.target.closest(".checkbox-area")
                ) {
                    return;
                }

                const productId = this.dataset.id;
                window.location.href = "product-view.php?id=" + productId;
            });
        });

    })
    .catch(error => {
        console.error("Fetch error:", error);
    });

});