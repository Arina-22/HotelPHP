<?php
session_start();
include '../db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    function clearString($str)
    {
        return stripslashes(strip_tags(trim($str ?? '')));
    }

    $userExists = false;

    $emailError = '';
    $email = $_POST["email"];

    clearString($email);

    // Проверка email
    if (!preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/i', $email)) {
        $emailError .= "Некорректная почта. ";
    } else {
        include '../db.php';
        // Проверка на существование почты в БД
        $query = "SELECT id FROM users WHERE email='$email'";
        $result = mysqli_query($conn, $query) or die("Ошибка выполнения запроса" . mysqli_error($conn));

        if ($result) {
            $row = mysqli_fetch_row($result);
            if (empty($row[0]))
                $emailError .= "Пользователь с данной почтой не зарегистрирован";
        }
    }


    $firstPasswordError = '';
    $firstPassword = $_POST["first-password"];

    clearString($firstPassword);

    if ($firstPassword == '') {
        $firstPasswordError .= "Заполните поле";
    }

    if (!preg_match('/^[а-яА-Яa-zA-Z]{8,}$/u', $firstPassword)) {
        $firstPasswordError = "Пароль должен содержать только буквы латинского и русского алфавита и быть не менее 8 символов.";
    } elseif (preg_match('/[#$%^&_=+-]/', $firstPassword)) {
        $firstPasswordError = "Пароль не может содержать #$%^&_=+-.";
    }

    //Проверка второго пароля
    $secondPasswordError = '';
    $secondPassword = $_POST["second-password"];

    clearString($secondPassword);

    if ($secondPassword == '') {
        $secondPasswordError .= "Заполните поле";
    } else if ($secondPassword != $firstPassword) {
        $secondPasswordError .= "Пароли не совпадают";
    }

    if ($emailError . $firstPasswordError . $secondPasswordError == '') {
        $passwordQuery = "SELECT password FROM users WHERE email='$email'";
        $saltQuery = "SELECT salt FROM users WHERE email='$email'";
        $passwordResult = mysqli_query($conn, $passwordQuery) or die("Ошибка выполнения запроса" . mysqli_error($conn));
        $saltResult = mysqli_query($conn, $saltQuery) or die("Ошибка выполнения запроса" . mysqli_error($conn));

        if ($passwordResult && $saltResult) {
            $passworsRow = mysqli_fetch_row($passwordResult);
            $saltRow = mysqli_fetch_row($saltResult);

            if (md5(md5($firstPassword) . $saltRow[0]) == $passworsRow[0]) {
                $userExists = true;
            } else {
                $userExists = false;
                print "<script language='Javascript' type='text/javascript'> alert('Пользователя не существует!');  </script>";
            }
        }
    }

    if ($userExists) {
        $nameQuery = "SELECT username FROM users WHERE email='$email'";
        $nameResult = mysqli_query($conn, $nameQuery) or die("Ошибка выполнения 
       запроса" . mysqli_error($conn));
        if ($nameResult) {
            $nameRow = mysqli_fetch_row($nameResult);
            $_SESSION["name"] = $nameRow[0];
        }
        print "<script language='Javascript' type='text/javascript'>
        // alert(`Вы вошли в аккаунт!`);
        function reload(){top.location = '../user-page.php'};
        reload();
        </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/registration.css" type="text/css">
    <title>Авторизация</title>
</head>

<body>
    <section class="main-section">
        <div class="container">
            <h2>Авторизация</h2>
            <div class="form-wrapper">
                <form action="" method="post" class="form">
                    <div class="box-input">
                        <input class="input login-input" name="email" type="text" required>
                        <label>Введите почту</label>
                        <span class="error"><?= @$emailError; ?></span>
                    </div>
                    <div class="box-input">
                        <input class="input login-input" name="first-password" type="password" required>
                        <label>Введите пароль</label>
                        <span class="error"><?= @$firstPasswordError; ?></span>
                    </div>
                    <div class="box-input">
                        <input class="input login-input" name="second-password" type="password" required>
                        <label>Повторите пароль</label>
                        <span class="error"><?= @$secondPasswordError; ?></span>
                    </div>
                    <input type="hidden" name="go-auth" value="5">
                    <a href="body.php">Еще нет аккаунта?</a>
                    <input type="submit" class="button" value="Войти">
                </form>
            </div>
        </div>
    </section>
</body>

</html>