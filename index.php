<?php
require 'db.php';

$sql = 'SELECT * FROM room_types';
$result = $conn->query($sql);

$room_info = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $room_info[] = [
            'id' => $row['id'],
            'type' => $row['type'],
        ];
    }
}


// поиск максимальной вместимости
$sql_max_capacity = "SELECT MAX(capacity) AS max_capacity FROM rooms";
$result_max = $conn->query($sql_max_capacity);
$row = $result_max->fetch_assoc();
$max_capacity = intval($row['max_capacity']);

$conn->close();
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/menu.css">
    <link rel="stylesheet" href="css/footer.css">
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
        <section class="hero">
            <h1>Ваш комфортный отдых только в SH <span>A</span> RM.</h1>
            <p>отель в Минске</p>
            <div class="booking-form">
                <form action="#" method="POST">
                    <div>
                        <div class="form-group">
                            <label for="check-in">Заезд</label>
                            <input type="date" id="check-in" name="check-in" required>
                        </div>
                        <div class="form-group">
                            <label for="check-out">Выезд</label>
                            <input type="date" id="check-out" name="check-out" required>
                        </div>
                        <div class="form-group">
                            <label for="guests">Гостей</label>
                            <input type="number" id="guests" name="guests" min="1" max="<?php echo $max_capacity; ?>"
                                value="2" required>
                        </div>
                        <div class="form-group">
                            <div class="room-checkboxes">
                                <?php foreach ($room_info as $room): ?>
                                    <label><input type="checkbox" name="room_type[]" value="<?= $room['id'] ?>">
                                        <?= $room['type'] ?></label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <button type="submit">Найти номер</button>
                </form>
            </div>
        </section>

        <section class="intro">
            <div class="container">
                <p>Добро пожаловать в наш уютный отель в самом сердце Минска! Мы предлагаем нашим гостям комфорт
                    и высококачественный сервис. <br><br>
                    Наши стандартные номера подходят для гостей, ищущих комфорт по отличной цене. Семейные номера
                    идеальны для отдыха с детьми. Насладитесь невероятным комфортом и стилем в люксовых номерах.
                    Мы любим животных и рады приветствовать ваших пушистых друзей.</p>
                <div>
                    <p>Дружелюбный персонал всегда готов помочь вам с любыми вопросами и сделать всё возможное, чтобы
                        ваше пребывание было комфортным.</p>
                </div>
            </div>
        </section>

        <section class="weekend">
            <h1>Проведите свои выходные в SH<span>A</span>RM.</h1>
            <div>
                <p>Добро пожаловать!<br>Приходите к нам и откройте для себя уют и комфорт в сердце Минска!</p>
            </div>
        </section>

        <section class="room-types">
            <div class="container">
                <h2>Номера</h2>
                <p>Выберите свой комфорт! Наш отель располагает разнообразными номерами, среди которых:</p>
                <div class="room-cards">
                    <?php $room['id'] = 2 ?>
                    <a href="rooms.php?type=<?= $room['id'] ?>" class="room-card">
                        <img src="images/family.svg" alt="icon">
                        <h3>Семейный</h3>
                        <p>Идеальные для отдыха с детьми, наши семейные номера обеспечивают пространство и удобства для
                            всей семьи.</p>
                    </a>
                    <?php $room['id'] = 1 ?>
                    <a href="rooms.php?type=<?= $room['id'] ?>" class="room-card">
                        <img src="images/standard.svg" alt="icon">
                        <h3>Стандартный</h3>
                        <p>Насладитесь роскошью в наших люксовых номерах с непревзойденным комфортом и стилем.</p>
                    </a>
                    <?php $room['id'] = 3 ?>
                    <a href="rooms.php?type=<?= $room['id'] ?>" class="room-card">
                        <img src="images/lux.svg" alt="icon">
                        <h3>Люкс</h3>
                        <p>Уютные и функциональные, стандартные номера идеально подходят для гостей, ищущих комфорт
                            по разумной цене.</p>
                    </a>
                </div>
            </div>
        </section>

        <section class="gallery">
            <div class="container">
                <div class="gallery-grid">
                    <div class="gallery-item">
                        <img src="images/image.jpg" alt="Photo">
                    </div>
                    <div class="gallery-item">
                        <img src="images/image-1.jpg" alt="Photo">
                    </div>
                    <div class="gallery-item">
                        <img src="images/image-2.jpg" alt="Photo">
                    </div>
                    <div class="gallery-item">
                        <img src="images/image-3.jpg" alt="Photo">
                    </div>
                    <div class="gallery-item">
                        <img src="images/image-4.jpg" alt="Photo">
                    </div>
                    <div class="gallery-item">
                        <img src="images/image-5.jpg" alt="Photo">
                    </div>
                </div>
                <h2>Мы ждем тебя!</h2>
                <p>Выберите свой комфорт! Наш отель располагает разнообразными номерами.</p>
            </div>
        </section>
        <?php include 'footer.php'; ?>
    </main>
</body>

</html>