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
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="../config/img/logo.png" type="image/png">
    <link href="https://fonts.googleapis.com/css2?family=SF+Pro+Display:wght@400;500;600&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'SF Pro Display', -apple-system, BlinkMacSystemFont, sans-serif;
        }
        
        body {
            background-color: #000;
            color: #fff;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        
        .back-to-home {
            position: absolute;
            top: 20px;
            left: 20px;
        }
        
        .back-to-home a {
            color: #2997ff;
            text-decoration: none;
            font-size: 16px;
            display: flex;
            align-items: center;
            transition: opacity 0.2s;
        }
        
        .back-to-home a:hover {
            opacity: 0.8;
        }
        
        .login-container {
            width: 90%;
            max-width: 380px;
            padding: 30px;
            border-radius: 20px;
            background-color: #1c1c1e;
        }
        
        .logo {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .logo img {
            width: 60px;
            height: auto;
            border-radius: 12px;
        }
        
        .login-title {
            text-align: center;
            margin-bottom: 30px;
            font-size: 28px;
            font-weight: 600;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group input {
            width: 100%;
            padding: 16px;
            border-radius: 12px;
            border: none;
            background-color: #2c2c2e;
            color: #fff;
            font-size: 16px;
            transition: background-color 0.2s;
        }
        
        .form-group input:focus {
            background-color: #3a3a3c;
            outline: none;
        }
        
        .form-group input::placeholder {
            color: #8e8e93;
        }
        
        .submit-btn {
            width: 100%;
            padding: 16px;
            border-radius: 12px;
            border: none;
            background-color: #2997ff;
            color: #fff;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        
        .submit-btn:hover {
            background-color: #148eff;
        }
        
        .forgot-password {
            margin-top: 20px;
            text-align: center;
        }
        
        .forgot-password a {
            color: #2997ff;
            text-decoration: none;
            font-size: 15px;
            transition: opacity 0.2s;
        }
        
        .forgot-password a:hover {
            opacity: 0.8;
        }
        
        .status-message {
            padding: 16px;
            margin-bottom: 20px;
            border-radius: 12px;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        
        .status-message.error {
            background-color: rgba(255, 69, 58, 0.2);
            color: #ff453a;
        }
        
        .status-message.success {
            background-color: rgba(48, 209, 88, 0.2);
            color: #30d158;
        }
    </style>
</head>
<body>
    <div class="back-to-home">
        <a href="../"><i class="fas fa-chevron-left"></i>&nbsp; Zpět domů</a>
    </div>
    
    <div class="login-container">
        <div class="logo">
            <img src="../config/img/logo-reftrack.png" alt="Logo">
        </div>
        
        <h1 class="login-title">Přihlášení</h1>
        
        <?php if ($error) { ?>
            <div class="status-message error">
                <i class="fas fa-exclamation-circle"></i>
                <span><?php echo $error; ?></span>
            </div>
        <?php } ?>
        
        <?php if ($info) { ?>
            <div class="status-message success">
                <i class="fas fa-check-circle"></i>
                <span><?php echo $info; ?></span>
            </div>
        <?php } ?>
        
        <form action="login_database.php" method="post" id="login-form">
            <?php if(isset($_GET['redirect'])){ ?>
                <input type="hidden" name="redirect" value="<?php echo htmlspecialchars($_GET['redirect']); ?>">
            <?php } ?>
            
            <div class="form-group">
                <input type="text" name="login_id" placeholder="Uživatelské jméno nebo email" autofocus>
            </div>
            
            <div class="form-group">
                <input type="password" name="password" placeholder="Heslo">
            </div>
            
            <button type="submit" class="submit-btn">Přihlásit se</button>
        </form>
        
        <div class="forgot-password">
            <a href="registration">
                <i class="fas fa-user-plus"></i> Nemáte účet? Registrujte se
            </a>
        </div>
    </div>
</body>
</html>