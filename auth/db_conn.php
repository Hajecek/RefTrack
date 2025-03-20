<?php
ob_start();

// Nastavení pro lokální připojení
$sname = "localhost";
$unmae = "hajecek";
$password = "hajecek10!M";
$db_name = "reftrack_lokalni";
$port = 3306; // Standardní port pro MySQL

$conn = mysqli_connect($sname, $unmae, $password, $db_name, $port);

if (!$conn) {
    echo "Connection failed: " . mysqli_connect_error();
    exit();
}

// Nastavení kódování na UTF-8
mysqli_set_charset($conn, "utf8");

?>
