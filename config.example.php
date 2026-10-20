<?php
//========================================================================================
//  APPLICATION CONFIGURATION (template)
//
//  Copy this file to config.php for a local override. config.php is git-ignored.
//  Every value is read from an environment variable first (used by Docker),
//  then falls back to the default given here.
//========================================================================================

return [
    'db' => [
        'host'     => getenv('DB_HOST')     ?: 'localhost',
        'port'     => getenv('DB_PORT')     ?: '3306',
        'name'     => getenv('DB_NAME')     ?: 'krima_rep',
        'user'     => getenv('DB_USER')     ?: 'root',
        'password' => getenv('DB_PASSWORD') ?: '',
    ],

    // Force the "Secure" flag on the session cookie even if HTTPS can't be
    // detected (e.g. behind a TLS-terminating proxy). Set to "1" in production.
    'force_secure_cookies' => getenv('APP_FORCE_SECURE_COOKIES') === '1',
];
