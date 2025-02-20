<?php
session_start();
include 'db.php';

// Получаем данные из формы
$room_id = $_POST['room_id'];
$check_in_date = $_POST['check_in_date'];
$check_out_date = $_POST['check_out_date'];
$price_per_night = $_POST['price_per_night'];

// Рассчитываем стоимость
$check_in = new DateTime($check_in_date);
$check_out = new DateTime($check_out_date);
$interval = $check_in->diff($check_out);
$days = $interval->days;
$total_price = $days * $price_per_night;

// Получаем user_id 
$user_name = $_SESSION['name'];
$query = "SELECT id FROM users WHERE username = '$user_name'";
    $result = mysqli_query($conn, $query);

    if ($result && $row = mysqli_fetch_assoc($result)) {
        // Получаем id пользователя
        $user_id = $row['id'];
    } 

// Добавляем запись в таблицу reservations
$query = "INSERT INTO reservations (user_id, room_id, check_in_date, check_out_date, total_price) 
          VALUES ($user_id, $room_id, '$check_in_date', '$check_out_date', $total_price)";
if (mysqli_query($conn, $query)) {
    echo "<script>alert('Бронирование успешно!'); window.location.href = 'user-page.php';</script>";
} else {
    echo "<script>alert('Ошибка бронирования. Попробуйте снова.'); window.location.href = 'booking.php?room_id=$room_id';</script>";
}

mysqli_close($conn);
?>