<?php
session_start();
include 'db.php';

// Проверка, что сессия активна и пользователь авторизован
if (!isset($_SESSION['name'])) {
    header("Location: registration/login.php");  // Перенаправление на страницу входа, если пользователь не авторизован
    exit();
}

// Получаем ID выбранного номера из GET-запроса
$room_id = isset($_GET['room_id']) ? (int) $_GET['room_id'] : 0;


// Получаем информацию о номере из базы данных
$query = "SELECT * FROM rooms WHERE id = $room_id";
$result = mysqli_query($conn, $query);
if ($result && $room = mysqli_fetch_assoc($result)) {
    // Получаем данные о номере
    $description = $room['description'];
    $photo_path = $room['photo_path'];
    $price_per_night = $room['price'];
} else {
    echo "Номер не найден!";
    exit();
}

// Получаем список забронированных дат для выбранного номера
$query = "SELECT check_in_date, check_out_date FROM reservations WHERE room_id = $room_id";
$reservations = mysqli_query($conn, $query);
$unavailable_dates = [];
while ($row = mysqli_fetch_assoc($reservations)) {
    // Получаем все даты между датами заезда и выезда
    $start = new DateTime($row['check_in_date']);
    $end = new DateTime($row['check_out_date']);
    $interval = DateInterval::createFromDateString('1 day');
    $daterange = new DatePeriod($start, $interval, $end);

    foreach ($daterange as $date) {
        $unavailable_dates[] = $date->format("Y-m-d");
    }
}

// Получаем данные пользователя из сессии
$user_name = $_SESSION['name'];
$query = "SELECT id FROM users WHERE username = '$user_name'";
$result = mysqli_query($conn, $query);

if ($result && $row = mysqli_fetch_assoc($result)) {
    // Получаем id пользователя
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
</head>

<body>

    <body>
        <header>
            <?php include 'menu.php'; ?>
        </header>
        <div class="booking-container">
            <h1>Бронирование номера: <?php echo $description; ?></h1>

            <div class="room-details">
                <img src="<?php echo $photo_path; ?>" alt="Фото номера" class="room-image">
                <p>Цена за ночь: <?php echo number_format($price_per_night, 2); ?> руб.</p>
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
                    <div class="user-data">
                        <p>ФИО: <?php echo $user['username']; ?></p>
                        <p>Email: <?php echo $user['email']; ?></p>
                        <p>Телефон: <?php echo $user['phone']; ?></p>
                    </div>
                </div>

                <button type="submit" class="submit-btn">Забронировать</button>
            </form>
        </div>

        <script>
            // Блокировка занятых дат в календаре
            const unavailableDates = <?php echo json_encode($unavailable_dates); ?>;
            const checkIn = document.getElementById('check_in_date');
            const checkOut = document.getElementById('check_out_date');

            // Функция блокировки занятых дат
            function blockUnavailableDates() {
                const allInputs = document.querySelectorAll('input[type="date"]');
                allInputs.forEach(input => {
                    input.addEventListener('focus', function () {
                        const inputId = input.id;
                        input.addEventListener('change', function () {
                            if (inputId === 'check_in_date') {
                                // Обновить доступность дат для выезда
                                checkOut.setAttribute('min', this.value);
                            }
                        });
                    });
                });
            }
            blockUnavailableDates();
        </script>
    </body>

</html>