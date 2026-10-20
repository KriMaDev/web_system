<?php
require_once __DIR__ . '/../km_func.php';

send_security_headers();
start_secure_session();

$characterSets = [
    'lower'   => 'Lowercase (a-z)',
    'upper'   => 'Uppercase (A-Z)',
    'digits'  => 'Numbers (0-9)',
    'symbols' => 'Symbols (!@#$…)',
];

$pageTitle   = 'Password Generator';
$assetBase   = '../';
$pageScripts = ['assets/js/password-generator.js'];
require __DIR__ . '/../partials/header.php';
?>
        <div class="container py-5">
            <a href="../km_dashboard.php" class="back-link"><i class="bi bi-arrow-left me-1"></i>Back to dashboard</a>

            <header class="tool-header text-center mb-4">
                <h1 class="h3"><i class="bi bi-key text-primary me-2"></i>Password Generator</h1>
                <p class="text-body-secondary mb-0">
                    Generated locally in your browser with <code>crypto.getRandomValues()</code>. Nothing is sent to the server.
                </p>
            </header>

            <div class="card tool-panel mx-auto">
                <div class="card-body">
                    <label for="password-output" class="form-label">Your password</label>
                    <div class="input-group input-group-lg mb-2">
                        <input type="text" class="form-control font-monospace" id="password-output"
                               readonly spellcheck="false" autocomplete="off" aria-describedby="pw-strength-label">
                        <button type="button" class="btn btn-outline-secondary" id="pw-regenerate" title="Generate a new password" aria-label="Generate a new password">
                            <i class="bi bi-arrow-repeat"></i>
                        </button>
                        <button type="button" class="btn btn-primary" data-copy-target="#password-output">
                            <i class="bi bi-clipboard me-1"></i>Copy
                        </button>
                    </div>

                    <div class="progress mb-1" role="progressbar" aria-label="Password strength" aria-valuemin="0" aria-valuemax="100" style="height: 6px;">
                        <div class="progress-bar" id="pw-strength-bar"></div>
                    </div>
                    <div class="small text-body-secondary mb-4" id="pw-strength-label">&nbsp;</div>

                    <form id="password-options">
                        <label for="pw-length" class="form-label d-flex justify-content-between">
                            <span>Length</span><strong id="pw-length-value">16</strong>
                        </label>
                        <input type="range" class="form-range mb-3" id="pw-length" min="8" max="64" value="16">

                        <div class="row row-cols-1 row-cols-sm-2 g-2 mb-3">
                            <?php foreach ($characterSets as $name => $label): ?>
                                <div class="col">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" id="pw-<?= e($name) ?>" checked>
                                        <label class="form-check-label" for="pw-<?= e($name) ?>"><?= e($label) ?></label>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="pw-ambiguous">
                            <label class="form-check-label" for="pw-ambiguous">
                                Avoid look-alike characters (<code>I l 1 O o 0</code>)
                            </label>
                        </div>
                    </form>
                </div>
            </div>
        </div>
<?php require __DIR__ . '/../partials/footer.php'; ?>
