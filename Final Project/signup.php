<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up | McNeese Bookstore</title>
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/register.css">
</head>

<body>

    <div class="signup-container">
        <h2><i><b>Create Your Account</b></i></h2>

        <div class="error" id="errorMessage"></div>
        <div class="success" id="successMessage"></div>

        <form id="signupForm">
            <input type="text" id="firstName" name="firstName" placeholder="First Name" required>
            <input type="text" id="lastName" name="lastName" placeholder="Last Name" required>
            <input type="email" id="email" name="email" placeholder="Email" required>
            <input type="password" id="password" name="password" placeholder="Password" required>
            <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm Password" required>

            <select id="role" name="role" required>
                <option value="student">Student</option>
                <option value="admin">Admin</option>
            </select>

            <button type="submit">Sign Up</button>
        </form>

        <p>Already have an account? <a href="login.php">Login here</a></p>
        <p>Go to <a href="index.php">Home Page</a></p>
        <p>Admin Login? <a href="admin_login.php">Click Here</a></p>

        <footer>&copy; 2025 McNeese State University Bookstore</footer>
    </div>

    <script>
        // JavaScript to handle signup submission
        document.getElementById("signupForm").addEventListener("submit", function (event) {
            event.preventDefault(); // Prevent page reload

            let formData = new FormData(this); // Collect form data

            fetch("signupprocess.php", { // Send data to the backend
                method: "POST",
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                const errorMessage = document.getElementById("errorMessage");
                const successMessage = document.getElementById("successMessage");

                errorMessage.textContent = "";
                successMessage.textContent = "";

                if (data.error) {
                    errorMessage.textContent = data.error;
                } else {
                    successMessage.textContent = data.success;
                    setTimeout(() => {
                        window.location.href = data.redirect; // Redirect to login page
                    }, 2000);
                }
            })
            .catch(error => console.error("Error:", error));
        });
    </script>

</body>
</html>
