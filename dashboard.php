<?php
session_start();
include "auth/auth.php"; // Zahrnuje autorizační funkce

// Pokud uživatel není přihlášen, auth.php ho přesměruje

// Zde začíná kód dashboardu
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" type="text/css" href="config/css/style.css">
    <link rel="icon" href="config/img/logo.png" type="image/png">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    <header>
        <h1>Dashboard</h1>
        <p>Vítejte, <?php echo $_SESSION['first_name'] . " " . $_SESSION['last_name']; ?></p>
    </header>
    
    <div class="dashboard-content">
        <div class="user-info">
            <h2>Informace o uživateli</h2>
            <p>Uživatelské jméno: <?php echo $_SESSION['username']; ?></p>
            <p>Email: <?php echo $_SESSION['email']; ?></p>
            <p>Role: <?php echo $_SESSION['role']; ?></p>
        </div>
        
        <div class="actions">
            <h2>Možné akce</h2>
            <ul>
                <li><a href="#">Správa profilu</a></li>
                <li><a href="#">Zobrazit statistiky</a></li>
                <li><a href="auth/logout.php">Odhlásit se</a></li>
            </ul>
        </div>
    </div>
</body>
</html>
