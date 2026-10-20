<?php
//========================================================================================
//  DATABASE CONNECTION
//  Settings live in config.php (git-ignored). If it doesn't exist, the committed
//  config.example.php is used, which reads everything from environment variables.
//========================================================================================
    $config = require (is_file(__DIR__ . '/config.php') ? __DIR__ . '/config.php' : __DIR__ . '/config.example.php');
    $db     = $config['db'];

    try {
        $pdo = new PDO(
            "mysql:host={$db['host']};port={$db['port']};dbname={$db['name']};charset=utf8mb4",
            $db['user'],
            $db['password'],
            [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,   // throw on errors
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,                    // real prepared statements
            ]
        );
    }
    catch (PDOException $e) {
        // Log the details for the developer, never show them to the visitor
        error_log('Database connection failed: ' . $e->getMessage());
        http_response_code(500);
        exit('Service temporarily unavailable. Please try again later.');
    }
