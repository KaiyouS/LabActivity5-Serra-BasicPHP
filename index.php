<?php
session_start();

// Protected route: can only be accessed if the user is logged in/authenticated
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Handle logout
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Home - Protected</title>
</head>
<body>
    <h1>Protected Page (index.php)</h1>
    <p>Welcome, <?php echo htmlspecialchars($_SESSION['email']); ?>!</p>
    <p>You are logged in/authenticated.</p>

    <form method="POST" action="index.php">
        <button type="submit" name="logout">Logout</button>
    </form>
</body>
</html>
