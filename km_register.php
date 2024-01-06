<?php
require_once("km_conn.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $alias = $_POST["alias"];

    // Validate user input to avoid SQL injection and XSS
    $username = htmlspecialchars($username, ENT_QUOTES, 'UTF-8');
    $alias = htmlspecialchars($alias, ENT_QUOTES, 'UTF-8');

    $stmt = $conn->prepare("INSERT INTO km_users (user, pass, alias) VALUES (?, ?, ?)");

    // Hash the password before storing it
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Execute the prepared statement
    $stmt->execute([$username, $hashedPassword, $alias]);

    // Redirect to login page after successful registration
    header("Location: km_login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registration</title>
</head>
<body>
    <h2>Registration</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        Username: <input type="text" name="username" required><br>
        Password: <input type="password" name="password" required><br>
        Alias: <input type="text" name="alias" required><br>
        <input type="submit" value="Register">
    </form>

    <!-- Add a button to go to the login page -->
    <p>Already have an account? <a href="km_login.php">Login</a></p>
</body>
</html>
