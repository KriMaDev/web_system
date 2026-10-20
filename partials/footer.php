<?php
/**
 * Shared page footer: closes <main>, renders the footer and loads scripts.
 *
 * Optional variables, set before including this file:
 *   $assetBase    string    Relative path from the page to the project root ('' or '../')
 *   $pageScripts  string[]  Extra scripts for this page, relative to the project root
 */
if (!function_exists('e')) {            // opened directly instead of included by a page
    http_response_code(404);
    exit();
}

$assetBase   = $assetBase ?? '';
$pageScripts = $pageScripts ?? [];
?>
    </main>
    <footer class="app-footer border-top py-3 mt-5">
        <div class="container d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2 small text-body-secondary">
            <span>&copy; 2023&ndash;<?= date('Y') ?> KriMaDev &middot; MIT License</span>
            <a href="https://github.com/KriMaDev/web_system" class="link-secondary text-decoration-none"
               target="_blank" rel="noopener noreferrer">
                <i class="bi bi-github me-1"></i>Source on GitHub
            </a>
        </div>
    </footer>
    <?php foreach ($pageScripts as $script): ?>
        <script src="<?= e($assetBase . $script) ?>"></script>
    <?php endforeach; ?>
</body>
</html>
