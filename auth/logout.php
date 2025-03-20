<?php
include "db_conn.php";
session_start();

// Kontrola, zda je uživatel přihlášen
if(isset($_SESSION['user_id'])) {
    // Zničení session
    session_unset();
    session_destroy();
    
    // Odstranění cookies, pokud existují
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time()-42000, '/');
    }
}

// Přesměrování podle parametru r
if(isset($_GET['r']) && $_GET['r'] === 'inactivity') {
    header("Location: ../logged-out");
} else {
    header("Location: ../");
}
exit();
?>