<?php
session_start();

// Check if the user is not logged in, redirect to login page
if (!isset($_SESSION["user_id"])) {
    header("Location: km_login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Perform any additional logout actions if needed
    // ...

    // Destroy the session and redirect to the login page
    session_destroy();
    header("Location: km_login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>
    <h2>Welcome to the Dashboard</h2>
    <!-- Your dashboard content here -->

    <!-- Logout Form -->
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <input type="submit" value="Logout">
    </form>
</body>
</html>
