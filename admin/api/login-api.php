<?php
//login_api
session_start(); 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

// Opravená cesta k souboru db_conn.php
include "../../auth/db_conn.php"; // Upravte cestu k souboru podle skutečné struktury

if (!$conn) {
    echo json_encode(["status" => "error", "message" => "Chyba připojení k databázi"]);
    exit();
}

// Běžný postup přihlášení pomocí uživatelského jména nebo emailu a hesla
if (isset($_POST['login_id']) && isset($_POST['password'])) {
    function validate($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $login_id = validate($_POST['login_id']); // Může být username nebo email
    $pass = validate($_POST['password']);

    if (empty($login_id)) {
        echo json_encode(["status" => "error", "message" => "Zadej uživatelské jméno nebo email!"]);
        exit();
    } elseif (empty($pass)) {
        echo json_encode(["status" => "error", "message" => "Zadej heslo!"]);
        exit();
    } else {
        // Upravený SQL dotaz podle nové struktury tabulky
        $sql = "SELECT * FROM users WHERE username='$login_id' OR email='$login_id'";
        $result = mysqli_query($conn, $sql);

        if ($result && mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);
            
            // Ověření hesla (poznámka: v produkci by mělo být použito password_verify)
            // V testovacích datech je heslo uloženo jako plaintext, ale mělo by být hashováno
            if ($pass !== $row['password']) {
                echo json_encode(["status" => "error", "message" => "Neplatné přihlašovací údaje"]);
                exit();
            }

            // Nastavení session proměnných podle nové struktury
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['first_name'] = $row['first_name'];
            $_SESSION['last_name'] = $row['last_name'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['role'] = $row['role'];
            $_SESSION['pair_code'] = $row['pair_code'];
            $_SESSION['profile_image'] = $row['profile_image'];
            $_SESSION['sport'] = $row['sport'];
            
            // Aktualizace posledního přihlášení (volitelné - pokud chcete sledovat přihlášení,
            // budete muset přidat toto pole do struktury tabulky)
            $today = date("Y-m-d H:i:s");
            $ip = $_SERVER['REMOTE_ADDR'];
            
            // Generování nového session ID
            session_regenerate_id();
            
            // Volitelné - logování přihlášení
            // Pokud máte tabulku pro logování, můžete použít něco jako:
            // $sql = mysqli_query($conn, "INSERT INTO login_logs (user_id, username, ip_address, login_time) 
            //         VALUES ('".$row['id']."', '".$row['username']."', '$ip', '$today')");

            echo json_encode([
                "status" => "success", 
                "message" => "Přihlášení úspěšné",
                "id" => $row['id'],
                "username" => $row['username'],
                "first_name" => $row['first_name'],
                "last_name" => $row['last_name'],
                "email" => $row['email'],
                "role" => $row['role'],
                "pair_code" => $row['pair_code'],
                "profile_image" => $row['profile_image'],
                "sport" => $row['sport'],
                "birth_date" => $row['birth_date']
            ]);
            exit();
        } else {
            echo json_encode(["status" => "error", "message" => "Neplatné přihlašovací údaje"]);
            exit();
        }
    }
} else {
    echo json_encode(["status" => "error", "message" => "Chybí požadované parametry"]);
    exit();
}
?>
