<?php
require_once("km_conn.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $alias = $_POST["alias"];

    // Validate user input to avoid SQL injection
    $stmt = $pdo->prepare("INSERT INTO km_users (user, pass, alias) VALUES (?, ?, ?)");
    
    // Hash the password before storing it
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Execute the prepared statement
    $stmt->execute([$username, $hashedPassword, $alias]);

    // Redirect to login page after successful registration
    header("Location: km_login.php");
    exit();
}

$pdo = null;
?>

<!-- The HTML registration form -->
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
</body>
</html>
