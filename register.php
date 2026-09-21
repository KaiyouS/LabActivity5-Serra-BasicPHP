<?php
declare(strict_types=1);

// Configure safe session cookie parameters before session_start()
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

// Unprotected route: can only be accessed if the user is logged out/unauthenticated
if (!empty($_SESSION['email'])) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
</head>
<body>
    <h1>Register (register.php)</h1>

    <div id="error-message" style="color: red;"></div>

    <form id="registerForm">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <br><br>
        <button type="submit">Register</button>
    </form>

    <p>Already have an account? <a href="login.php">Login here</a></p>

    <script>
        const registerForm = document.getElementById('registerForm');
        const emailInput = document.getElementById('email');
        const errorMessage = document.getElementById('error-message');

        registerForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const email = emailInput.value.trim();

            if (!email) {
                errorMessage.textContent = 'Please enter an email.';
                return;
            }

            // Save email via localStorage for validation later
            localStorage.setItem('email', email);

            alert('Registration successful! Your email has been saved.');
            window.location.href = 'login.php';
        });
    </script>
</body>
</html>
