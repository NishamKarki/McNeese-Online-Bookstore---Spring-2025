document.addEventListener("DOMContentLoaded", function () {
    loadCart();

    // Attach event listener to clear cart
    document.getElementById("clearCart").addEventListener("click", function () {
        fetch("../cart/cart_process.php", {
            method: "POST",
            body: JSON.stringify({ clearCart: true }),
            headers: { "Content-Type": "application/json" }
        })
        .then(response => response.json())
        .then(data => {
            loadCart();
        })
        .catch(error => console.error("Error:", error));
    });

    // Attach event listener to proceed to checkout
    document.getElementById("checkoutButton").addEventListener("click", function () {
        window.location.href = "../cart/checkout.php";
    });
});

// Load Cart Items
function loadCart() {
    fetch("../cart/get_cart.php")
    .then(response => response.json())
    .then(data => {
        let cartItems = document.getElementById("cartItems");
        cartItems.innerHTML = "";
        let total = 0;

        if (data.length === 0) {
            cartItems.innerHTML = "<div class='empty-cart'>Your cart is empty.</div>";
        } else {
            data.forEach(item => {
                total += item.Price * item.quantity;
                cartItems.innerHTML += `
                    <div class="cart-item">
                        <img src="/bookstore/${item.ImagePath}" alt="${item.ProductName}" 
                            onerror="this.onerror=null; this.src='/bookstore/images/no-image.jpg';">
                        <h3>${item.ProductName}</h3>
                        <p>$${parseFloat(item.Price).toFixed(2)} x ${item.quantity}</p>
                        <button class="remove-item" data-id="${item.ProductID}">Remove</button>
                    </div>
                `;
            });
        }

        document.getElementById("cartTotal").textContent = `Total: $${total.toFixed(2)}`;
        attachRemoveItemEvents();
    })
    .catch(error => console.error("Error:", error));
}

// Attach Remove Item Event
function attachRemoveItemEvents() {
    document.querySelectorAll(".remove-item").forEach(button => {
        button.addEventListener("click", function () {
            let productId = this.getAttribute("data-id");

            fetch("../cart/cart_process.php", {
                method: "POST",
                body: JSON.stringify({ removeProduct: productId }),
                headers: { "Content-Type": "application/json" }
            })
            .then(response => response.json())
            .then(data => {
                loadCart();
            })
            .catch(error => console.error("Error:", error));
        });
    });
}
