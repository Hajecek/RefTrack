<?php
include "db_conn.php";
session_start();

// Kontrola, zda je uživatel přihlášen
if(isset($_SESSION['user_id'])) {
    // Aktuální čas přihlášení
    $today = date("d.m.Y H:i:s");

    // Získání IP adresy
    $ip = $_SERVER['REMOTE_ADDR'];

    // Kontrola parametru inactivity v URL
    $status = (isset($_GET['r']) && $_GET['r'] === 'inactivity') ? 'logged out due to inactivity' : 'logged out';

    // Vložení do tabulky userlog
    $sql = mysqli_query($conn, "INSERT INTO userlog (user_id, label, user_name, ip_address, time, status) 
                               VALUES ('".$_SESSION['user_id']."', 'authentikace', '".$_SESSION['user_name']."', 
                               '$ip', '$today', '$status')");

    // Aktualizace stavu uživatele
    $sql2 = mysqli_query($conn, "UPDATE users SET status = 'Offline now' 
                                WHERE unique_id = {$_SESSION['unique_id']}");

    // Odstranění session
    session_id($_SESSION['user_session_id']);
    session_unset();
    session_destroy();

    if(isset($_SESSION['unique_id'])) {
        unset($_SESSION["user_id"]);
        unset($_SESSION["user_name"]);
        $logout_id = mysqli_real_escape_string($conn, $_GET['logout_id']);
    }
}

// Změna přesměrování podle parametru r
if(isset($_GET['r']) && $_GET['r'] === 'inactivity') {
    header("Location: ../logged-out");
} else {
    header("Location: ../");
}
?>