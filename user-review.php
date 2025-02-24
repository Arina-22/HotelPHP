<?php 
session_start();
include 'db.php';
// Получаем ID выбранного номера из GET-запроса
if (isset($_GET['room_id'])) {
    $room_id = (int) $_GET['room_id'];
}

// информация о пользователе по ID бронирования
if (isset($_GET['reservation_id'])) {
    $reservation_id = $_GET['reservation_id'];
    $query = "SELECT users.username, users.email, users.id 
              FROM reservations 
              JOIN users ON reservations.user_id = users.id 
              WHERE reservations.id = $reservation_id";
    $result = mysqli_query($conn, $query);

    if ($result && $user = mysqli_fetch_assoc($result)) {
        $user_name = $user['username'];
        $email = $user['email'];
        $user_id = $user['id'];
        // echo $user_name;
    }
}

// Получаем информацию о номере из базы данных
$query = "SELECT * FROM rooms WHERE id = $room_id";
$result = mysqli_query($conn, $query);
if ($result && $room = mysqli_fetch_assoc($result)) {
    $description = $room['description'];
    $photo_path = $room['photo_path'];
} else {
    exit("Ошибка: номер не найден.");
}
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/menu.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/user-review.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="shortcut icon" type="image/svg" href="images/fav.svg">
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Playball&family=WindSong:wght@400;500&display=swap"
        rel="stylesheet">
    <title>SHARM</title>
</head>

<body>
    <header>
        <?php include 'menu.php'; ?>
    </header>
    <main>
        <section class="review-section">
            <h2>Отзыв: <?php echo $description ?></h2>
            <div class="review-container">
                <form id="review-form" method="POST" action="process-review.php">
                    <input type="hidden" name="room_id" value="<?php echo $room_id; ?>">
                    <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                    <div class="sub-form-group">
                        <div class="form-group">
                            <label for="name"><span>*</span>Имя</label>
                            <input type="text" id="name" name="name" required value=<?php echo $user_name?>>
                        </div>
                        <div class="form-group">
                            <label for="email">E-mail</label>
                            <input type="email" id="email" name="email" value=<?php echo $email?>>
                        </div>
                    </div>

                    <div class="form-group review-form-group">
                        <label for="review"><span>*</span>Отзыв</label>
                        <textarea id="review" name="review" rows="4" required></textarea>
                    </div>
                    <div class="form-group rating">
                        <label><span>*</span>Рейтинг</label>
                        <span class="stars">
                            <input type="radio" id="star1" name="rating" value="5" required>
                            <label for="star1">★</label>
                            <input type="radio" id="star2" name="rating" value="4">
                            <label for="star2">★</label>
                            <input type="radio" id="star3" name="rating" value="3">
                            <label for="star3">★</label>
                            <input type="radio" id="star4" name="rating" value="2">
                            <label for="star4">★</label>
                            <input type="radio" id="star5" name="rating" value="1">
                            <label for="star5">★</label>
                        </span>
                    </div>
                    <button type="submit">Отправить отзыв</button>
                </form>
                <div class="review-image">
                    <img src=<?php echo $photo_path?> alt="Room">
                </div>
            </div>
        </section>

        <?php include 'footer.php'; ?>
    </main>
</body>

</html>