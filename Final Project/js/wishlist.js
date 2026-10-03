function attachAddToWishlistEvent() {

    document.querySelectorAll(".add-to-wishlist-btn").forEach(button => {
        button.addEventListener("click", function () {
            let productId = this.getAttribute("data-id");

            fetch("/bookstore/product/add_to_wishlist.php", {
                method: "POST",
                body: JSON.stringify({ product_id: productId }),
                headers: { "Content-Type": "application/json" }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert("Added to wishlist!");
                } else {
                    alert(data.error || "Error");
                }
            })
            .catch(error => console.error("Error adding to wishlist:", error));
        });
    });
}

document.addEventListener("DOMContentLoaded", function () {
    attachRemoveFromWishlist();
});

function attachRemoveFromWishlist() {
    document.querySelectorAll(".remove-from-wishlist-btn").forEach(button => {
        button.addEventListener("click", function () {
            const productId = this.getAttribute("data-id");

            fetch("/bookstore/product/remove_wishlist.php", {
                method: "POST",
                body: JSON.stringify({ product_id: productId }),
                headers: { "Content-Type": "application/json" }
            })
            .then(res => res.json())
            .then(data => {
                alert("Removed from wishlist!");
                location.reload();
            })
            .catch(err => console.error("Error removing from wishlist:", err));
        });
    });
}
