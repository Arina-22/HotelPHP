<?php
require 'db.php';

$sql = 'SELECT * FROM room_types';
$result = $conn->query($sql);

$room_info = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $room_info[] = [
            'id' => $row['id'],
            'type' => $row['type'],
        ];
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
            <a href="rooms.php?type=all">Номера <img src="images/arrow.svg" alt="arrow" id="room-toggle"></a>
            <ul id="room-types" style="display: none;">
                <?php foreach ($room_info as $room): ?>
                    <li>
                        <a href="rooms.php?type=<?= $room['id'] ?>">
                            <?= $room['type'] ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </li>
        <li><a href="reviews.php">Отзывы</a></li>
    </ul>

    <div class="user-icon">
        <a href="registration/body.php">
            <img src="images/icon-user.svg" alt="Иконка пользователя" />
        </a>
    </div>
</nav>
<script>
    document.getElementById('room-toggle').onclick = function (event) {
        event.preventDefault();
        const roomTypesList = document.getElementById('room-types');
        roomTypesList.style.display = roomTypesList.style.display === 'none' ? 'block' : 'none';
    };    
</script>