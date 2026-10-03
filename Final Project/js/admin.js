document.addEventListener("DOMContentLoaded", function () {
    loadProducts();
    loadUsers();

    // Add Product
    document.getElementById("addProductForm").addEventListener("submit", function (event) {
        event.preventDefault();

        let formData = new FormData(this);

        fetch("../admin/admin_process.php", {  // Ensure correct path
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            loadProducts(); // Refresh product list
        })
        .catch(error => console.error("Error:", error));
    });
});

// Load Products
function loadProducts() {
    fetch("../admin/get_product.php")
    .then(response => response.json())
    .then(products => {
        let productTable = document.getElementById("productTable");
        productTable.innerHTML = ""; // Clear table

        products.forEach(product => {
            productTable.innerHTML += `
                <tr>
                    <td><img src="../${product.ImagePath}" width="50" height="50" 
                    onerror="this.src='../images/no-image.jpg'"></td>
                    <td>${product.ProductName}</td>
                    <td>$${parseFloat(product.Price).toFixed(2)}</td>
                    <td>
                        <button class="delete-product" data-id="${product.ProductID}">Delete</button>
                    </td>
                </tr>
            `;
        });

        attachDeleteProductEvent();
    })
    .catch(error => console.error("Error loading products:", error));
}


// Load Users
function loadUsers() {
    fetch('get_user.php')
        .then(res => res.json())
        .then(users => {
            const userTable = document.getElementById('userTableBody');
            userTable.innerHTML = "";
        
            users.forEach(user => {
                const row = `
                    <tr>
                        <td>${user.FirstName}</td>
                        <td>${user.LastName}</td>
                        <td>${user.Email}</td>
                        <td>${user.CreatedAt}</td>
                        <td><button class="delete-user" data-id="${user.UserID}">Delete</button></td>
                    </tr>
                `;
                userTable.innerHTML += row;
            });
        
            attachDeleteUserEvent(); 
        })        

        .catch(err => console.error('Failed to load users:', err));
}


// Attach Delete Product Event
function attachDeleteProductEvent() {
    document.querySelectorAll(".delete-product").forEach(button => {
        button.addEventListener("click", function () {
            let productId = this.getAttribute("data-id");

            fetch("../admin/delete_product.php", {  // Use `delete_product.php`
                method: "POST",
                body: JSON.stringify({ productID: productId }),
                headers: { "Content-Type": "application/json" }
            })
            .then(response => response.json())
            .then(data => {
                loadProducts();
            })
            .catch(error => console.error("Error deleting product:", error));
        });
    });
}

// Attach Delete User Event
function attachDeleteUserEvent() {
    document.querySelectorAll(".delete-user").forEach(button => {
        button.addEventListener("click", function () {
            let userId = this.getAttribute("data-id");

            fetch("../admin/delete_user.php", {  // Use `delete_user.php`
                method: "POST",
                body: JSON.stringify({ userID: userId }),
                headers: { "Content-Type": "application/json" }
            })
            .then(response => response.json())
            .then(data => {
                loadUsers();
            })
            .catch(error => console.error("Error deleting user:", error));
        });
    });
}
