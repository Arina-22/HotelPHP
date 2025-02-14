<?php
require 'db.php';
// Запрос для получения номеров одного типа
$sql = "SELECT * FROM rooms where room_type_id=1";
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