<?php 
//login_database.php
session_start(); 
include "db_conn.php";
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


// Funkce pro validaci vstupu
function validate($data){
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

// Běžný postup přihlášení pomocí jména/emailu a PIN kódu
if (isset($_POST['login_id']) && isset($_POST['password'])) {
    $login_id = validate($_POST['login_id']);
    $pin = validate($_POST['password']);

    if (empty($login_id)) {
        header("Location: ./login?error=" . urlencode("Zadej uživatelské jméno nebo email!"));
        exit();
    } elseif (empty($pin)) {
        header("Location: ./login?error=" . urlencode("Zadej PIN kód!"));
        exit();
    } elseif (strlen($pin) !== 5 || !ctype_digit($pin)) {
        header("Location: ./login?error=" . urlencode("PIN kód musí být 5místné číslo!"));
        exit();
    } else {
        // Použití prepared statement pro prevenci SQL injection
        // Hledáme buď podle username nebo email
        $sql = "SELECT * FROM users WHERE username = ? OR email = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $login_id, $login_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result && mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);

            // Přímé porovnání hesla
            if ($pin !== $row['password']) {
                header("Location: ./login?error=" . urlencode("Neplatné přihlašovací údaje"));
                exit();
            }

            // Nastavení session proměnných
            $_SESSION['role'] = $row['role'];
            $_SESSION['img'] = $row['profile_image'];
            $_SESSION['first_name'] = $row['first_name'];
            $_SESSION['last_name'] = $row['last_name'];
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['sport'] = $row['sport'];
            $_SESSION['birth_date'] = $row['birth_date'];

            // Generování nového session ID
            session_regenerate_id(true);

            // Přesměrování na checkout-session, pokud je přítomen redirect parametr
            if (isset($_POST['redirect']) && !empty($_POST['redirect'])) {
                header("Location: " . $_POST['redirect']);
                exit();
            }

            // Všichni uživatelé jdou na dashboard bez ohledu na roli
            header("Location: ../dashboard");
            exit();
        } else {
            // Neplatné přihlašovací údaje
            header("Location: ./login?error=" . urlencode("Přihlášení nebylo úspěšné"));
            exit();
        }
    }
}
?>