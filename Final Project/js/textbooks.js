document.addEventListener("DOMContentLoaded", function () {
    loadTextbooks();
    updateCartCount();

    document.getElementById("searchForm").addEventListener("submit", function (event) {
        event.preventDefault();
        loadTextbooks();
    });
});

function loadTextbooks() {
    const searchQuery = document.getElementById("searchInput").value;
    const url = `/bookstore/product/get_textbooks.php?search=${encodeURIComponent(searchQuery)}`;

    fetch(url)
        .then(response => response.json())
        .then(products => {
            const container = document.getElementById("textbookList");
            container.innerHTML = "";

            if (products.length === 0) {
                container.innerHTML = "<p style='text-align: center;'>No textbooks found.</p>";
                return;
            }

            products.forEach(product => {
                container.innerHTML += `
                    <div class="product-card">
                        <img src="/bookstore/${product.ImagePath}" alt="${product.ProductName}" 
                             onerror="this.onerror=null; this.src='/bookstore/images/no-image.jpg';">
                        <h3>${product.ProductName}</h3>
                        <p>$${parseFloat(product.Price).toFixed(2)}</p>
                        <button class="add-to-wishlist-btn" data-id="${product.ProductID}">Add to Wishlist</button>
                        <button class="add-to-cart-btn" data-id="${product.ProductID}">Add to Cart</button>
                    </div>
                `;
            });

            attachAddToCartEvent();
            attachAddToWishlistEvent();
        })
        .catch(error => console.error("Error loading textbooks:", error));
}

function attachAddToCartEvent() {
    document.querySelectorAll(".add-to-cart-btn").forEach(button => {
        button.addEventListener("click", function () {
            const productId = this.getAttribute("data-id");

            fetch("/bookstore/cart/cart_process.php", {
                method: "POST",
                body: JSON.stringify({ product_id: productId, action: "add" }),
                headers: { "Content-Type": "application/json" }
            })
            .then(response => response.json())
            .then(data => {
                updateCartCount();
            })
            .catch(error => console.error("Cart error:", error));
        });
    });
}

function updateCartCount() {
    fetch("/bookstore/cart/cart_process.php?action=count")
        .then(response => response.json())
        .then(data => {
            const cartCount = document.getElementById("cart-count");
            if (cartCount) {
                cartCount.textContent = data.count || 0;
            }
        });
}
