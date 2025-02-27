<?php
session_start();
include '../db.php';

$response = ["success" => false, "errors" => []];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    function clearString($str)
    {
        return stripslashes(strip_tags(trim($str ?? '')));
    }

    // Получаем данные из POST-запроса
    $email = clearString($_POST["email"] ?? '');
    $phone = clearString($_POST["phone"] ?? '');
    $name = clearString($_POST["name"] ?? '');
    $password = clearString($_POST["first-password"] ?? '');
    $secondpassword = clearString($_POST["second-password"] ?? '');

    // Если запрос на валидацию 
    if (isset($_POST['validate']) && $_POST['validate'] === 'true') {
        // Валидация ФИО
        if ($name) {
            if (!preg_match('/^[А-ЯЁ][а-яё]+\s[А-ЯЁ][а-яё]+\s[А-ЯЁ][а-яё]+$/iu', $name)) {
                $response['errors']['name'] = 'Некорректное ФИО.';
            }
        }

        // Валидация email
        if ($email) {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $response['errors']['email'] = 'Некорректная почта.';
            } else {
                // Проверка, существует ли email в базе
                $query = "SELECT id FROM users WHERE email='$email'";
                $result = mysqli_query($conn, $query);
                if ($result && mysqli_fetch_row($result)) {
                    $response['errors']['email'] = 'Пользователь с данной почтой уже существует.';
                }
            }
        }

        // Валидация пароля
        if ($password) {
            if (!preg_match('/^[а-яА-Яa-zA-Z]{8,}$/u', $password)) {
                $response['errors']['firstPassword'] = 'Пароль должен содержать только буквы и быть не менее 8 символов.';
            } elseif (preg_match('/[#$%^&_=+-]/', $password)) {
                $response['errors']['firstPassword'] = 'Пароль не может содержать специальные символы.';
            }
        }

        // Проверка повторного пароля
        if ($secondpassword) {
            if ($secondpassword !== $password) {
                $response['errors']['secondPassword'] = 'Пароли не совпадают.';
            }
        }

        // Валидация телефона
        if ($phone) {
            if (!preg_match('/^\+?[0-9]{10,12}$/', $phone)) {
                $response['errors']['phone'] = 'Некорректный номер телефона.';
            }
        }

        echo json_encode($response);
        exit();
    }

    // Проверка перед регистрацией 
    if (!$name || !preg_match('/^[А-ЯЁ][а-яё]+\s[А-ЯЁ][а-яё]+\s[А-ЯЁ][а-яё]+$/iu', $name)) {
        $response['errors']['name'] = 'Некорректное ФИО.';
    }
    if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['errors']['email'] = 'Некорректная почта.';
    } else {
        $query = "SELECT id FROM users WHERE email='$email'";
        $result = mysqli_query($conn, $query);
        if ($result && mysqli_fetch_row($result)) {
            $response['errors']['email'] = 'Пользователь с данной почтой уже существует.';
        }
    }
    if (!$password || !preg_match('/^[а-яА-Яa-zA-Z]{8,}$/u', $password) || preg_match('/[#$%^&_=+-]/', $password)) {
        $response['errors']['firstPassword'] = 'Пароль должен содержать только буквы и быть не менее 8 символов.';
    }
    if (!$secondpassword || $secondpassword !== $password) {
        $response['errors']['secondPassword'] = 'Пароли не совпадают.';
    }
    if (!$phone || !preg_match('/^\+?[0-9]{10,12}$/', $phone)) {
        $response['errors']['phone'] = 'Некорректный номер телефона.';
    }

    // Если есть ошибки, отправляем их и прерываем регистрацию
    if (!empty($response['errors'])) {
        echo json_encode($response);
        exit();
    }

    // Хеширование пароля
    $salt = mt_rand(100, 999);
    $hashedPassword = md5(md5($password) . $salt);

    // Запись в базу данных
    $query = "INSERT INTO users (username, password, email, salt, phone) 
              VALUES ('$name', '$hashedPassword', '$email', '$salt', '$phone')";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $_SESSION["name"] = $name;
        $response["success"] = true;
    } else {
        $response["errors"]["database"] = "Ошибка при регистрации.";
    }

    echo json_encode($response);
    exit();
}
