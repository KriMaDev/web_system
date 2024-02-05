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
    </style>
</head>
<body>

    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <!-- Num1 user input -->
        <label for="num1">Enter First Number : </label>
        <input type="number" name="num1" required>
        <!-- Operator user input -->
        <label for="oper">Select Operator : </label>
        <select name="oper" required>
            <option value="+">+</option>
            <option value="-">-</option>
            <option value="*">*</option>
            <option value="/">/</option>
        </select>
        <!-- Num2 user input -->
        <label for="num2">Enter Second Number : </label>
        <input type="number" name="num2" required>
        <!-- Submit button -->
        <button type="submit" name="calculate">Calculate</button>
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["calculate"])) {
        $num1     = $_POST["num1"];
        $num2     = $_POST["num2"];
        $operator = $_POST["oper"];

        // Perform calculation
        switch ($operator) {
            case "+":
                $result = $num1 + $num2;
                break;
            case "-":
                $result = $num1 - $num2;
                break;
            case "*":
                $result = $num1 * $num2;
                break;
            case "/":
                if ($num2 != 0) {
                    $result = $num1 / $num2;
                } else {
                    $result = "Cannot divide by zero!";
                }
                break;
            default:
                $result = "Invalid operator";
                break;
        }

        echo '<div id="result">Result: ' . $result . '</div>';
    }
    ?>
    
    
</body>
</html>