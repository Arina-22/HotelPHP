<?php
session_start();
include 'db.php';

if ($_SESSION["name"] == NULL) {
    header('Location: index.php'); 
    exit(); 
}

// Получаем ID пользователя из БД по его имени в сессии
if (isset($_SESSION["name"])) {
    $username = $_SESSION["name"];
    $userQuery = "SELECT id FROM users WHERE username = '$username'";
    $userResult = mysqli_query($conn, $userQuery);

    if ($userResult && $userRow = mysqli_fetch_assoc($userResult)) {
        $user_id = $userRow['id'];
    }
}

// Запрос на получение бронирований пользователя
$query = "SELECT r.id AS reservation_id, r.room_id, rm.description, rm.photo_path, r.total_price, r.check_in_date, r.check_out_date
          FROM reservations r
          JOIN rooms rm ON r.room_id = rm.id
          WHERE r.user_id = $user_id
          ORDER BY r.check_in_date ASC";

$result = $conn->query($query);

$bookings = [];
if ($result->num_rows > 0) {
    while ($booking = $result->fetch_assoc()) {
        $bookings[] = $booking;
    }
}
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/menu.css">
    <link rel="stylesheet" href="css/user-page.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="shortcut icon" type="image/svg" href="images/fav.svg">
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Playball&family=WindSong:wght@400;500&display=swap"
        rel="stylesheet">
    <script>
        function confirmCancel(reservationId) {
            if (confirm("Точно хотите отменить бронь?")) {
                window.location.href = 'cancel-booking.php?reservation_id=' + reservationId;
            }
        }
    </script>
    <title>SHARM</title>
</head>

<body>
    <header>
        <?php include 'menu.php'; ?>
    </header>
    <main>
        <h2>Добро пожаловать, <?php echo $_SESSION["name"]; ?>!</h2>
        <form action="registration/logout.php" method="post">
            <button type="submit" class="logout-button">Выйти из аккаунта</button>
        </form>

        <h2>Ваши бронирования:</h2>
        <?php if (!empty($bookings)): ?>
            <div class="booking-list">
                <?php foreach ($bookings as $booking): ?>
                    <div class="booking-card">
                        <img src="<?php echo $booking['photo_path']; ?>" alt="Фото">
                        <div class="booking-info">
                            <h3><?php echo $booking['description']; ?></h3>
                            <h3 style="display: none;"><?php echo $booking['room_id']; ?></h3>
                            <p><strong>Дата:</strong> 
                                <?php echo $booking['check_in_date']; ?> - <?php echo $booking['check_out_date']; ?>
                            </p>
                            <p><strong>Общая стоимость:</strong> <?php echo $booking['total_price']; ?> BYN</p>
                        </div>
                        <?php
                        // Получаем текущую дату и дату выезда
                        $currentDate = date('Y-m-d');
                        $checkoutDate = $booking['check_out_date'];
                        ?>
                        <?php if ($currentDate > $checkoutDate): ?>
                            <button class="review-button" onclick="window.location.href='user-review.php?room_id=<?php echo $booking['room_id']; ?>&reservation_id=<?php echo $booking['reservation_id']; ?>'">Оставить отзыв</button>
                        <?php else: ?>
                            <button class="cancel-button" style="background-color:#979797" onclick="confirmCancel(<?php echo $booking['reservation_id']; ?>)">Отменить</button>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="p" style="color: #333333;">У вас нет активных бронирований.</p>
        <?php endif; ?>

        <?php include 'footer.php'; ?>
    </main>
</body>

</html>