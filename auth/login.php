<?php
//login.php
include "login_database.php";

$error = isset($_GET['error']) ? $_GET['error'] : '';
$info = isset($_GET['info']) ? $_GET['info'] : '';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Přihlášení</title>
    <link rel="stylesheet" type="text/css" href="../config/css/login.css">
    <link rel="icon" href="../config/img/logo.png" type="image/png">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://api.fontshare.com/v2/css?f[]=clash-display@500&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css"/>
    <style>
        .form-row input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .form-row button {
            width: 100%;
            padding: 12px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .form-row button:hover {
            background: #0056b3;
        }
        .login-status {
            padding: 20px;
            margin: 15px 0;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }

        .login-status p {
            margin: 0;
            font-size: 16px;
            font-weight: 500;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .login-status.success {
            background-color: rgba(52, 199, 89, 0.1);
            border: none;
        }

        .login-status.error {
            background-color: rgba(255, 59, 48, 0.1);
            border: none;
        }
    </style>
</head>
<body>
    <div class="back-to-home"><a href="../">← Zpět domů </a></div>
    <div class="contact-wrapper">
        <header class="login-cta">
            <h2><img src="../config/img/logo_v2new.png" alt="" style="width:50px;height:auto"></h2>
            <?php if ($error) { ?>
                <p class="error" id="error-message"><?php echo $error; ?></p>
            <?php } ?>
            <?php if ($info) { ?>
                <p class="info" id="info-message"><?php echo $info; ?></p>
            <?php } ?>
        </header>

        <form action="login_database.php" method="post" id="login-form">
            <?php if(isset($_GET['redirect'])){ ?>
                <input type="hidden" name="redirect" value="<?php echo htmlspecialchars($_GET['redirect']); ?>">
            <?php } ?>
            
            <div class="form-row">
                <input type="text" name="login_id" placeholder="Uživatelské jméno nebo email" autofocus>
            </div>
            <div class="form-row">
                <input type="password" name="password" placeholder="Heslo">
            </div>
            <div class="form-row">
                <button type="submit">Přihlásit se</button>
            </div>
        </form>

        <!-- Přidáno tlačítko pro zapomenuté heslo -->
        <div class="form-row" style="margin-top: 15px;">
            <a href="recovery" style="color: #666; text-decoration: none; font-size: 14px;">
                <i class="fas fa-question-circle"></i> Zapomenuté heslo
            </a>
        </div>
    </div>
</body>
</html>