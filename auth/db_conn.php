<?php
ob_start();

// Detekce prostředí (lokální nebo ostré)
$local = in_array($_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1']); // Lokální IP adresy

if ($local) {
    // Lokální připojení
    $sname = "localhost";
    $unmae = "hajecek";
    $password = "hajecek10!M";
    $db_name = "reftrack_lokalni";
    $port = 3306; // Standardní port pro MySQL
} else {
    // Ostré připojení
    $sname = "md413.wedos.net";
    $unmae = "a337610_pbcz";
    $password = "hajecek10!M";
    $db_name = "d337610_pbcz";
    $port = null; // Port není potřeba na ostré doméně
}

$conn = mysqli_connect($sname, $unmae, $password, $db_name, $port);

if (!$conn) {
    echo "Connection failed: " . mysqli_connect_error();
    exit();
}

// Nastavení kódování na UTF-8
mysqli_set_charset($conn, "utf8");

?>
