<?php
// upcoming_events-api.php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

include "../../../auth/db_conn.php";

if (!$conn) {
    echo json_encode(["status" => "error", "message" => "Chyba připojení k databázi"]);
    exit();
}

// Získání user_id z GET parametru
if (!isset($_GET['user_id'])) {
    echo json_encode(["status" => "error", "message" => "Chybějící user_id"]);
    exit();
}

$user_id = $_GET['user_id'];

// SQL dotaz pro získání zápasů přihlášeného uživatele
$sql = "SELECT * FROM matches WHERE user_id = $user_id ORDER BY match_date DESC";
$result = mysqli_query($conn, $sql);

if ($result) {
    $matches = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $matches[] = array(
            "id" => $row['id'],
            "user_id" => (int)$row['user_id'],
            "competition" => $row['competition'],
            "home_team" => $row['home_team'],
            "away_team" => $row['away_team'],
            "match_date" => $row['match_date'],
            "location" => $row['location'],
            "first_half_duration" => $row['first_half_duration'],
            "second_half_duration" => $row['second_half_duration'],
            "total_match_time" => $row['total_match_time'] !== null && $row['total_match_time'] !== '' 
        ? (int)$row['total_match_time'] 
        : null,
            "home_score" => $row['home_score'],
            "away_score" => $row['away_score'],
            "role" => $row['role'],
            "distance_run" => $row['distance_run'],
            "payment" => $row['payment'],
            "yellow_cards" => $row['yellow_cards'],
            "red_cards" => $row['red_cards'],
            "delegate_rating" => $row['delegate_rating'],
            "match_review" => $row['match_review'],
            "head_referee" => $row['head_referee'],
            "assistant_referee_1" => $row['assistant_referee_1'],
            "assistant_referee_2" => $row['assistant_referee_2'],
            "fourth_official" => $row['fourth_official'],
            "delegate_name" => $row['delegate_name'],
            "visibility" => $row['visibility'],
            "status" => $row['status'],
            "created_at" => $row['created_at'],
            "created_by" => $row['created_by']
        );
    }
    
    echo json_encode([
        "status" => "success",
        "message" => "Data úspěšně načtena",
        "matches" => $matches
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Chyba při načítání dat: " . mysqli_error($conn)
    ]);
}

mysqli_close($conn);
?>