<?php
session_start(); 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

include "../../auth/db_conn.php";

if (!$conn) {
    echo json_encode(["status" => "error", "message" => "Chyba připojení k databázi"]);
    exit();
}

if (isset($_POST['pair_code'])) {
    function validate($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $pair_code = validate($_POST['pair_code']);

    if (empty($pair_code)) {
        echo json_encode(["status" => "error", "message" => "Zadejte párovací kód!"]);
        exit();
    } else {
        $sql = "SELECT * FROM users WHERE pair_code='$pair_code'";
        $result = mysqli_query($conn, $sql);

        if ($result && mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);
            
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['pair_code'] = $row['pair_code'];
            
            $today = date("Y-m-d H:i:s");
            $ip = $_SERVER['REMOTE_ADDR'];
            
            session_regenerate_id();

            echo json_encode([
                "status" => "success", 
                "message" => "Přihlášení úspěšné",
                "id" => $row['id'],
                "pair_code" => $row['pair_code'],
                "first_name" => $row['first_name'],
                "last_name" => $row['last_name'],
                "profile_image" => $row['profile_image'],
            ]);
            exit();
        } else {
            echo json_encode(["status" => "error", "message" => "Neplatný párovací kód"]);
            exit();
        }
    }
} else {
    echo json_encode(["status" => "error", "message" => "Chybí párovací kód"]);
    exit();
}
?>