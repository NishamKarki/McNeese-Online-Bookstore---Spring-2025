<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | McNeese Bookstore</title>
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/login.css">
</head>

<body>

    <div class="login-container">
        <h2><i><b>McNeese Bookstore Login</b></i></h2>

        <div class="error" id="errorMessage"></div>

        <form id="loginForm">
            <input type="email" id="email" name="email" placeholder="Email" required>
            <input type="password" id="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>

        <p class="forgot-password"><a href="forgot-password.php">Forgot Password?</a></p>
        <p>Don't have an account? <a href="signup.php">Sign Up</a></p>
        <p>Go to <a href="index.php">Home Page</a></p>
        
        <footer>&copy; 2025 McNeese State University Bookstore</footer>
    </div>

    <script>
        // JavaScript to handle login
        document.getElementById("loginForm").addEventListener("submit", function (event) {
            event.preventDefault(); // Prevent page reload

            let formData = new FormData(this); // Collect form data

            fetch("loginprocess.php", { // Send data to backend
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
                    window.location.href = data.redirect; // Redirect to correct page
                }
            })
            .catch(error => console.error("Error:", error));
        });
    </script>
</body>
</html>
