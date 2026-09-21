<?php
session_start();

// Unprotected route: can only be accessed if the user is logged out/unauthenticated
if (isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

// Set session and redirect when authenticated
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    if (!empty($email)) {
        $_SESSION['email'] = $email;
        header("Location: index.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>
    <h1>Login (login.php)</h1>

    <div id="error-message" style="color: red;"></div>

    <form id="loginForm" method="POST" action="login.php">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <br><br>
        <button type="submit">Login</button>
    </form>

    <p>Don't have an account? <a href="register.php">Register here</a></p>

    <script>
        const loginForm = document.getElementById('loginForm');
        const emailInput = document.getElementById('email');
        const errorMessage = document.getElementById('error-message');

        loginForm.addEventListener('submit', function(e) {
            const enteredEmail = emailInput.value.trim();
            const savedEmail = localStorage.getItem('email');

            // Reset error message
            errorMessage.textContent = '';

            // Client-side validation: check if email is provided
            if (!enteredEmail) {
                e.preventDefault();
                errorMessage.textContent = 'Please enter your email.';
                return;
            }

            // Client-side validation: check if any email has been registered
            if (!savedEmail) {
                e.preventDefault();
                errorMessage.textContent = 'No registered email found. Please register an account first.';
                return;
            }

            // Client-side validation: check if entered email matches saved email
            if (enteredEmail.toLowerCase() !== savedEmail.toLowerCase()) {
                e.preventDefault();
                errorMessage.textContent = 'Incorrect email. The email you entered does not match our records.';
                return;
            }

            // If email is correct, allow form submission to create PHP session and redirect
        });
    </script>
</body>
</html>
