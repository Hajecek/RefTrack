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
        
        .pin-container {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-bottom: 20px;
        }
        
        .pin-input {
            width: 50px;
            height: 60px;
            text-align: center;
            font-size: 20px;
            border-radius: 12px;
            border: none;
            background-color: #2c2c2e;
            color: #fff;
            transition: background-color 0.2s;
        }
        
        .pin-input:focus {
            background-color: #3a3a3c;
            outline: none;
        }
        
        /* Skryjeme původní pole pro heslo */
        #password-field {
            display: none;
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
                <div class="pin-container">
                    <input type="text" class="pin-input" maxlength="1" pattern="[0-9]" inputmode="numeric">
                    <input type="text" class="pin-input" maxlength="1" pattern="[0-9]" inputmode="numeric">
                    <input type="text" class="pin-input" maxlength="1" pattern="[0-9]" inputmode="numeric">
                    <input type="text" class="pin-input" maxlength="1" pattern="[0-9]" inputmode="numeric">
                    <input type="text" class="pin-input" maxlength="1" pattern="[0-9]" inputmode="numeric">
                </div>
                <input type="hidden" name="password" id="password-field">
                <div id="pin-error" class="status-message error" style="display: none; margin-top: 10px;">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>Zadejte prosím pouze číslice (0-9).</span>
                </div>
                <div id="incomplete-pin-error" class="status-message error" style="display: none; margin-top: 10px;">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>Zadejte prosím celý PIN kód.</span>
                </div>
            </div>
            
            <button type="submit" class="submit-btn">Přihlásit se</button>
        </form>
        
        <div class="forgot-password">
            <a href="registration">
                <i class="fas fa-user-plus"></i> Nemáte účet? Registrujte se
            </a>
        </div>
    </div>
    
    <script>
        // JavaScript pro automatické přeskakování mezi PIN políčky
        document.addEventListener('DOMContentLoaded', function() {
            const pinInputs = document.querySelectorAll('.pin-input');
            const passwordField = document.getElementById('password-field');
            const form = document.getElementById('login-form');
            const pinError = document.getElementById('pin-error');
            const incompletePinError = document.getElementById('incomplete-pin-error');
            
            // Autofocus na první PIN políčko po zadání uživatelského jména
            document.querySelector('input[name="login_id"]').addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    pinInputs[0].focus();
                }
            });
            
            // Funkce pro zvýraznění neplatného vstupu
            function showError(errorElement) {
                // Skrýt všechny chybové hlášky
                pinError.style.display = 'none';
                incompletePinError.style.display = 'none';
                
                // Zobrazit konkrétní chybovou hlášku
                errorElement.style.display = 'flex';
                
                // Zbarvit PIN políčka
                pinInputs.forEach(input => {
                    input.style.backgroundColor = '#3a2a2a';
                    input.style.border = '1px solid #ff453a';
                });
            }
            
            // Funkce pro resetování chybového stavu
            function resetError() {
                pinError.style.display = 'none';
                incompletePinError.style.display = 'none';
                
                pinInputs.forEach(input => {
                    input.style.backgroundColor = '#2c2c2e';
                    input.style.border = 'none';
                });
            }
            
            // Automatický přeskok na další políčko
            pinInputs.forEach((input, index) => {
                // Kontrola vstupu
                input.addEventListener('input', function(e) {
                    // Kontrola, zda je vstup číslo
                    if (!/^[0-9]*$/.test(this.value)) {
                        this.value = ''; // Vymazat neplatný vstup
                        showError(pinError);
                        return;
                    }
                    
                    resetError();
                    
                    if (this.value.length === 1) {
                        // Přeskočit na další políčko
                        if (index < pinInputs.length - 1) {
                            pinInputs[index + 1].focus();
                        }
                    }
                });
                
                // Mazání a přeskok zpět
                input.addEventListener('keydown', function(e) {
                    if (e.key === 'Backspace' && this.value.length === 0 && index > 0) {
                        pinInputs[index - 1].focus();
                        resetError();
                    }
                });
                
                // Reakce na paste event
                input.addEventListener('paste', function(e) {
                    e.preventDefault();
                    resetError();
                    
                    // Získat text ze schránky
                    const pasteData = (e.clipboardData || window.clipboardData).getData('text');
                    
                    // Kontrola, zda jsou všechny znaky číslice
                    if (!/^\d+$/.test(pasteData)) {
                        showError(pinError);
                        return;
                    }
                    
                    // Rozdělit data do jednotlivých políček
                    const digits = pasteData.split('');
                    
                    for (let i = 0; i < Math.min(digits.length, pinInputs.length); i++) {
                        pinInputs[i].value = digits[i];
                    }
                    
                    // Zaměřit se na další prázdné políčko nebo poslední políčko
                    const nextEmptyIndex = Math.min(digits.length, pinInputs.length - 1);
                    pinInputs[nextEmptyIndex].focus();
                });
            });
            
            // Před odesláním formuláře spojit PIN kód
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Zkontrolovat, zda jsou všechna pole vyplněna
                const emptyInputs = Array.from(pinInputs).filter(input => !input.value);
                if (emptyInputs.length > 0) {
                    showError(incompletePinError);
                    // Zaměřit se na první prázdné políčko
                    emptyInputs[0].focus();
                    return;
                }
                
                const pinCode = Array.from(pinInputs).map(input => input.value).join('');
                
                // Zkontrolovat, zda je PIN kompletní (mělo by být vždy splněno díky předchozí kontrole)
                if (pinCode.length !== 5) {
                    showError(incompletePinError);
                    return;
                }
                
                // Nastavit PIN jako heslo
                passwordField.value = pinCode;
                
                // Odeslat formulář
                this.submit();
            });
        });
    </script>
</body>
</html>