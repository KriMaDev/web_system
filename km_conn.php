<?php
//========================================================================================
//  DATABASE CONNECTION
//  Settings come from app_config(): config.php (git-ignored) if it exists, otherwise
//  the committed config.example.php, which reads everything from environment variables.
//========================================================================================
    require_once __DIR__ . '/km_func.php';

    // Any uncaught error (e.g. a failed query) is logged, never shown to the visitor
    set_exception_handler(function (Throwable $e) {
        error_log('Uncaught ' . get_class($e) . ': ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
        if (!headers_sent()) {
            http_response_code(500);
        }
        echo 'Something went wrong. Please try again later.';
    });

    $db = app_config()['db'];

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
