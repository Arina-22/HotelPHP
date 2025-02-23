<?php
session_start();
include 'db.php';

if (isset($_GET['reservation_id'])) {
    $reservation_id = $_GET['reservation_id'];
    $deleteQuery = "DELETE FROM reservations WHERE id = $reservation_id";
    if (mysqli_query($conn, $deleteQuery)) {
        echo "<script>alert('Бронирование отменено!'); window.location.href = 'user-page.php';</script>";
    } else {
        echo "<script>alert('Ошибка при отмене брони.'); window.location.href = 'user-page.php';</script>";
    }

} else {
    header("Location: user-page.php");
    exit();
}
?>