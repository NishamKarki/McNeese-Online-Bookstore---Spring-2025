document.addEventListener("DOMContentLoaded", function () {
    loadApparels(); 

    const searchForm = document.getElementById("searchForm");
    if (searchForm) {
        searchForm.addEventListener("submit", function (event) {
            event.preventDefault();
            loadApparels();
        });
    }

    updateCartCount();
});

// Load Apparel Products
function loadApparels() {
    const searchQuery = document.getElementById("searchInput").value;
    const url = `/bookstore/product/get_apparels.php?search=${encodeURIComponent(searchQuery)}`;

    fetch(url)
        .then(response => response.json())
        .then(products => {
            const apparelSection = document.getElementById("apparelList");
            apparelSection.innerHTML = ""; // Clear section

            if (products.length === 0) {
                apparelSection.innerHTML = "<p style='text-align: center;'>No apparel items available.</p>";
                return;
            }

            products.forEach(product => {
                apparelSection.innerHTML += `
                    <div class="product-card">
                        <img src="/bookstore/${product.ImagePath}" 
                             alt="${product.ProductName}" 
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
        .catch(error => console.error("Error fetching apparels:", error));
}

function globalSearch() {
    const searchInput = document.getElementById("searchInput");
    const searchQuery = searchInput ? searchInput.value : "";

    let url = `../product/get_products.php?search=${encodeURIComponent(searchQuery)}`;


    fetch(url)
        .then(response => response.json())
        .then(products => {
            let apparelSection = document.getElementById("apparelList");
            apparelSection.innerHTML = ""; // Clear previous products

            if (products.length === 0) {
                apparelSection.innerHTML = "<p style='text-align: center;'>No items found.</p>";
                return;
            }

            products.forEach(product => {
                apparelSection.innerHTML += `
                    <div class="product-card">
                        <img src="/bookstore/${product.ImagePath}" 
                             alt="${product.ProductName}" 
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
        .catch(error => console.error("Error fetching products:", error));
}

// Add to Cart Handler
function attachAddToCartEvent() {
    document.querySelectorAll(".add-to-cart-btn").forEach(button => {
        button.addEventListener("click", function () {
            let productId = this.getAttribute("data-id");

            fetch("/bookstore/cart/cart_process.php", {
                method: "POST",
                body: JSON.stringify({ product_id: productId, action: "add" }),
                headers: { "Content-Type": "application/json" }
            })
            .then(response => response.json())
            .then(data => {
                updateCartCount();
            });
            
        });
    });
}

// Update Cart Count in Header
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


