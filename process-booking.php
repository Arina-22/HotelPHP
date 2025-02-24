<?php
session_start();
include 'db.php';

// данные из формы
$room_id = $_POST['room_id'];
$check_in_date = $_POST['check_in_date'];
$check_out_date = $_POST['check_out_date'];
$price_per_night = $_POST['price_per_night'];

$check_in = new DateTime($check_in_date);
$check_out = new DateTime($check_out_date);

if ($check_out < $check_in){
    echo "<script>alert('Дата выезда не может быть раньше даты заезда.'); window.location.href = 'booking.php?room_id=$room_id ?>';</script>";
    exit();
}

if ($check_out = $check_in){
    echo "<script>alert('День выезда не может быть в день заезда.'); window.location.href = 'booking.php?room_id=$room_id ?>';</script>";
    exit();
}

// забронированные даты для выбранного номера
$query = "SELECT check_in_date, check_out_date FROM reservations WHERE room_id = $room_id";
$reservations = mysqli_query($conn, $query);

$is_available = true;

while ($row = mysqli_fetch_assoc($reservations)) {
    $reserved_start = new DateTime($row['check_in_date']);
    $reserved_end = new DateTime($row['check_out_date']);

    // Проверка пересечения интервалов
    if ($check_in < $reserved_end && $check_out > $reserved_start) {
        $is_available = false; // Даты пересекаются
        break;
    }
}

if (!$is_available) {
    echo "<script>alert('Выбранная дата занята, выберите другую дату.'); window.location.href = 'booking.php?room_id=$room_id ?>';</script>";
    exit();
} else {
    // расчет стоимости
    // diff() вычисляет разницу между двумя объектами DateTime. Результатом является объект класса DateInterval
    $interval = $check_in->diff($check_out);
    // свойство days возвращает разницу в днях между двумя датами
    $days = $interval->days;
    $total_price = $days * $price_per_night;

    // user_id из сессии
    $user_name = $_SESSION['name'];
    $query = "SELECT id FROM users WHERE username = '$user_name'";
    $result = mysqli_query($conn, $query);

    if ($result && $row = mysqli_fetch_assoc($result)) {
        $user_id = $row['id'];
    }

    $query = "INSERT INTO reservations (user_id, room_id, check_in_date, check_out_date, total_price) 
          VALUES ($user_id, $room_id, '$check_in_date', '$check_out_date', $total_price)";
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Бронирование успешно!'); window.location.href = 'user-page.php';</script>";
    } else {
        echo "<script>alert('Ошибка бронирования.'); window.location.href = 'booking.php';</script>";
    }
}

mysqli_close($conn);
?>