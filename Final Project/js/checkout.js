document.addEventListener("DOMContentLoaded", function () {
    loadCheckoutTotal();

    // Handle form submission
    document.getElementById("checkoutForm").addEventListener("submit", function (event) {
        event.preventDefault();

        let formData = new FormData(this);

        // Send discount info if applied
        let discount = document.getElementById("checkoutTotal").dataset.discount || "0";
        formData.append("discount", discount);

        fetch("cart/checkout.php", {
            method: "POST",
            body: formData
        })
        .then(response => {
            if (response.redirected) {
                window.location.href = response.url; // Redirect to order success page
            } else {
                alert("Order processing failed. Please try again.");
            }
        })
        .catch(error => console.error("Error:", error));
    });

});

document.getElementById('paymentForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);

    fetch('process_payment.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
        window.location.href = 'order_success.php';
        } else {
        alert('Payment failed: ' + data.message);
        }
    });
});

// Load Total Price from Cart
function loadCheckoutTotal() {
    fetch("cart/get_checkout_total.php") // Fetch total price separately
    .then(response => response.json())
    .then(data => {
        document.getElementById("checkoutTotal").textContent = `$${parseFloat(data.totalPrice).toFixed(2)}`;
        document.getElementById("checkoutTotal").dataset.original = data.totalPrice; // Store original price
    })
    .catch(error => console.error("Error:", error));
}
