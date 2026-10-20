<?php
require_once __DIR__ . '/km_func.php';

send_security_headers();
start_secure_session();

// Check if the user is not logged in, redirect to login page
if (!isset($_SESSION['user_id'])) {
    header('Location: km_login.php');
    exit();
}

// Logout (POST only, CSRF-protected)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify()) {
        header('Location: km_dashboard.php');
        exit();
    }

    // Clear the session data, expire the cookie and destroy the session
    $_SESSION = [];
    $cookie   = session_get_cookie_params();
    setcookie(session_name(), '', [
        'expires'  => time() - 3600,
        'path'     => $cookie['path'],
        'domain'   => $cookie['domain'],
        'secure'   => $cookie['secure'],
        'httponly' => $cookie['httponly'],
        'samesite' => $cookie['samesite'],
    ]);
    session_destroy();

    header('Location: km_login.php');
    exit();
}

$alias = $_SESSION['alias'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <nav class="navbar navbar-dark bg-dark shadow-sm">
        <div class="container">
            <span class="navbar-brand fw-semibold">Web System</span>
            <!-- Logout Form -->
            <form method="post" action="" class="m-0">
                <?= csrf_field() ?>
                <button type="submit" class="btn btn-outline-light btn-sm">Logout</button>
            </form>
        </div>
    </nav>

    <main class="container py-5">
        <header class="mb-4 text-light">
            <h1 class="h2">Welcome<?= $alias !== '' ? ', ' . e($alias) : '' ?></h1>
            <p class="text-white-50 mb-0">Pick a tool below to get started.</p>
        </header>

        <div class="row g-4">
            <div class="col-md-6">
                <div class="card tool-card h-100 shadow">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h2 class="h5 card-title mb-0">Calculator</h2>
                            <span class="badge text-bg-warning">JavaScript</span>
                        </div>
                        <p class="card-text text-body-secondary">
                            Button-based calculator that runs in the browser. Handles parentheses and
                            operator precedence with a small expression parser instead of <code>eval()</code>.
                        </p>
                        <a href="test_scripts/calculator.html" class="btn btn-primary mt-auto align-self-start">Open Calculator</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card tool-card h-100 shadow">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h2 class="h5 card-title mb-0">Basic Calculator</h2>
                            <span class="badge text-bg-info">PHP</span>
                        </div>
                        <p class="card-text text-body-secondary">
                            Form-based calculator processed on the server, with input validation
                            and escaped output.
                        </p>
                        <a href="test_scripts/basic_calculator.php" class="btn btn-primary mt-auto align-self-start">Open Basic Calculator</a>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
