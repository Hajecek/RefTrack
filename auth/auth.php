<?php
// auth for all types of Admins
session_start();
define('ROLE_ADMIN', 'Admin');
define('ROLE_ADMIN_CEO', 'Admin, CEO');

$session_lifetime = 5600;

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if (!isset($_SESSION['ip_address'])) {
    $_SESSION['ip_address'] = $_SERVER['REMOTE_ADDR'];
} else {
    $stored_ip = ip2long($_SESSION['ip_address']);
    $current_ip = ip2long($_SERVER['REMOTE_ADDR']);
    
    if (abs($stored_ip - $current_ip) > 256) {
        session_unset();
        session_destroy();
        redirectWithError("Změna IP adresy. Přihlašte se znovu");
    }
}

function redirectWithError($error_message) {
    $encoded_error = urlencode($error_message);
    
    // Zjištění aktuální cesty
    $current_dir = dirname($_SERVER['PHP_SELF']);
    
    // Určení relativní cesty k login stránce
    if (strpos($current_dir, '/auth') !== false) {
        // Jsme v adresáři auth nebo jeho podadresáři
        header("Location: ./login?error=$encoded_error");
    } else {
        // Jsme mimo adresář auth
        header("Location: ./auth/login?error=$encoded_error");
    }
    exit();
}

if (!isset($_SESSION['user_id'])) {
    redirectWithError("Pro tuto akci se musíte přihlásit!");
}

if (!isset($_SESSION['last_activity'])) {
    $_SESSION['last_activity'] = time();
} elseif (time() - $_SESSION['last_activity'] > $session_lifetime) {
    session_unset();
    session_destroy();
    redirectWithError("Vaše relace vypršela. Přihlaste se prosím znovu.");
} else {
    $_SESSION['last_activity'] = time();
}

// Připojení k databázi - toto chybělo v původním auth.php
include_once __DIR__ . "/db_conn.php";

$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id = ?";  // opraveno z user_id na id podle struktury tabulky
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $user_role = $row['role'];
    
    // Kontrola role - přístup povolíme všem přihlášeným
    // Pokud chcete povolit přístup pouze administrátorům, odkomentujte následující podmínku
    /*
    if ($user_role !== ROLE_ADMIN && $user_role !== ROLE_ADMIN_CEO) {
        redirectWithError("Nemáte dostatečné oprávnění!");
    }
    */
} else {
    redirectWithError("Uživatel nebyl nalezen v databázi!");
}

// Zde pokračuje váš kód pro autorizované uživatele

/*
<form method="post" action="process.php">
    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
    <!-- Ostatní pole formuláře -->
    <input type="submit" value="Odeslat">
</form>
*/

/*
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Neplatný požadavek.");
    }
    // Zpracování formuláře
}
*/
