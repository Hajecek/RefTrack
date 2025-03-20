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
    $paths = array("../auth/login", "../../auth/login");
    
    foreach ($paths as $path) {
        if (file_exists($path . ".php")) {
            header("Location: $path?error=$encoded_error");
            exit();
        }
    }
    
    header("Location: ../auth/login?error=$encoded_error");
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

$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $user_role = $row['role'];
    
    if ($user_role !== ROLE_ADMIN && $user_role !== ROLE_ADMIN_CEO) {
        redirectWithError("Nemáte dostatečné oprávnění!");
    }
} else {
    echo htmlspecialchars("Nemáte dostatečné oprávnění.", ENT_QUOTES, 'UTF-8');
    exit();
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
?>