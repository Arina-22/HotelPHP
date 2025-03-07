<?php
session_start();
include 'db.php';

// Проверка, что пользователь авторизован
if (!isset($_SESSION['name'])) {
    header("Location: registration/login.php");
    exit();
}

// Получаем ID выбранного номера из GET-запроса
if (isset($_GET['room_id'])) {
    $room_id = (int) $_GET['room_id'];
}

// Получаем информацию о номере из базы данных
$query = "SELECT * FROM rooms WHERE id = $room_id";
$result = mysqli_query($conn, $query);
if ($result && $room = mysqli_fetch_assoc($result)) {
    $description = $room['description'];
    $photo_path = $room['photo_path'];
    $price_per_night = $room['price'];
} else {
    exit("Ошибка: номер не найден.");
}

// список забронированных дат для выбранного номера
$query = "SELECT DISTINCT check_in_date, check_out_date FROM reservations WHERE room_id = $room_id ORDER BY check_in_date ASC";
$reservations = mysqli_query($conn, $query);
$unavailable_dates = [];

while ($row = mysqli_fetch_assoc($reservations)) {
    $start = new DateTime($row['check_in_date']);
    $end = new DateTime($row['check_out_date']);
    $interval = new DateInterval('P1D');

    // Добавляем даты от заезда до выезда включительно
    while ($start <= $end) {
        $unavailable_dates[] = $start->format("Y-m-d");
        $start->add($interval); // Переход к следующему дню
    }
}

// данные пользователя из сессии
$user_name = $_SESSION['name'];
$query = "SELECT id FROM users WHERE username = '$user_name'";
$result = mysqli_query($conn, $query);

if ($result && $row = mysqli_fetch_assoc($result)) {
    $user_id = $row['id'];
}
$query = "SELECT * FROM users WHERE id = $user_id";
$user_result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($user_result);

?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SHARM</title>
    <link rel="stylesheet" href="css/menu.css">
    <link rel="stylesheet" href="css/booking.css">
    <link rel="stylesheet" href="css/flatpickr.css">
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css"> -->
    <script src="flatpickr.js"></script>
</head>

<body>
    <header>
        <?php include 'menu.php'; ?>
    </header>
    <div class="booking-container">
        <h1>Бронирование номера: <?php echo $description; ?></h1>

        <div class="room-details">
            <img src="<?php echo $photo_path; ?>" alt="номер" class="room-image">
            <p>Цена за ночь: <?php echo $price_per_night; ?> BYN</p>
        </div>

        <h3>Выберите даты для бронирования</h3>
        <form action="process-booking.php" method="POST" class="booking-form">
            <input type="hidden" name="room_id" value="<?php echo $room_id; ?>">
            <input type="hidden" name="price_per_night" value="<?php echo $price_per_night; ?>">

            <label for="check_in_date">Дата заезда:</label>
            <input type="date" id="check_in_date" name="check_in_date" required min="<?php echo date('Y-m-d'); ?>">

            <label for="check_out_date">Дата выезда:</label>
            <input type="date" id="check_out_date" name="check_out_date" required
                min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>">

            <div class="user-info">
                <h3>Данные пользователя</h3>
                <d class="user-data">
                    <p>ФИО: <?php echo $user['username']; ?></p>
                    <label>Email:</label>
                    <input type="email" value=<?php echo $user['email']; ?>>
                    <label>Телефон:</label>
                    <input type="phone" name="phone" id="phone" value=<?php echo $user['phone']; ?>>
            </div>
            <button type="submit" class="submit-btn">Забронировать</button>
        </form>
    </div>

    <script>
        let unavailableDates = <?php echo json_encode($unavailable_dates); ?>;
        document.addEventListener("DOMContentLoaded", function () {
            let disabledDates = unavailableDates.map(date => new Date(date));

            flatpickr("#check_in_date", {
                dateFormat: "Y-m-d",
                minDate: "today",
                disable: disabledDates,
            });

            flatpickr("#check_out_date", {
                dateFormat: "Y-m-d",
                minDate: new Date().fp_incr(1),
                disable: disabledDates,
            });
        });
    </script>
</body>
</html>