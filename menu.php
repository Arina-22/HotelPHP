<?php
require 'db.php';

// Запрос к базе данных
$sql = "SELECT type FROM room_types";
$result = $conn->query($sql);

$room_types = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $room_types[] = $row['type'];
    }
}

$conn->close();
?>

<nav>
    <div class="logo">
        <a href="index.php"><img src="images/logo.svg" alt="Логотип" /></a>
    </div>

    <ul>
        <li>
            <a href="rooms.php">Номера <img src="images/arrow.svg" alt="arrow" id="room-toggle"></a>
            <ul id="room-types" style="display: none;">
                <?php foreach ($room_types as $type): ?>
                    <li>
                        <a href="<?= $type?>-rooms.php">
                            <?= $type ?>
                        </a>                     
                    </li>
                <?php endforeach; ?>
            </ul>
        </li>
        <li><a href="#">Отзывы</a></li>
        <li><a href="#">Контакты</a></li>
    </ul>

    <div class="user-icon">
        <a href="#">
            <img src="images/icon-user.svg" alt="Иконка пользователя" />
        </a>
    </div>
</nav>