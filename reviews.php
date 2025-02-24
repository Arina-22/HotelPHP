<?php 
session_start();
include 'db.php';

// Запрос для получения всех отзывов
$query = "SELECT r.*, u.username, rm.description AS description 
          FROM reviews r 
          JOIN users u ON r.user_id = u.id 
          JOIN rooms rm ON r.room_id = rm.id 
          ORDER BY r.rating DESC";
$result = $conn->query($query);

$reviews = [];
if ($result->num_rows > 0) {
    while ($review = $result->fetch_assoc()) {
        $reviews[] = $review;
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
            <?php foreach ($reviews as $review): ?>
            <div class="review">
                <h3 class="description"><?php echo $review['description']; ?></h3>
                <div class="rating">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <?php if ($i <= $review['rating']): ?>
                            <span>★</span>
                        <?php else: ?>
                            <span>☆</span>
                        <?php endif; ?>
                    <?php endfor; ?>
                </div>
                <p><?php echo $review['commentary']; ?></p>
                <p style="color:rgb(113, 113, 113)"><?php echo $review['username']; ?></p>
            </div>
            <?php endforeach; ?>
        </div>
        <?php include 'footer.php'; ?>
    </main>
</body>

</html>