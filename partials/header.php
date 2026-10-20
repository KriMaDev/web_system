<?php
/**
 * Shared page header: <head>, navbar and the opening <main> tag.
 *
 * Optional variables, set before including this file:
 *   $pageTitle  string  Title shown in the browser tab
 *   $assetBase  string  Relative path from the page to the project root ('' or '../')
 */
if (!function_exists('e')) {            // opened directly instead of included by a page
    http_response_code(404);
    exit();
}

$pageTitle  = $pageTitle ?? 'Web System';
$assetBase  = $assetBase ?? '';
$isLoggedIn = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?> · Web System</title>
    <!-- Applies the saved/system theme before the page is painted (no flash) -->
    <script src="<?= e($assetBase) ?>assets/js/theme.js"></script>
    <!-- Bootstrap CSS & Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
          integrity="sha384-XGjxtQfXaH2tnPFa9x+ruJTuLE3Aa6LhHSWRr1XeTyhezb4abCG4ccI5AkVDxqC+" crossorigin="anonymous">
    <link rel="stylesheet" href="<?= e($assetBase) ?>assets/css/styles.css">
</head>
<body class="d-flex flex-column min-vh-100">
    <nav class="navbar app-navbar border-bottom sticky-top">
        <div class="container">
            <a class="navbar-brand fw-semibold d-flex align-items-center" href="<?= e($assetBase) ?>index.php">
                <i class="bi bi-grid-1x2-fill text-primary me-2"></i>Web System
            </a>
            <div class="d-flex align-items-center gap-2">
                <button type="button" class="btn btn-sm btn-outline-secondary theme-toggle" data-theme-toggle
                        title="Toggle dark/light mode" aria-label="Toggle dark/light mode">
                    <i class="bi bi-moon-stars-fill theme-icon-to-dark"></i>
                    <i class="bi bi-sun-fill theme-icon-to-light"></i>
                </button>
                <?php if ($isLoggedIn): ?>
                    <span class="navbar-text small d-none d-sm-inline">
                        <i class="bi bi-person-circle me-1"></i><?= e($_SESSION['alias'] ?? '') ?>
                    </span>
                    <!-- Logout Form (handled by km_dashboard.php) -->
                    <form method="post" action="<?= e($assetBase) ?>km_dashboard.php" class="m-0">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-sm btn-outline-danger">
                            <i class="bi bi-box-arrow-right me-1"></i>Logout
                        </button>
                    </form>
                <?php else: ?>
                    <a href="<?= e($assetBase) ?>km_login.php" class="btn btn-sm btn-primary">Log in</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
    <main class="flex-grow-1">
