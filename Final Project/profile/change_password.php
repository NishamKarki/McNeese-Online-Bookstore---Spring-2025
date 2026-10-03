<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password | McNeese Bookstore</title>
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/change_password.css">  <!-- Reusing the login.css for styling -->
</head>

<body>
    <div class="login-container">
        <h2><i><b>Change Password</b></i></h2>

        <div class="error" id="errorMessage"></div>

        <form id="changePasswordForm" method="POST" action="update_password.php">
            <input type="password" id="oldPassword" name="oldPassword" placeholder="Old Password" required>
            <input type="password" id="newPassword" name="newPassword" placeholder="New Password" required>
            <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm New Password" required>
            <button type="submit">Change Password</button>
        </form>

        <footer>&copy; 2025 McNeese State University Bookstore</footer>
    </div>

    <script>
        // JavaScript to handle the change password functionality
        document.getElementById("changePasswordForm").addEventListener("submit", function (event) {
            event.preventDefault(); // Prevent page reload

            let formData = new FormData(this); // Collect form data

            fetch("update_password.php", { // Send data to backend
                method: "POST",
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                const errorMessage = document.getElementById("errorMessage");
                errorMessage.textContent = "";

                if (data.error) {
                    errorMessage.textContent = data.error;
                } else {
                    alert("Password changed successfully!");
                    window.location.href = "profile.php"; // Redirect to the profile page after password change
                }
            })
            .catch(error => console.error("Error:", error));
        });
    </script>
</body>
</html>
