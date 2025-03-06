<?php
session_start();

if (isset($_SESSION["name"] ) && $_SESSION["name"] != NULL) {
    header('Location: ../user-page.php'); 
    exit(); 
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/registration.css">
    <title>Авторизация</title>
</head>
<body>
    <section class="main-section">
        <div class="container">
            <h2>Авторизация</h2>
            <div class="form-wrapper">
                <form id="loginForm" class="form" action="auth.php" method="POST">
                    <div class="box-input">
                        <input class="input login-input" id="email" name="email" type="text" required>
                        <label>Введите почту</label>
                        <span class="error" id="email-error"></span>
                    </div>
                    <div class="box-input">
                        <input class="input login-input" id="password" name="first-password" type="password" required>
                        <label>Введите пароль</label>
                        <span class="error" id="password-error"></span>
                    </div>
                    <a href="body.php">Еще нет аккаунта?</a>
                    <input type="submit" class="button" value="Войти">
                </form>
            </div>
        </div>
    </section>

    <script src="ajax-script.js"></script>
</body>
</html>