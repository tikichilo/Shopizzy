document.querySelector("#login-form").addEventListener("submit", function (e) {
    e.preventDefault(); // Prevent full-page reload

    let email = document.querySelector("#email").value;
    let password = document.querySelector("#password").value;
    let errorMessage = document.querySelector("#error-message");

    fetch("login.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `email=${encodeURIComponent(email)}&password=${encodeURIComponent(password)}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === "success") {
            sessionStorage.setItem("user_id", data.user_id); // ✅ Store user_id for session tracking
            window.location.href = data.redirect; // ✅ Redirect on success
        } else {
            errorMessage.textContent = data.message;
            errorMessage.style.display = "block"; // Show error
        }
    })
    .catch(error => {
        errorMessage.textContent = "An error occurred. Please try again.";
        errorMessage.style.display = "block";
    });
});
