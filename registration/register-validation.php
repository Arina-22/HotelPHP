<?php
session_start();
include '../db.php';  

$response = ["success" => false, "errors" => []];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    function clearString($str) {
        return stripslashes(strip_tags(trim($str ?? '')));
    }

    // Получаем данные из POST-запроса
    $email = clearString($_POST["email"] ?? '');
    $phone = clearString($_POST["phone"] ?? '');
    $name = clearString($_POST["name"] ?? '');
    $password = clearString($_POST["first-password"] ?? '');
    $secondpassword = clearString($_POST["second-password"] ?? '');

    // Проверка, что пришел запрос на валидацию
    if (isset($_POST['validate']) && $_POST['validate'] === 'true') {
        // Валидация поля name (ФИО)
        if ($name) {
            if (!preg_match('/^[А-ЯЁ][а-яё]+\s[А-ЯЁ][а-яё]+\s[А-ЯЁ][а-яё]+$/iu', $name)) {
                $response['errors']['name'] = 'Некорректное ФИО.';
            }
        }

        // Валидация поля "email"
        if ($email) {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $response['errors']['email'] = 'Некорректная почта.';
            } else {
                // Проверка на существование почты в БД
                $query = "SELECT id FROM users WHERE email='$email'";
                $result = mysqli_query($conn, $query);

                if ($result && mysqli_fetch_row($result)) {
                    $response['errors']['email'] = 'Пользователь с данной почтой уже существует.';
                }
            }
        }

        // Валидация поля "first-password"
        if ($password) {
            if (!preg_match('/^[а-яА-Яa-zA-Z]{8,}$/u', $password)) {
                $response['errors']['firstPassword'] = 'Пароль должен содержать только буквы и быть не менее 8 символов.';
            } elseif (preg_match('/[#$%^&_=+-]/', $password)) {
                $response['errors']['firstPassword'] = 'Пароль не может содержать специальные символы.';
            }
        }

        // Валидация поля "second-password" 
        if ($secondpassword) {
            if ($secondpassword !== $password) {
                $response['errors']['secondPassword'] = 'Пароли не совпадают.';
            }
        }

        // Валидация поля "phone"
        if ($phone) {
            if (!preg_match('/^\+?[0-9]{10,12}$/', $phone)) {
                $response['errors']['phone'] = 'Некорректный номер телефона.';
            }
        }

        echo json_encode($response);
        exit();
    }

    // Основная обработка регистрации
    if (empty($response['errors'])) {
        // Если ошибок нет, продолжаем с регистрацией
        $salt = mt_rand(100, 999);
        $hashedPassword = md5(md5($password) . $salt);

        try {
            // Вставляем данные в базу
            $query = "INSERT INTO users (username, password, email, salt, phone) 
                      VALUES ('$name', '$hashedPassword', '$email', '$salt', '$phone')";
            $result = mysqli_query($conn, $query);

            if ($result) {
                // Успешная регистрация
                $_SESSION["name"] = $name;
                $response["success"] = true;
            } else {
                $response["errors"]["database"] = "Ошибка при регистрации.";
            }
        } catch (mysqli_sql_exception $e) {
            // Ошибка при выполнении SQL-запроса
            // window.location.href = '../user-page.php';
            $response["errors"]["database"] = "Ошибка при выполнении запроса: " . $e;
        }
    }
    
    // echo json_encode($response);
    // exit();
}