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

// Debug výpis pro kontrolu přijatého user_id
error_log("Received user_id: " . $userId);

// Kontrola existence a validity user_id
if (!$userId) {
    echo json_encode([
        "status" => "error",
        "message" => "Chybí parametr user_id",
        "matches" => []
    ]);
    exit();
}

// Převod na integer a kontrola, zda je to platné číslo
$userId = filter_var($userId, FILTER_VALIDATE_INT);
if ($userId === false || $userId <= 0) {
    echo json_encode([
        "status" => "error",
        "message" => "Neplatný parametr user_id",
        "matches" => []
    ]);
    exit();
}

// Příprava SQL dotazu s prepared statement
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

// Bindování parametru
mysqli_stmt_bind_param($stmt, "i", $userId);

// Provedení dotazu
if (!mysqli_stmt_execute($stmt)) {
    echo json_encode([
        "status" => "error",
        "message" => "Chyba při provádění dotazu: " . mysqli_stmt_error($stmt),
        "matches" => []
    ]);
    exit();
}

// Získání výsledků
$result = mysqli_stmt_get_result($stmt);

if (!$result) {
    echo json_encode([
        "status" => "error",
        "message" => "Chyba při získávání výsledků: " . mysqli_error($conn),
        "matches" => []
    ]);
    exit();
}

// Zpracování výsledků
$matches = array();
while ($row = mysqli_fetch_assoc($result)) {
    // Formátování data
    $row['match_date'] = date('Y-m-d\TH:i:s', strtotime($row['match_date']));
    $matches[] = $row;
}

// Uzavření statementu a připojení
mysqli_stmt_close($stmt);
mysqli_close($conn);

// Vrácení odpovědi
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