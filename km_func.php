<?php
// ======================================================
//                                 Functions Library File
// ======================================================


// ------------------------------------------------------
//  Configuration
// ------------------------------------------------------

/**
 * Load the app configuration once: config.php if present, otherwise the
 * committed config.example.php (which reads environment variables).
 */
function app_config(): array
{
    static $config = null;
    if ($config === null) {
        $file   = is_file(__DIR__ . '/config.php') ? __DIR__ . '/config.php' : __DIR__ . '/config.example.php';
        $config = require $file;
    }
    return $config;
}


// ------------------------------------------------------
//  Security helpers
// ------------------------------------------------------

/**
 * Send basic security headers. Call before any output.
 */
function send_security_headers(): void
{
    header('X-Frame-Options: DENY');                         // no clickjacking via iframes
    header('X-Content-Type-Options: nosniff');               // no MIME sniffing
    header('Referrer-Policy: strict-origin-when-cross-origin');
}

/**
 * Start the session with hardened cookie settings.
 * The Secure flag is set when the request is HTTPS (or forced via config).
 */
function start_secure_session(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }

    $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
            || app_config()['force_secure_cookies'];

    ini_set('session.use_strict_mode', '1');    // reject uninitialised session ids
    ini_set('session.use_only_cookies', '1');   // never accept ids from the URL

    session_name('KMSESSID');
    session_set_cookie_params([
        'lifetime' => 0,          // until the browser closes
        'path'     => '/',
        'secure'   => $isHttps,
        'httponly' => true,       // not readable from JavaScript
        'samesite' => 'Lax',
    ]);
    session_start();
}

/**
 * Return the CSRF token for this session, creating it if needed.
 */
function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Hidden form field carrying the CSRF token.
 */
function csrf_field(): string
{
    return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
}

/**
 * Check the submitted CSRF token against the session (timing-safe).
 */
function csrf_verify(): bool
{
    $token = $_POST['csrf_token'] ?? '';
    return is_string($token)
        && !empty($_SESSION['csrf_token'])
        && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Escape a value for safe output in HTML (text and attributes).
 */
function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/**
 * Read a POST field as a string. Arrays or missing fields become ''.
 */
function post_string(string $key, bool $trim = true): string
{
    $value = $_POST[$key] ?? '';
    if (!is_string($value)) {
        return '';
    }
    return $trim ? trim($value) : $value;
}


// ------------------------------------------------------
//  Text helpers
// ------------------------------------------------------

/**
 * Transliterate (Greek) text into a lowercase, URL/filename-friendly slug.
 *
 * Greek letters are mapped to Latin equivalents (e.g. "θ" => "th"), whitespace
 * and slashes become hyphens, every other character outside [a-z0-9-] is
 * removed and repeated hyphens are collapsed.
 *
 * Example: greekToEnglishChars('Καλημέρα Κόσμε!') returns 'kalimera-kosme'.
 *
 * @param  string $text UTF-8 input text.
 * @return string       Lowercase ASCII slug.
 */
function greekToEnglishChars(string $text): string
{
    static $map = [
        'α' => 'a',  'ά' => 'a',  'Α' => 'A',  'Ά' => 'A',
        'β' => 'b',  'Β' => 'B',
        'γ' => 'g',  'Γ' => 'G',
        'δ' => 'd',  'Δ' => 'D',
        'ε' => 'e',  'έ' => 'e',  'Ε' => 'E',  'Έ' => 'E',
        'ζ' => 'z',  'Ζ' => 'Z',
        'η' => 'i',  'ή' => 'i',  'Η' => 'I',  'Ή' => 'I',
        'θ' => 'th', 'Θ' => 'Th',
        'ι' => 'i',  'ί' => 'i',  'ϊ' => 'i',  'ΐ' => 'i',  'Ι' => 'I',  'Ί' => 'I',  'Ϊ' => 'I',
        'κ' => 'k',  'Κ' => 'K',
        'λ' => 'l',  'Λ' => 'L',
        'μ' => 'm',  'Μ' => 'M',
        'ν' => 'n',  'Ν' => 'N',
        'ξ' => 'x',  'Ξ' => 'X',
        'ο' => 'o',  'ό' => 'o',  'Ο' => 'O',  'Ό' => 'O',
        'π' => 'p',  'Π' => 'P',
        'ρ' => 'r',  'Ρ' => 'R',
        'σ' => 's',  'ς' => 's',  'Σ' => 'S',
        'τ' => 't',  'Τ' => 'T',
        'υ' => 'y',  'ύ' => 'y',  'ϋ' => 'y',  'ΰ' => 'y',  'Υ' => 'Y',  'Ύ' => 'Y',  'Ϋ' => 'Y',
        'φ' => 'f',  'Φ' => 'F',
        'χ' => 'ch', 'Χ' => 'Ch',
        'ψ' => 'ps', 'Ψ' => 'Ps',
        'ω' => 'o',  'ώ' => 'o',  'Ω' => 'O',  'Ώ' => 'O',
    ];

    $slug = strtr(trim($text), $map);
    $slug = preg_replace('~[\s/\\\\]+~u', '-', $slug) ?? '';    // whitespace and slashes -> '-'
    $slug = preg_replace('~[^A-Za-z0-9-]+~', '', $slug);        // drop everything else
    $slug = preg_replace('~-{2,}~', '-', $slug);                // collapse repeated '-'

    return strtolower(trim($slug, '-'));
}
