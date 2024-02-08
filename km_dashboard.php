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
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css"> 
    <title>Dashboard</title>
</head>
<body>
    <div class="wholeDash">
        <h2>Welcome to the Dashboard</h2>
        <div id ="headerDash">
            <pre>
                Here you can find and use all of the available tools built in the system.
                Remember that this page is still under development!
                Thats why they might be a bit crappy...
            </pre>
        </div>
        <div id="bodyDash">
            <div id="mainDash">
                <div id="mainHeadDash">
                    <a href="/test-rep/test_scripts/calculator.html">
                        <h3>Calculator</h3>
                    </a>
                    <a href="/test-rep/test_scripts/basic_calculator.php">
                        <h3>Basic Calculator</h3>
                    </a> 
                </div>
                <div id="mainFirstDash"></div>
                <div id="mainSecondDash"></div>
            </div>
            <div id="navDash"></div>
        </div>
        <div id="footerDash"></div>
       

        <!-- Logout Form -->
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input type="submit" value="Logout">
        </form>
    </div>
</body>
</html>
