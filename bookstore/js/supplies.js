document.addEventListener("DOMContentLoaded", function () {
    loadSupplies();
    updateCartCount();

    document.getElementById("searchForm").addEventListener("submit", function (event) {
        event.preventDefault();
        loadSupplies();
    });
});

function loadSupplies() {
    const searchQuery = document.getElementById("searchInput").value;
    const url = `/bookstore/product/get_supplies.php?search=${encodeURIComponent(searchQuery)}`;

    fetch(url)
        .then(response => response.json())
        .then(products => {
            const section = document.getElementById("suppliesList");
            section.innerHTML = "";

            if (products.length === 0) {
                section.innerHTML = "<p style='text-align: center;'>No supplies found.</p>";
                return;
            }

            products.forEach(product => {
                section.innerHTML += `
                    <div class="product-card">
                        <img src="/bookstore/${product.ImagePath}" 
                             alt="${product.ProductName}" 
                             onerror="this.onerror=null; this.src='/bookstore/images/no-image.jpg';">
                        <h3>${product.ProductName}</h3>
                        <p>$${parseFloat(product.Price).toFixed(2)}</p>
                        <button class="add-to-cart-btn" data-id="${product.ProductID}">Add to Cart</button>
                    </div>
                `;
            });

            attachAddToCartEvent();
        })
        .catch(error => console.error("Error fetching supplies:", error));
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
            .catch(error => console.error("Error adding to cart:", error));
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
        })
        .catch(error => console.error("Error updating cart count:", error));
}
