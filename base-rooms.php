<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/rooms.css">
    <link rel="stylesheet" href="css/menu.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
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
        <div class="hero">
            <h1>Номера в SH<span>A</span>RM</h1>
        </div>

        <section class="rooms">
            <div class="room-cards">
                <?php foreach ($rooms as $room): ?>
                    <div class="room-card">
                        <div class="room-name">
                            <h3><?php echo $room['description']; ?></h3>
                            <img src="<?php echo $room['photo_path']; ?>" alt="<?php echo $room['description']; ?>">
                        </div>
                        <div class="room-details">
                            <div class="one-room-details">
                                <img src="images/money.svg" alt="people">
                                <p>Стоимость: <strong><?php echo $room['price']; ?> BYN/ночь</strong></p>
                            </div>

                            <div class="one-room-details">
                                <img src="images/people.svg" alt="people">
                                <p>Вместимость: <?php echo $room['capacity']; ?> гостей</p>
                            </div>

                            <div class="one-room-details">
                                <img src="images/bed.svg" alt="people">
                                <p>Кровати: <?php echo $room['bed_count']; ?></p>
                            </div>

                            <div class="one-room-details">
                                <img src="images/pet.svg" alt="people">
                                <p>C животными: <?php echo $room['pet_friendly'] ? 'Да' : 'Нет'; ?></p>
                            </div>
                            <button type="button">Забронировать</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="gallery">
            <h2>Мы ждем тебя!</h2>
            <p>Выберите свой комфорт! Наш отель располагает разнообразными номерами.</p>

        </section>

        <?php include 'footer.php'; ?>
    </main>

    <script>
        document.getElementById('room-toggle').onclick = function (event) {
            event.preventDefault();
            const roomTypesList = document.getElementById('room-types');
            roomTypesList.style.display = roomTypesList.style.display === 'none' ? 'block' : 'none';
        };    
    </script>
</body>

</html>