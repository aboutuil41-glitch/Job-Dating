<?php
/**
 * Configuration de la base de données
 */

define('DB_HOST', 'localhost');
define('DB_NAME', 'test_app');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

/**
 * Configuration des sessions sécurisées
 * ⚠️ MUST be done BEFORE session_start
 */
if (session_status() === PHP_SESSION_NONE) {

    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_only_cookies', 1);
    ini_set('session.cookie_secure', 0); // 1 if HTTPS
    ini_set('session.cookie_samesite', 'Strict');

    // Session lifetime: 30 minutes
    ini_set('session.gc_maxlifetime', 1800);

    session_set_cookie_params([
        'lifetime' => 1800,
        'path' => '/',
        'httponly' => true,
        'secure' => false,
        'samesite' => 'Strict'
    ]);

    session_start();
}

/**
 * Regenerate session ID once (anti-hijacking)
 */
if (!isset($_SESSION['initiated'])) {
    session_regenerate_id(true);
    $_SESSION['initiated'] = true;
}
