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

    // Apply promo code
    // document.getElementById("applyPromo").addEventListener("click", function () {
    //     applyPromo();
    // });
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

// Apply Promo Code Discount
// function applyPromo() {
//     let promoCode = document.getElementById("promoCode").value.trim().toUpperCase();
//     let discountApplied = false;

//     const validPromoCodes = {
//         "MCNEESE10": 0.10, // 10% discount
//         "STUDENT15": 0.15, // 15% discount
//         "WELCOME5": 0.05 // 5% discount
//     };

//     if (validPromoCodes[promoCode] && !discountApplied) {
//         let discount = validPromoCodes[promoCode];

//         fetch("cart/get_checkout_total.php") // Fetch total price separately
//         .then(response => response.json())
//         .then(data => {
//             let totalPrice = parseFloat(data.totalPrice);
//             totalPrice -= totalPrice * discount;

//             document.getElementById("checkoutTotal").textContent = `$${totalPrice.toFixed(2)}`;
//             document.getElementById("checkoutTotal").dataset.discount = discount; // Store discount
//             document.getElementById("promoMessage").textContent = `Promo applied: ${promoCode} - You saved ${discount * 100}%!`;
//             document.getElementById("promoMessage").style.color = "green";
//             discountApplied = true;
//         })
//         .catch(error => console.error("Error:", error));
//     } else {
//         document.getElementById("promoMessage").textContent = "Invalid promo code!";
//         document.getElementById("promoMessage").style.color = "red";
//     }
// }
