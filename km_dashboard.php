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

// Tools shown on the dashboard: one card each
$tools = [
    [
        'title' => 'Calculator',
        'href'  => 'test_scripts/calculator.php',
        'icon'  => 'bi-calculator',
        'tech'  => 'JavaScript',
        'text'  => 'Button and keyboard calculator that runs in the browser, with a small expression parser instead of eval().',
    ],
    [
        'title' => 'Basic Calculator',
        'href'  => 'test_scripts/basic_calculator.php',
        'icon'  => 'bi-plus-slash-minus',
        'tech'  => 'PHP',
        'text'  => 'Form-based calculator processed on the server, with input validation and escaped output.',
    ],
];

$pageTitle = 'Dashboard';
require __DIR__ . '/partials/header.php';
?>
        <div class="container py-5">
            <header class="dashboard-hero mb-5">
                <h1 class="display-6 fw-semibold mb-2">Welcome<?= $alias !== '' ? ', ' . e($alias) : '' ?></h1>
                <p class="lead text-body-secondary mb-0">Pick a tool below to get started.</p>
            </header>

            <div class="row g-4">
                <?php foreach ($tools as $tool): ?>
                    <div class="col-md-6">
                        <a href="<?= e($tool['href']) ?>" class="card tool-card h-100 text-decoration-none">
                            <div class="card-body d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <span class="tool-icon"><i class="bi <?= e($tool['icon']) ?>"></i></span>
                                    <span class="badge rounded-pill tech-badge"><?= e($tool['tech']) ?></span>
                                </div>
                                <h2 class="h5 card-title"><?= e($tool['title']) ?></h2>
                                <p class="card-text text-body-secondary"><?= e($tool['text']) ?></p>
                                <span class="mt-auto fw-semibold text-primary">Open <i class="bi bi-arrow-right"></i></span>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
<?php require __DIR__ . '/partials/footer.php'; ?>
