<?php
require_once __DIR__ . '/km_conn.php';

send_security_headers();
start_secure_session();

// Already logged in? Go straight to the dashboard
if (isset($_SESSION['user_id'])) {
    header('Location: km_dashboard.php');
    exit();
}

const USERNAME_PATTERN    = '/^[A-Za-z0-9_.-]{3,32}$/';
const PASSWORD_MIN_LENGTH = 8;
const PASSWORD_MAX_BYTES  = 72;     // bcrypt ignores anything after 72 bytes

$errors   = [];
$username = '';
$alias    = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = post_string('username');
    $password = post_string('password', false);
    $alias    = post_string('alias');

    // Server-side validation (the browser checks are only a convenience)
    if (!csrf_verify()) {
        $errors[] = 'Your session has expired. Please try again.';
    }
    if (!preg_match(USERNAME_PATTERN, $username)) {
        $errors[] = 'Username must be 3-32 characters: letters, numbers, dot, dash or underscore.';
    }
    if (strlen($password) < PASSWORD_MIN_LENGTH) {
        $errors[] = 'Password must be at least ' . PASSWORD_MIN_LENGTH . ' characters long.';
    } elseif (strlen($password) > PASSWORD_MAX_BYTES) {
        $errors[] = 'Password must be at most ' . PASSWORD_MAX_BYTES . ' bytes long.';
    }
    if (!preg_match('/^[^\p{C}]{1,50}$/u', $alias)) {
        $errors[] = 'Alias must be 1-50 characters long.';
    }

    if (!$errors) {
        // Raw values are stored: prepared statements prevent SQL injection,
        // and every value is escaped with e() when it is printed.
        $stmt = $pdo->prepare('INSERT INTO km_users (user, pass, alias) VALUES (?, ?, ?)');

        try {
            $stmt->execute([$username, password_hash($password, PASSWORD_DEFAULT), $alias]);

            header('Location: km_login.php?registered=1');
            exit();
        } catch (PDOException $e) {
            // 1062 = duplicate entry on the UNIQUE username index
            if (($e->errorInfo[1] ?? null) !== 1062) {
                throw $e;
            }
            $errors[] = 'That username is already taken. Please choose another one.';
        }
    }
}

$pageTitle   = 'Register';
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
                    <h1 class="h3 card-title mb-1">Create an account</h1>
                    <p class="text-body-secondary mb-4">It only takes a few seconds.</p>
                    <?php if ($errors): ?>
                        <div class="alert alert-danger text-start" role="alert">
                            <ul class="mb-0 ps-3">
                                <?php foreach ($errors as $error): ?>
                                    <li><?= e($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    <form method="post" action="" class="text-start">
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username"
                                   value="<?= e($username) ?>" autocomplete="username" aria-describedby="usernameHelp"
                                   minlength="3" maxlength="32" pattern="[A-Za-z0-9_.\-]+" required autofocus>
                            <div id="usernameHelp" class="form-text">3-32 characters: letters, numbers, dot, dash or underscore.</div>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" aria-describedby="passwordHelp"
                                   autocomplete="new-password" minlength="8" maxlength="72" required>
                            <div id="passwordHelp" class="form-text">At least 8 characters.</div>
                        </div>
                        <div class="mb-4">
                            <label for="alias" class="form-label">Display name</label>
                            <input type="text" class="form-control" id="alias" name="alias"
                                   value="<?= e($alias) ?>" maxlength="50" aria-describedby="aliasHelp" required>
                            <div id="aliasHelp" class="form-text">Shown on your dashboard.</div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-person-plus me-1"></i>Register
                        </button>
                    </form>
                    <p class="mt-4 mb-0 small">Already have an account? <a href="km_login.php">Log in</a></p>
                </div>
            </div>
        </div>
<?php require __DIR__ . '/partials/footer.php'; ?>
