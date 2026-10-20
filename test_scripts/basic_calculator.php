<?php
require_once __DIR__ . '/../km_func.php';

send_security_headers();

$num1     = '';
$num2     = '';
$operator = '+';
$result   = null;

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
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple Calculator</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            color: white;
            margin: 50px;
            background-color: rgb(45, 45, 45);
        }

        form {
            display: inline-block;
            text-align: left;
        }

        input, select {
            font-size: 16px;
            padding: 10px;
        }

        button {
            font-size: 16px;
            padding: 10px 20px;
            cursor: pointer;
        }

        #result {
            margin-top: 20px;
            font-size: 24px;
        }

        .back-link {
            display: inline-block;
            margin-bottom: 30px;
            color: #9ecbff;
        }
    </style>
</head>
<body>
    <a href="../km_dashboard.php" class="back-link">&larr; Back to dashboard</a>
    <br>

    <form method="post" action="">
        <!-- Num1 user input -->
        <label for="num1">Enter First Number : </label>
        <input type="number" step="any" id="num1" name="num1" value="<?= e($num1) ?>" required>
        <!-- Operator user input -->
        <label for="oper">Select Operator : </label>
        <select id="oper" name="oper" required>
            <?php foreach (['+', '-', '*', '/'] as $op): ?>
                <option value="<?= e($op) ?>"<?= $op === $operator ? ' selected' : '' ?>><?= e($op) ?></option>
            <?php endforeach; ?>
        </select>
        <!-- Num2 user input -->
        <label for="num2">Enter Second Number : </label>
        <input type="number" step="any" id="num2" name="num2" value="<?= e($num2) ?>" required>
        <!-- Submit button -->
        <button type="submit" name="calculate">Calculate</button>
    </form>

    <?php if ($result !== null): ?>
        <div id="result">Result: <?= e((string) $result) ?></div>
    <?php endif; ?>
</body>
</html>