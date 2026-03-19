document.addEventListener("DOMContentLoaded", function () {

    let cart = JSON.parse(localStorage.getItem("cart")) || [];
    const container = document.getElementById("cartItems");
    const grandTotalEl = document.getElementById("grandTotal");
    const itemCountEl = document.getElementById("itemCount");

    if (!container) return;

    // 🔥 CLEAN INVALID ITEMS (IMPORTANT)
    cart = cart.filter(item => item && item.id);
    localStorage.setItem("cart", JSON.stringify(cart));

    if (cart.length === 0) {
        container.innerHTML = "<p>Your cart is empty.</p>";
        grandTotalEl.innerText = "0.00";
        return;
    }

    renderCart();
    updateGrandTotal();

    function renderCart() {
        container.innerHTML = "";

        cart.forEach((item, index) => {

            if (!item) return; // safety

            const itemTotal = item.price * item.qty;

            container.innerHTML += `
                <div class="cart-item" data-index="${index}">
                    
                    <input 
                        type="checkbox" 
                        class="select-item" 
                        data-item='${JSON.stringify(item)}'
                    >

                    <div class="product-image">
                        <img src="/CAPSTONE/uploads/${item.image}">
                    </div>

                    <div class="product-info">
                        <strong>${item.brand}</strong><br>
                        <small class="variation-text">Color: ${item.color}</small><br>
                        <small class="variation-text">Size: ${item.size}</small>
                    </div>

                    <div class="price">₱${item.price.toFixed(2)}</div>

                    <div class="qty">
                        <input type="number" 
                        value="${item.qty}" 
                        min="1" 
                        class="qty-input">
                    </div>

                    <div class="item-total">
                        ₱${itemTotal.toFixed(2)}
                    </div>

                    <div class="delete">
                        <button class="delete-item">Delete</button>
                    </div>

                </div>
            `;
        });

        // RESET CHECKBOXES
        setTimeout(() => {
            document.querySelectorAll(".select-item").forEach(cb => cb.checked = false);
        }, 0);

        const checkoutBtn = document.getElementById("checkoutBtn");

        checkoutBtn.disabled = cart.length === 0;
        checkoutBtn.style.opacity = cart.length === 0 ? "0.5" : "1";

        attachEvents();

        document.querySelectorAll(".select-item").forEach(checkbox => {
            checkbox.addEventListener("change", updateGrandTotal);
        });

        // CLICK ITEM → VIEW PRODUCT
        document.querySelectorAll(".cart-item").forEach(item => {
            item.addEventListener("click", function(e) {

                if (e.target.closest("button") || e.target.closest("input")) return;

                const index = this.dataset.index;
                const selectedItem = cart[index];

                if (!selectedItem) return;

                window.location.href = "product-view.php?id=" + selectedItem.id + "&cartIndex=" + index;

            });
        });
    }

    function attachEvents() {

        // ✅ DELETE ITEM
        document.querySelectorAll(".delete-item").forEach(btn => {
            btn.addEventListener("click", function () {

                const index = this.closest(".cart-item").dataset.index;

                if (cart[index]) {
                    cart.splice(index, 1);
                }

                localStorage.setItem("cart", JSON.stringify(cart));

                renderCart();
                updateGrandTotal();
            });
        });

        // ✅ UPDATE QTY
        document.querySelectorAll(".qty-input").forEach(input => {

            input.addEventListener("change", async function () {

                const index = this.closest(".cart-item").dataset.index;
                let newQty = parseInt(this.value);

                const item = cart[index];
                if (!item) return;

                const res = await fetch("check-stock.php", {
                    method:"POST",
                    headers:{ "Content-Type":"application/json" },
                    body: JSON.stringify({
                        id: item.id,
                        color: item.color,
                        size: item.size
                    })
                });

                const data = await res.json();
                const maxStock = data.stock;

                if (newQty > maxStock) {
                    alert("Only " + maxStock + " left in stock.");
                    newQty = maxStock;
                }

                if (newQty <= 0) {
                    if (confirm("Remove this item from cart?")) {
                        cart.splice(index,1);
                    } else {
                        newQty = 1;
                    }
                }

                if (cart[index]) {
                    cart[index].qty = newQty;
                }

                localStorage.setItem("cart", JSON.stringify(cart));

                renderCart();
                updateGrandTotal();

            });
        });
    }

    // ✅ TOTAL CALCULATION
    function updateGrandTotal() {

        let total = 0;
        let itemCount = 0;

        document.querySelectorAll(".cart-item").forEach(itemEl => {

            const checkbox = itemEl.querySelector(".select-item");

            if (checkbox && checkbox.checked) {

                const index = parseInt(itemEl.dataset.index);
                const item = cart[index];

                if (item) {
                    total += Number(item.price) * Number(item.qty);
                    itemCount += Number(item.qty);
                }
            }
        });

        grandTotalEl.innerText = total.toFixed(2);
        itemCountEl.innerText = itemCount;

        const checkoutBtn = document.getElementById("checkoutBtn");

        checkoutBtn.disabled = total <= 0;
        checkoutBtn.style.opacity = total <= 0 ? "0.5" : "1";
    }

    // ✅ CHECKOUT
    const checkoutBtn = document.getElementById("checkoutBtn");

    checkoutBtn.addEventListener("click", function (e) {

        let selectedItems = [];

        document.querySelectorAll(".select-item:checked").forEach(cb => {

            const item = JSON.parse(cb.dataset.item);

            if (item) {
                selectedItems.push(item);
            }
        });

        if (selectedItems.length === 0) {
            e.preventDefault();
            alert("Please select at least one item.");
            return;
        }

        localStorage.removeItem("checkoutItems");
        localStorage.setItem("checkoutItems", JSON.stringify(selectedItems));

        console.log("FINAL SELECTED:", selectedItems);
    });

});