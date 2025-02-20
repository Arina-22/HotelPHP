<?php
include '../db.php';
session_start();

$nameError = '';
$emailError = '';
$firstPasswordError = '';
$secondPasswordError = '';
$phoneError = '';

$name = '';
$email = '';
$firstPassword = '';
$secondPassword = '';
$phone = '';

function clearString($str)
{
    $str = trim($str);
    $str = strip_tags($str); 
    $str = stripslashes($str);
    return $str;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['form-submitted']) && $_POST['form-submitted'] === 'true') {
    $name = clearString($_POST["name"]);
    $_SESSION["name"] = $name;
    $email = clearString($_POST["email"]);
    $firstPassword = clearString($_POST["first-password"]);
    $secondPassword = clearString($_POST["second-password"]);
    $phone = clearString($_POST["phone"]);

    if (!preg_match('/^[А-ЯЁ][а-яё]+\s[А-ЯЁ][а-яё]+\s[А-ЯЁ][а-яё]+$/iu', $name)) {
        $nameError .= "Неккоретное имя";
    }

    // Проверка email
    if (!preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/i', $email)) {
        $emailError .= "Некорректная почта. ";
    } 
    else {
        // Проверка на существование почты в БД
        $query = "SELECT id FROM users WHERE email='$email'";
        $result = mysqli_query($conn, $query) or die("Ошибка выполнения запроса" . mysqli_error($conn));

        if ($result && mysqli_fetch_row($result)) {
            $emailError = 'Пользователь с данной почтой уже существует.';
        }
    }

    // Проверка паролей
    if (!preg_match('/^[а-яА-Яa-zA-Z]{8,}$/u', $firstPassword)) {
        $firstPasswordError = "Пароль может содержать только буквы и быть длиной не менее 8 символов.";
    } elseif (preg_match('/[#$%^&_=+-]/', $firstPassword)) {
        $firstPasswordError = "Пароль не может содержать специальные символы.";
    }

    if ($secondPassword !== $firstPassword) {
        $secondPasswordError = "Пароли не совпадают.";
    }

    if (!preg_match('/^\+?[0-9]{10,12}$/', $phone)) {
        $phoneError = "Некорректный номер телефона.";
    }

    if (empty($nameError) && empty($emailError) && empty($firstPasswordError) && empty($secondPasswordError) && empty($phoneError)) {
        include '../db.php';
        $salt = mt_rand(100, 999);
        $password = md5(md5($firstPassword) . $salt);

        try {
            $query = "INSERT INTO users (username, password, email, salt, phone) 
                  VALUES ('$name', '$password', '$email', '$salt', '$phone')";
            $result = mysqli_query($conn, $query);

            if ($result) {
                // Успешная регистрация
                $query = "SELECT * FROM users WHERE email='$email'";
                $rez = mysqli_query($conn, $query);

                if ($rez && $row = mysqli_fetch_assoc($rez)) {
                    $_SESSION["name"] = $row['name'];
                    mysqli_close($conn);
                    echo "<script>alert('Вы зарегистрировались!'); top.location= '../user-page.php';</script>";
                } else {
                    echo "<script>alert('Вы не зарегистрированы.'); top.location= 'index.php';</script>";
                }
            }
        } catch (mysqli_sql_exception $e) {
            // ошибки регистрации
            $error = $e->getMessage();
        }

        mysqli_close($conn);
    } 
}
?>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/registration.css" type="text/css">
    <title>Регистрация</title>
</head>

<section class="main-section">
    <div class="container">
        <h2>Регистрация</h2>
        <div class="form-wrapper">
            <form action="" method="post" class="form" name="regform">
                <input type="hidden" name="form-submitted" value="true">
                <div class="box-input">
                    <input class="input" name="name" type="text" value="<?= @$name; ?>" required>
                    <label>Введите ФИО</label>
                    <span class="error"><?= @$nameError; ?></span>
                </div>
                <div class="box-input">
                    <input class="input" name="email" type="email" value="<?= @$email; ?>" required>
                    <label>Введите E-mail</label>
                    <span class="error"><?= @$emailError; ?></span>
                </div>
                <div class="box-input">
                    <input class="input" name="first-password" type="password" required>
                    <label>Придумайте пароль</label>
                    <span class="error"><?= @$firstPasswordError; ?></span>
                </div>
                <div class="box-input">
                    <input class="input" name="second-password" type="password" required>
                    <label>Повторите пароль</label>
                    <span class="error"><?= @$secondPasswordError; ?></span>
                </div>
                <div class="box-input">
                    <input class="input" name="phone" type="text" value="<?= @$phone; ?>" required>
                    <label>Введите номер телефона</label>
                    <span class="error"><?= @$phoneError; ?></span>
                </div>
                <a href="login.php">Уже есть аккаунт?</a>
                <!-- критерий, были ли данные с формы отправлены -->
                <input type="hidden" name="go-reg" value="5"> 
                <input type="submit" class="button" value="Зарегистрироваться">
            </form>
        </div>
    </div>
</section>