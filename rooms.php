<?php
require 'db.php';

// Инициализация переменных для фильтрации
$room_types = isset($_POST['room_type']) ? $_POST['room_type'] : [];
$prices = isset($_POST['price']) ? $_POST['price'] : [];
$guests = isset($_POST['guests']) ? $_POST['guests'] : [];
$beds = isset($_POST['beds']) ? $_POST['beds'] : [];
$pets_allowed = isset($_POST['pets_allowed']) ? 1 : 0;

// Начинаем формировать запрос
$sql = "SELECT * FROM rooms WHERE 1=1";

// Фильтрация по типу номера
if (!empty($room_types)) {
    $types = "'" . implode("','", $room_types) . "'";
    $sql .= " AND type IN ($types)";
}

// Фильтрация по цене
if (!empty($prices)) {
    foreach ($prices as $price) {
        if ($price == 'up_to_300') {
            $sql .= " AND price <= 300";
        } elseif ($price == 'up_to_450') {
            $sql .= " AND price <= 450";
        } elseif ($price == 'up_to_600') {
            $sql .= " AND price <= 600";
        }
    }
}

// Фильтрация по количеству гостей
if (!empty($guests)) {
    $guests_conditions = "'" . implode("','", $guests) . "'";
    $sql .= " AND capacity IN ($guests_conditions)";
}

// Фильтрация по количеству кроватей
if (!empty($beds)) {
    $beds_conditions = "'" . implode("','", $beds) . "'";
    $sql .= " AND beds IN ($beds_conditions)";
}


// Фильтрация по животным
if ($pets_allowed) {
    $sql .= " AND pets_allowed = 1";
}

// Выполнение запроса
$result = $conn->query($sql);

$rooms = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $rooms[] = $row;
    }
}






// Запрос для получения номеров (протое отображение всех номеров списоком)
$sql = "SELECT * FROM rooms ORDER BY room_type_id, price";
$result = $conn->query($sql);

$rooms = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $rooms[] = $row;
    }
}
$conn->close();
?>


<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/rooms.css">
    <link rel="stylesheet" href="css/menu.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
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
            <h1>Номера в SH<span>A</span>RM</h1>
        </section>

        <section class="filter">
            <h2>Фильтровать:</h2>
            <form id="filter-form" method="POST" action="filter_rooms.php">
                <div class="filter-options">
                    <div class="filter-group">
                        <h3>Тип номера</h3>
                        <label><input type="checkbox" name="room_type[]" value="standard"> Стандарт</label>
                        <label><input type="checkbox" name="room_type[]" value="family"> Семейный</label>
                        <label><input type="checkbox" name="room_type[]" value="luxury"> Люкс</label>
                    </div>
                    <div class="filter-group">
                        <h3>Стоимость</h3>
                        <label><input type="checkbox" name="price[]" value="up_to_300"> До 300 BYN/ночь</label>
                        <label><input type="checkbox" name="price[]" value="up_to_450"> До 450 BYN/ночь</label>
                        <label><input type="checkbox" name="price[]" value="up_to_600"> До 600 BYN/ночь</label>
                    </div>
                    <div class="filter-group">
                        <h3>Гости</h3>
                        <label><input type="checkbox" name="guests[]" value="2_guests"> 2</label>
                        <label><input type="checkbox" name="guests[]" value="3_guests"> 3</label>
                        <label><input type="checkbox" name="guests[]" value="4_guests"> 4</label>
                    </div>
                    <div class="filter-group">
                        <h3>Кровати</h3>
                        <label><input type="checkbox" name="beds[]" value="1_bed"> 1</label>
                        <label><input type="checkbox" name="beds[]" value="2_beds"> 2</label>
                    </div>
                    <div class="filter-group">
                        <h3>С животными</h3>
                        <label><input type="checkbox" name="pets_allowed" value="1"> Можно с животными</label>
                    </div>
                </div>
                <button type="submit">Применить фильтры</button>
            </form>
        </section>

        <section class="rooms">
            <div class="room-cards">
                <?php foreach ($rooms as $room): ?>
                    <div class="room-card">
                        <div class="room-name">
                            <h3><?php echo $room['description']; ?></h3>
                            <img src="<?php echo $room['photo_path']; ?>" alt="<?php echo $room['description']; ?>">
                        </div>
                        <div class="room-details">
                            <div class="one-room-details">
                                <img src="images/money.svg" alt="people">
                                <p>Стоимость: <strong><?php echo $room['price']; ?> BYN/ночь</strong></p>
                            </div>

                            <div class="one-room-details">
                                <img src="images/people.svg" alt="people">
                                <p>Вместимость: <?php echo $room['capacity']; ?> гостей</p>
                            </div>

                            <div class="one-room-details">
                                <img src="images/bed.svg" alt="people">
                                <p>Кровати: <?php echo $room['bed_count']; ?></p>
                            </div>

                            <div class="one-room-details">
                                <img src="images/pet.svg" alt="people">
                                <p>С животными: <?php echo $room['pet_friendly'] ? 'Да' : 'Нет'; ?></p>
                            </div>
                            <button type="button">Забронировать</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="gallery">
            <h2>Мы ждем тебя!</h2>
            <p>Выберите свой комфорт! Наш отель располагает разнообразными номерами.</p>
            </div>
        </section>
        <?php include 'footer.php'; ?>
    </main>

    <script>
        document.getElementById('room-toggle').onclick = function (event) {
            // event.preventDefault();
            const roomTypesList = document.getElementById('room-types');
            roomTypesList.style.display = roomTypesList.style.display === 'none' ? 'block' : 'none';
        };    
    </script>
</body>

</html>