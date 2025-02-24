<?php
session_start();
include 'db.php';

$room_id = $_POST['room_id'];
$user_id = $_POST['user_id'];
$commentary = $_POST['review'];
$rating = $_POST['rating'];

$query = "INSERT INTO reviews (user_id, room_id, commentary, rating) VALUES ('$user_id', '$room_id', '$commentary', '$rating')";

if (mysqli_query($conn, $query)) {
    echo "<script>alert('Спасибо за ваш отзыв!'); window.location.href = 'reviews.php';</script>";
} else {
    echo "<script>alert('Ошибка.'); window.location.href = 'user-page.php';</script>";
}
mysqli_close($conn);
?>