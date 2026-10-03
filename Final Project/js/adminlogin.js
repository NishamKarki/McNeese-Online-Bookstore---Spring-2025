document.addEventListener("DOMContentLoaded", function () {
    document.getElementById('adminLoginForm').addEventListener('submit', function (event) {
        event.preventDefault();

        // Get user inputs
        const email = document.getElementById('adminEmail').value.trim();
        const password = document.getElementById('adminPassword').value.trim();
        const errorMessage = document.getElementById('adminErrorMessage');

        // Clear previous errors
        errorMessage.textContent = '';

        // Basic validation
        if (!email || !password) {
            errorMessage.textContent = 'Please fill in all fields.';
            return;
        }

        // Send Login Request to Backend
        fetch("../admin/admin_login.php", {
            method: "POST",
            body: JSON.stringify({ email, password }),
            headers: { "Content-Type": "application/json" }
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                errorMessage.textContent = data.error;
            } else {
                window.location.href = data.redirect; // Redirect to admin dashboard
            }
        })
        .catch(error => console.error("Error:", error));
    });
});
