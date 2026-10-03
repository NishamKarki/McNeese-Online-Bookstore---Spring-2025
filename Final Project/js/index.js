document.addEventListener("DOMContentLoaded", function () {
    loadCategories();
    fetchProducts();

    // Handle Search & Filter Submission
    document.getElementById("searchForm").addEventListener("submit", function (event) {
        event.preventDefault();
        fetchProducts();
    });

    // Load Cart Count
    updateCartCount();
});

// Fetch & Populate Categories in Dropdown
function loadCategories() {
    fetch("product/get_categories.php")
        .then(response => response.json())
        .then(categories => {
            let categorySelect = document.getElementById("categorySelect");
            categorySelect.innerHTML = '<option value="">All Categories</option>';

            categories.forEach(category => {
                let option = document.createElement("option");
                option.value = category.CategoryID;
                option.textContent = category.CategoryName;
                categorySelect.appendChild(option);
            });
        })
        .catch(error => console.error("Error loading categories:", error));
}

function fetchProducts() {
    let searchQuery = document.getElementById("searchInput").value;
    let category = document.getElementById("categorySelect").value;
    let price = document.getElementById("priceSelect").value;

    let url = `product/get_products.php?search=${encodeURIComponent(searchQuery)}&category=${category}&price=${price}`;

    console.log("Fetching from:", url);

    fetch(url)
        .then(response => response.json())
        .then(products => {
            console.log("Fetched Products:", products);

            let productSection = document.getElementById("productList");
            if (!productSection) {
                console.error(" #productList not found in HTML!");
                return;
            }

            productSection.innerHTML = "";

            if (products.length === 0) {
                productSection.innerHTML = "<p>No products found.</p>";
                return;
            }

            products.forEach(product => {
                let productCard = document.createElement("div");
                productCard.classList.add("product-card");

                productCard.innerHTML = `
                    <img src="${product.ImagePath || 'images/no-image.jpg'}" 
                         alt="${product.ProductName}" 
                         onerror="this.onerror=null; this.src='images/no-image.jpg';">

                    <h3>${product.ProductName}</h3>
                    <p>$${parseFloat(product.Price).toFixed(2)}</p>
                    <button class="add-to-wishlist-btn" data-id="${product.ProductID}">Add to Wishlist</button>
                    <button class="add-to-cart-btn" data-id="${product.ProductID}">Add to Cart</button>
                `;

                productSection.appendChild(productCard);
            });

            attachAddToCartEvent();
            attachAddToWishlistEvent();
        })
        .catch(error => console.error("Error loading products:", error));
}

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

function updateCartCount() {
    fetch("/bookstore/cart/cart_process.php?action=count")
        .then(response => response.json())
        .then(data => {
            let cartCount = document.getElementById("cart-count");
            if (cartCount) {
                cartCount.textContent = data.count || 0;
            }
        })
        .catch(error => console.error("Error updating cart count:", error));
}
