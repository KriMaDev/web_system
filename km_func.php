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


function greekToEnglishChars($filestr)
    {
        $filestr = trim($filestr);
        // Replace Greek Letters
        $greek   = array('α','ά','Ά','Α','β','Β','γ', 'Γ', 'δ','Δ','ε','έ','Ε','Έ','ζ','Ζ','η','ή','Η','θ','Θ','ι','ί','ϊ','ΐ','Ι','Ί', 'κ','Κ','λ','Λ','μ','Μ','ν','Ν','ξ','Ξ','ο','ό','Ο','Ό','π','Π','ρ','Ρ','σ','ς', 'Σ','τ','Τ','υ','ύ','Υ','Ύ','φ','Φ','χ','Χ','ψ','Ψ','ω','ώ','Ω','Ώ',"'","'",','); 
        $english = array('a', 'a','A','A','b','B','g','G','d','D','e','e','E','E','z','Z','i','i','I','th','Th', 'i','i','i','i','I','I','k','K','l','L','m','M','n','N','x','X','o','o','O','O','p','P' ,'r','R','s','s','S','t','T','y','y','Y','Y','f','F','ch','Ch','ps','Ps','o','o','O','O','_','_','_'); 
        $filestr = str_replace($greek, $english, $filestr);
        
        // Change or Remove Special Chars
        $filestr = str_replace(" - ", "-", $filestr);  // Change chars to '-'
        $filestr = str_replace(" -",  "-", $filestr);  // Change chars to '-'
        $filestr = str_replace("- ",  "-", $filestr);  // Change chars to '-'
        $filestr = str_replace(" ",   "-", $filestr);  // Change space char to '-'
        $filestr = str_replace("\\",  "-", $filestr);  // Change '\' char to '-'
        $filestr = str_replace("/",   "-", $filestr);  // Change '\' char to '-'
        $filestr = str_replace(".",   "",  $filestr);  // Remove '.' char
        $filestr = str_replace(",",   "",  $filestr);  // Remove ',' char
        $filestr = str_replace("'",   "",  $filestr);  // Remove ' char
        $filestr = str_replace("!",   "",  $filestr);  // Remove '!' char
        $filestr = str_replace("~",   "",  $filestr);  // Remove '~' char
        $filestr = str_replace("`",   "",  $filestr);  // Remove '`' char
        $filestr = str_replace("!",   "",  $filestr);  // Remove '!' char
        $filestr = str_replace("@",   "",  $filestr);  // Remove '@' char
        $filestr = str_replace("#",   "",  $filestr);  // Remove '#' char
        $filestr = str_replace("$",   "",  $filestr);  // Remove '$' char
        $filestr = str_replace("%",   "",  $filestr);  // Remove '%' char
        $filestr = str_replace("^",   "",  $filestr);  // Remove '^' char
        $filestr = str_replace("&",   "",  $filestr);  // Remove '&' char
        $filestr = str_replace("*",   "",  $filestr);  // Remove '*' char
        $filestr = str_replace("(",   "",  $filestr);  // Remove '(' char
        $filestr = str_replace(")",   "",  $filestr);  // Remove ')' char
        $filestr = str_replace("+",   "",  $filestr);  // Remove '+' char
        $filestr = str_replace(":",   "",  $filestr);  // Remove ':' char
        $filestr = str_replace("[",   "",  $filestr);  // Remove '[' char
        $filestr = str_replace("]",   "",  $filestr);  // Remove ']' char
        $filestr = str_replace("{",   "",  $filestr);  // Remove '{' char
        $filestr = str_replace("}",   "",  $filestr);  // Remove '}' char
        $filestr = str_replace(";",   "",  $filestr);  // Remove ';' char
        $filestr = str_replace("\"",  "",  $filestr);  // Remove '"' char
        $filestr = str_replace("|",   "",  $filestr);  // Remove '|' char
        $filestr = str_replace("<",   "",  $filestr);  // Remove '<' char
        $filestr = str_replace(">",   "",  $filestr);  // Remove '>' char
        $filestr = str_replace("?",   "",  $filestr);  // Remove '?' char
        $filestr = str_replace("=",   "",  $filestr);  // Remove '=' char
        $filestr = str_replace("_",   "",  $filestr);  // Remove '_' char
        $filestr = str_replace("--",  "-", $filestr);  // Replace '--' char
        $filestr = str_replace("---", "-", $filestr);  // Replace '---' char
        $filestr = str_replace("«", "",    $filestr);     // Remove '«' char
        $filestr = str_replace("»", "",    $filestr);     // Remove '»' char
        $filestr = str_replace("”", "",    $filestr);     // Remove '”' char
        $filestr = str_replace("“", "",    $filestr);     // Remove '“' char
        $filestr = str_replace("΄", "",    $filestr);     // Remove '΄' char
        
        return strtolower($filestr);			
    }  // End of Function
?>