<?php
require_once("km_conn.php");

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validate user input to avoid SQL injection and XSS
    $username = htmlspecialchars($username, ENT_QUOTES, 'UTF-8');

    $stmt = $conn->prepare("SELECT id, user, pass FROM km_users WHERE user = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user["pass"])) {
        // Valid credentials
        $_SESSION["user_id"] = $user["id"];
        header("Location: km_dashboard.php");
        exit();
    } else {
        // Invalid credentials
        echo "Invalid username or password";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title>
</head>
<body>
    <h2>Login</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        Username: <input type="text" name="username"><br>
        Password: <input type="password" name="password"><br>
        <input type="submit" value="Login">
    </form>

    <!-- Add a button to go to the registration page -->
    <p>Don't have an account? <a href="km_register.php">Register</a></p>
</body>
</html>
