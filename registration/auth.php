<?php
session_start();
include '../db.php';

$response = ["success" => false, "errors" => []];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    function clearString($str) {
        return stripslashes(strip_tags(trim($str ?? '')));
    }

    $email = clearString($_POST["email"] ?? '');
    $password = clearString($_POST["first-password"] ?? '');

    // Проверка, если это запрос на валидацию полей
    if (isset($_POST['validate']) && $_POST['validate'] === 'true') {
        if ($email) {
            // Валидация email
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $response["errors"]["email"] = "Некорректная почта.";
            }
        }

        if ($password) {
            // Валидация пароля
            if (empty($password)) {
                $response["errors"]["password"] = "Заполните поле.";
            } elseif (!preg_match('/^[а-яА-Яa-zA-Z]{8,}$/u', $password)) {
                $response["errors"]["password"] = "Пароль должен содержать только буквы и быть не менее 8 символов.";
            }
        }

        echo json_encode($response);
        exit(); // Завершаем обработку, если это валидация
    }

    // Основная обработка авторизации
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response["errors"]["email"] = "Некорректная почта.";
    } else {
        $query = "SELECT id, password, salt, username FROM users WHERE email='$email'";
        $result = mysqli_query($conn, $query);

        if ($result && $row = mysqli_fetch_assoc($result)) {
            $hashedPassword = md5(md5($password) . $row['salt']);
            if ($hashedPassword === $row['password']) {
                $_SESSION["name"] = $row['username'];
                $response["success"] = true;
            } else {
                $response["errors"]["password"] = "Неверный пароль.";
            }
        } else {
            $response["errors"]["email"] = "Пользователь с данной почтой не зарегистрирован.";
        }
    }

    echo json_encode($response);
    exit();
}
