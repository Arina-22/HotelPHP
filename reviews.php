<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/menu.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/reviews.css">
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
        <section class="gallery">
            <h2>Отзывы наших гостей</h2>
            <p>Мы ценим каждое ваше мнение и стремимся сделать ваше пребывание в нашем отеле максимально комфортным. Вот
                что говорят наши гости о своем опыте проживания.</p>
        </section>

        <div class="reviews">
            <div class="review">
                <img src="images/5star.svg" alt="rating">
                <p>Прекрасный отель в центре Минска! Уютные номера, отличный сервис и дружелюбный персонал.
                    Особенно понравился завтрак!</p>
                <h3>Анна Петрова</h3>
            </div>
            <div class="review">
                <img src="images/5star.svg" alt="rating">
                <p>Люкс превзошел все наши ожидания! Великолепный вид из окна и отличное качество обслуживания.
                    Обязательно вернемся снова!</p>
                <h3>Елена Ковалёва</h3>
            </div>
            <div class="review">
                <img src="images/5star.svg" alt="rating">
                <p>Останавливались в семейном номере. Просторно и комфортно, наши дети были в восторге! Также удобно,
                    что можно было взять с собой питомца.</p>
                <h3>Игорь Смирнов</h3>
            </div>
        </div>


        <?php include 'footer.php'; ?>
    </main>
</body>

</html>