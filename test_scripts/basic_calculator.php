<?php
require_once __DIR__ . '/../km_func.php';

send_security_headers();
start_secure_session();

$operators = ['+' => 'Add (+)', '-' => 'Subtract (−)', '*' => 'Multiply (×)', '/' => 'Divide (÷)'];

$num1     = '';
$num2     = '';
$operator = '+';
$result   = null;
$isError  = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['calculate'])) {
    $num1     = post_string('num1');
    $num2     = post_string('num2');
    $operator = post_string('oper');

    if (!is_numeric($num1) || !is_numeric($num2)) {
        $result = 'Please enter two valid numbers.';
    } else {
        $a = (float) $num1;
        $b = (float) $num2;

        // Perform calculation
        switch ($operator) {
            case '+':
                $result = $a + $b;
                break;
            case '-':
                $result = $a - $b;
                break;
            case '*':
                $result = $a * $b;
                break;
            case '/':
                $result = ($b != 0) ? $a / $b : 'Cannot divide by zero!';
                break;
            default:
                $result = 'Invalid operator';
                break;
        }
    }
    $isError = is_string($result);
}

$pageTitle = 'Basic Calculator';
$assetBase = '../';
require __DIR__ . '/../partials/header.php';
?>
        <div class="container py-5">
            <a href="../km_dashboard.php" class="back-link"><i class="bi bi-arrow-left me-1"></i>Back to dashboard</a>

            <header class="tool-header text-center mb-4">
                <h1 class="h3"><i class="bi bi-plus-slash-minus text-primary me-2"></i>Basic Calculator</h1>
                <p class="text-body-secondary mb-0">The calculation runs on the server in PHP.</p>
            </header>

            <div class="card tool-panel mx-auto">
                <div class="card-body">
                    <form method="post" action="" class="row g-3 align-items-end">
                        <div class="col-sm-4">
                            <label for="num1" class="form-label">First number</label>
                            <input type="number" step="any" class="form-control" id="num1" name="num1" value="<?= e($num1) ?>" required>
                        </div>
                        <div class="col-sm-4">
                            <label for="oper" class="form-label">Operator</label>
                            <select class="form-select" id="oper" name="oper" required>
                                <?php foreach ($operators as $op => $label): ?>
                                    <option value="<?= e($op) ?>"<?= $op === $operator ? ' selected' : '' ?>><?= e($label) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <label for="num2" class="form-label">Second number</label>
                            <input type="number" step="any" class="form-control" id="num2" name="num2" value="<?= e($num2) ?>" required>
                        </div>
                        <div class="col-12">
                            <button type="submit" name="calculate" class="btn btn-primary w-100">
                                <i class="bi bi-calculator me-1"></i>Calculate
                            </button>
                        </div>
                    </form>

                    <?php if ($result !== null): ?>
                        <div id="result" class="alert <?= $isError ? 'alert-warning' : 'alert-success' ?> mt-4 mb-0 fs-5 text-center" role="status">
                            Result: <strong><?= e((string) $result) ?></strong>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
<?php require __DIR__ . '/../partials/footer.php'; ?>
