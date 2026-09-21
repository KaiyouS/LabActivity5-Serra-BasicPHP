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

// Typed email validation function per Week 5 guidelines
function isValidEmail(string $email): bool {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

// Server-side authentication handler when valid form is submitted
if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    $email = trim((string) ($_POST['email'] ?? ''));

    if ($email !== '' && isValidEmail($email)) {
        $_SESSION['email'] = $email;
        header('Location: index.php');
        exit;
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

            // Client-side validation: check if any email has been saved
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

            // Client-side validation passed; form POSTs to PHP to set session
        });
    </script>
</body>
</html>
