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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Your additional styles -->
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card auth-card">
                    <div class="card-body">
                        <div class="eye-container">
                            <div class="eye">
                                <div class="pupil"></div>
                            </div>
                        </div>
                        <h2 class="card-title">Login</h2>
                        <?php if ($notice !== ''): ?>
                            <div class="alert alert-success" role="alert"><?= e($notice) ?></div>
                        <?php endif; ?>
                        <?php if ($error !== ''): ?>
                            <div class="alert alert-danger" role="alert"><?= e($error) ?></div>
                        <?php endif; ?>
                        <form method="post" action="">
                            <?= csrf_field() ?>
                            <div class="mb-3">
                                <label for="username" class="form-label">Username:</label>
                                <input type="text" class="form-control" id="username" name="username"
                                       value="<?= e($username) ?>" autocomplete="username" required autofocus>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password:</label>
                                <input type="password" class="form-control" id="password" name="password"
                                       autocomplete="current-password" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Login</button>
                        </form>
                        <p class="mt-3">Don't have an account? <a href="km_register.php">Register</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
    </script>
</body>
</html>


