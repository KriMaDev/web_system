<?php
require_once("km_conn.php");

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validate user input to avoid SQL injection and XSS
    $username = htmlspecialchars($username, ENT_QUOTES, 'UTF-8');

    $stmt = $pdo->prepare("SELECT id, user, pass FROM km_users WHERE user = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user["pass"])) {
        // Valid credentials
        $_SESSION["user_id"] = $user["id"];
        header("Location: km_dashboard.php");
        exit();
    } else {
        // Invalid credentials
        echo '<script>alert("Invalid username or password. Please try again.");</script>';
    }
}
?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Your additional styles -->
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="eye-container">
                            <div class="eye">
                                <div class="pupil"></div>
                            </div>
                        </div>
                        <h2 class="card-title">Login</h2>
                        <form method="post" action="#" onsubmit="return validateForm()">
                            <div class="form-group">
                                <label for="username">Username:</label>
                                <input type="text" class="form-control" name="username">
                            </div>
                            <div class="form-group">
                                <label for="password">Password:</label>
                                <input type="password" class="form-control" name="password">
                            </div>
                            <button type="submit" class="btn btn-primary">Login</button>
                        </form>
                        <p class="mt-3">Don't have an account? <a href="km_register.php">Register</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap JS and other JavaScript files can be included here -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Your additional JavaScript files can be included here -->
    <script>
        //===================================
        //                       Eye controls
        document.addEventListener("DOMContentLoaded", function () {
            const eye = document.querySelector(".eye");
            document.addEventListener("mousemove", function (e) {
                const { clientX: mouseX, clientY: mouseY } = e;
                const eyeRect = eye.getBoundingClientRect();
                const eyeX = eyeRect.left + eyeRect.width / 2;
                const eyeY = eyeRect.top + eyeRect.height / 2;
                const deltaX = mouseX - eyeX;
                const deltaY = mouseY - eyeY;
                const angle = Math.atan2(deltaY, deltaX);
                const distance = Math.min(eyeRect.width, eyeRect.height) / 4;
                const eyePupil = eye.querySelector(".pupil");
                eyePupil.style.transform = `translate(-50%, -50%) translate(${Math.cos(angle) * distance}px, ${Math.sin(angle) * distance}px)`;
            });
        });
        
        //===================================
        //                Empty fields alerts
        function validateForm() {
            var username = document.forms[0]["username"].value;
            var password = document.forms[0]["password"].value;

            // Your validation logic goes here
            if (username === "" || password === "") {
                alert("Please enter a valid credentials.");
                return false;
            }
            // Additional validation logic can be added as needed

            return true; // Submit the form
        }
    </script>
</body>
</html>


