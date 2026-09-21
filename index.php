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

// Handle logout (thorough session destruction as covered in Topic 5)
if (isset($_POST['logout']) || (isset($_GET['action']) && $_GET['action'] === 'logout')) {
    // 1. Wipe session data for this request
    $_SESSION = [];

    // 2. Expire session cookie
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
    }

    // 3. Remove server-side session storage
    session_destroy();

    // 4. Redirect to login and exit immediately
    header('Location: login.php');
    exit;
}

// Protected route: can only be accessed if the user is logged in/authenticated
if (empty($_SESSION['email'])) {
    header('Location: login.php');
    exit;
}

$email = (string) $_SESSION['email'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Home - Protected</title>
</head>
<body>
    <h1>Protected Page (index.php)</h1>
    <p>Welcome, <?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?>!</p>
    <p>You are logged in/authenticated.</p>

    <form method="POST" action="index.php">
        <button type="submit" name="logout">Logout</button>
    </form>
</body>
</html>
