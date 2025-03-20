<?php
// Nastavení ini pro zabezpečení session
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', !empty($_SERVER['HTTPS']));

// Zkontroluje, zda session ještě neběží
if (session_status() === PHP_SESSION_NONE) {
    // Nastavení ini pro zabezpečení session
    @ini_set('session.cookie_httponly', 1);
    @ini_set('session.use_only_cookies', 1);
    @ini_set('session.cookie_secure', 1); // Vynucení secure cookie, protože víme že jsme na HTTPS

    // Nastavení session cookie před spuštěním session
    session_set_cookie_params([
        'lifetime' => 0,        // Platnost do zavření prohlížeče
        'path' => '/',          // Platí pro celý web
        'domain' => '',         // Prázdné pro automatickou doménu
        'secure' => true,       // Vynucení secure cookie
        'httponly' => true,     // Zabrání přístupu přes JavaScript
        'samesite' => 'Lax'     // Lax je bezpečnější než Strict pro běžné použití
    ]);

    // Spuštění session
    session_start();
    
    // Regenerace session ID pro větší bezpečnost
    if (!isset($_SESSION['last_regeneration'])) {
        session_regenerate_id(true);
        $_SESSION['last_regeneration'] = time();
    }
}
?>