<?php
require_once __DIR__ . '/../km_func.php';

send_security_headers();
start_secure_session();

// [key sent to the calculator, button label, Bootstrap button style]
$keys = [
    ['(', '(', 'calc'], [')', ')', 'calc'], ['C', 'C', 'outline-danger'],    ['back', '⌫', 'outline-warning'],
    ['7', '7', 'calc'], ['8', '8', 'calc'], ['9', '9', 'calc'], ['/', '÷', 'primary'],
    ['4', '4', 'calc'], ['5', '5', 'calc'], ['6', '6', 'calc'], ['*', '×', 'primary'],
    ['1', '1', 'calc'], ['2', '2', 'calc'], ['3', '3', 'calc'], ['-', '−', 'primary'],
    ['0', '0', 'calc'], ['.', '.', 'calc'], ['=', '=', 'success'],           ['+', '+', 'primary'],
];

$pageTitle   = 'Calculator';
$assetBase   = '../';
$pageScripts = ['assets/js/calculator.js'];
require __DIR__ . '/../partials/header.php';
?>
        <div class="container py-5">
            <a href="../km_dashboard.php" class="back-link"><i class="bi bi-arrow-left me-1"></i>Back to dashboard</a>

            <header class="tool-header text-center mb-4">
                <h1 class="h3"><i class="bi bi-calculator text-primary me-2"></i>Calculator</h1>
                <p class="text-body-secondary mb-0">
                    Click the buttons or type: <kbd>Enter</kbd> calculates, <kbd>Backspace</kbd> deletes, <kbd>Esc</kbd> clears.
                </p>
            </header>

            <div class="card calc-card mx-auto">
                <div class="card-body">
                    <input type="text" id="calc-display" class="form-control form-control-lg calc-display mb-3"
                           readonly aria-label="Calculator display">
                    <div class="calc-grid">
                        <?php foreach ($keys as [$key, $label, $style]): ?>
                            <button type="button" class="btn btn-lg btn-<?= e($style) ?>" data-key="<?= e($key) ?>"><?= e($label) ?></button>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
<?php require __DIR__ . '/../partials/footer.php'; ?>
