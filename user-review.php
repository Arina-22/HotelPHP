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
            <h2>Оставьте свой отзыв</h2>
            <div class="review-container">
                <form id="review-form" method="POST" action="user-review.php">
                    <div class="sub-form-group">
                        <div class="form-group">
                            <label for="name"><span>*</span>Имя</label>
                            <input type="text" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="email">E-mail</label>
                            <input type="email" id="email" name="email">
                        </div>
                    </div>

                    <div class="form-group review-form-group">
                        <label for="review"><span>*</span>Отзыв</label>
                        <textarea id="review" name="review" rows="4" required></textarea>
                    </div>
                    <div class="form-group rating">
                        <label><span>*</span>Рейтинг</label>
                        <span class="stars">
                            <input type="radio" id="star1" name="rating" value="1" required>
                            <label for="star1">★</label>
                            <input type="radio" id="star2" name="rating" value="2">
                            <label for="star2">★</label>
                            <input type="radio" id="star3" name="rating" value="3">
                            <label for="star3">★</label>
                            <input type="radio" id="star4" name="rating" value="4">
                            <label for="star4">★</label>
                            <input type="radio" id="star5" name="rating" value="5">
                            <label for="star5">★</label>
                        </span>
                    </div>
                    <button type="submit">Отправить отзыв</button>
                </form>
                <div class="review-image">
                    <img src="images/review.jpg" alt="Room">
                </div>
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