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
                            <input type="number" id="guests" name="guests" min="1" max="4" value="2" required>
                        </div>
                        <div class="form-group">
                            <div class="room-checkboxes">
                                <div>
                                    <input type="checkbox" id="standard" name="standard" />
                                    <label for="standard">Стандартный</label>
                                </div>
                                <div>
                                    <input type="checkbox" id="family" name="family" />
                                    <label for="family">Семейный</label>
                                </div>
                                <div>
                                    <input type="checkbox" id="lux" name="lux" />
                                    <label for="lux">Люкс</label>
                                </div>
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
                    <button type="button"><a href="rooms.php">Номера</a></button>
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
                    <a href="семейный-rooms.php" class="room-card">
                        <img src="images/family.svg" alt="icon">
                        <h3>Семейный</h3>
                        <p>Идеальные для отдыха с детьми, наши семейные номера обеспечивают пространство и удобства для
                            всей семьи.</p>
                    </a>
                    <a href="стандарт-rooms.php" class="room-card">
                        <img src="images/standard.svg" alt="icon">
                        <h3>Стандартный</h3>
                        <p>Насладитесь роскошью в наших люксовых номерах с непревзойденным комфортом и стилем.</p>
                    </a>
                    <a href="люкс-rooms.php" class="room-card">
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

    <script>
        document.getElementById('room-toggle').onclick = function (event) {
            event.preventDefault();
            const roomTypesList = document.getElementById('room-types');
            roomTypesList.style.display = roomTypesList.style.display === 'none' ? 'block' : 'none';
        };    
    </script>
</body>

</html>