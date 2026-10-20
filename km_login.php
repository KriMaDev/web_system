<?php
require_once __DIR__ . '/km_conn.php';

send_security_headers();
start_secure_session();

// Already logged in? Go straight to the dashboard
if (isset($_SESSION['user_id'])) {
    header('Location: km_dashboard.php');
    exit();
}

$error    = '';
$username = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = post_string('username');
    $password = post_string('password', false);

    if (!csrf_verify()) {
        $error = 'Your session has expired. Please try again.';
    } elseif ($username === '' || $password === '') {
        $error = 'Please enter your username and password.';
    } else {
        // The raw username is safe here: the prepared statement handles SQL escaping
        $stmt = $pdo->prepare('SELECT id, pass, alias FROM km_users WHERE user = ?');
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        // Hash a dummy value for unknown users so response times don't reveal valid usernames
        $hash = $user ? $user['pass'] : password_hash('dummy-password', PASSWORD_DEFAULT);

        if (password_verify($password, $hash) && $user) {
            // Upgrade the hash if PHP's default algorithm/cost has changed
            if (password_needs_rehash($user['pass'], PASSWORD_DEFAULT)) {
                $pdo->prepare('UPDATE km_users SET pass = ? WHERE id = ?')
                    ->execute([password_hash($password, PASSWORD_DEFAULT), $user['id']]);
            }

            session_regenerate_id(true);        // new session id: prevents session fixation
            unset($_SESSION['csrf_token']);     // fresh CSRF token for the new session
            $_SESSION['user_id'] = (int) $user['id'];
            $_SESSION['alias']   = $user['alias'];

            header('Location: km_dashboard.php');
            exit();
        }

        $error = 'Invalid username or password. Please try again.';
    }
}

$notice = isset($_GET['registered']) ? 'Account created. You can now log in.' : '';

$pageTitle   = 'Log in';
$pageScripts = ['assets/js/eye.js'];
require __DIR__ . '/partials/header.php';
?>
        <div class="container py-5">
            <div class="card auth-card mx-auto">
                <div class="card-body">
                    <div class="eye-container" aria-hidden="true">
                        <div class="eye">
                            <div class="pupil"></div>
                        </div>
                    </div>
                    <h1 class="h3 card-title mb-1">Welcome back</h1>
                    <p class="text-body-secondary mb-4">Log in to open your tools dashboard.</p>
                    <?php if ($notice !== ''): ?>
                        <div class="alert alert-success" role="alert"><?= e($notice) ?></div>
                    <?php endif; ?>
                    <?php if ($error !== ''): ?>
                        <div class="alert alert-danger" role="alert"><?= e($error) ?></div>
                    <?php endif; ?>
                    <form method="post" action="" class="text-start">
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username"
                                   value="<?= e($username) ?>" autocomplete="username" required autofocus>
                        </div>
                        <div class="mb-4">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password"
                                   autocomplete="current-password" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-box-arrow-in-right me-1"></i>Log in
                        </button>
                    </form>
                    <p class="mt-4 mb-0 small">Don't have an account? <a href="km_register.php">Register</a></p>
                </div>
            </div>
        </div>
<?php require __DIR__ . '/partials/footer.php'; ?>
