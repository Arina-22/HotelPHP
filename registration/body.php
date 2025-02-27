<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/registration.css" type="text/css">
    <title>Регистрация</title>
</head>

<body>
    <section class="main-section">
        <div class="container">
            <h2>Регистрация</h2>
            <div class="form-wrapper">
                <form id="regForm" method="post" class="form" action="register-validation.php">
                    <div class="box-input">
                        <input class="input" id="name" name="name" type="text" required>
                        <label>Введите ФИО</label>
                        <span class="error" id="name-error"></span>
                    </div>
                    <div class="box-input">
                        <input class="input" id="email" name="email" type="email" required>
                        <label>Введите E-mail</label>
                        <span class="error" id="email-error"></span>
                    </div>
                    <div class="box-input">
                        <input class="input" id="first-password" name="first-password" type="password" required>
                        <label>Придумайте пароль</label>
                        <span class="error" id="first-password-error"></span>
                    </div>
                    <div class="box-input">
                        <input class="input" id="second-password" name="second-password" type="password" required>
                        <label>Повторите пароль</label>
                        <span class="error" id="second-password-error"></span>
                    </div>
                    <div class="box-input">
                        <input class="input" id="phone" name="phone" type="text" required>
                        <label>Введите номер телефона</label>
                        <span class="error" id="phone-error"></span>
                    </div>
                    <a href="login.php">Уже есть аккаунт?</a>
                    <input type="submit" class="button" value="Зарегистрироваться">
                </form>
            </div>
        </div>
    </section>

    <script src="ajax-script-reg.js"></script>
</body>

</html>