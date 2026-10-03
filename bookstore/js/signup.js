document.getElementById("signupForm").addEventListener("submit", function (event) {
    event.preventDefault();

    let formData = new FormData(this); // Automatically gathers all form inputs

    fetch("signup.php", {
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
