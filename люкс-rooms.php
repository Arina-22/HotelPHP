<?php
require 'db.php';
// Запрос для получения номеров одного типа
$sql = "SELECT * FROM rooms where room_type_id=3";
$result = $conn->query($sql);

$rooms = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $rooms[] = $row;
    }
}

$conn->close();
require 'base-rooms.php';
?>

<head>
    <link rel="stylesheet" href="css/lux-rooms.css">
</head>