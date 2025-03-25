<?php
// Nastavení hlaviček a připojení k databázi
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

include "../../auth/db_conn.php";

if (!$conn) {
    echo json_encode([
        "status" => "error",
        "message" => "Chyba připojení k databázi",
        "matches" => []
    ]);
    exit();
}

// Získání user_id z GET parametru
$userId = $_GET['user_id'] ?? null;

if (!$userId || !is_numeric($userId)) {
    echo json_encode([
        "status" => "error",
        "message" => "Chybí nebo neplatný parametr user_id",
        "matches" => []
    ]);
    exit();
}

// SQL dotaz pro získání všech sloupců
$sql = "SELECT * FROM matches WHERE user_id = ? ORDER BY match_date ASC";

$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    echo json_encode([
        "status" => "error",
        "message" => "Chyba při přípravě dotazu: " . mysqli_error($conn),
        "matches" => []
    ]);
    exit();
}

mysqli_stmt_bind_param($stmt, "i", $userId);
$executed = mysqli_stmt_execute($stmt);

if (!$executed) {
    echo json_encode([
        "status" => "error",
        "message" => "Chyba při provádění dotazu: " . mysqli_stmt_error($stmt),
        "matches" => []
    ]);
    exit();
}

$result = mysqli_stmt_get_result($stmt);

if (!$result) {
    echo json_encode([
        "status" => "error",
        "message" => "Chyba při získávání výsledků: " . mysqli_error($conn),
        "matches" => []
    ]);
    exit();
}

$matches = array();
while ($row = mysqli_fetch_assoc($result)) {
    // Formátování data
    $row['match_date'] = date('Y-m-d\TH:i:s', strtotime($row['match_date']));
    $matches[] = $row;
}

mysqli_stmt_close($stmt);
mysqli_close($conn);

if (empty($matches)) {
    echo json_encode([
        "status" => "success",
        "message" => "Uživatel nemá žádné zápasy",
        "matches" => []
    ]);
} else {
    echo json_encode([
        "status" => "success",
        "message" => "",
        "matches" => $matches
    ]);
}
?>