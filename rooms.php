<?php
require 'db.php';

// массив пар типов-id:
$type_mapping = [
    'standard' => 1,
    'family' => 2,
    'luxury' => 3
];

// Инициализация переменных для фильтрации
$room_types = isset($_POST['room_type']) ? $_POST['room_type'] : [];
$price = isset($_POST['price']) ? $_POST['price'] : null;
$guests = isset($_POST['guests']) ? intval($_POST['guests']) : 0;
$beds = isset($_POST['beds']) ? $_POST['beds'] : [];
$pets_allowed = isset($_POST['pets_allowed']) ? 1 : 0;

// Инициализация переменных для сортировки
$capacity = isset($_POST['capacity']) ? $_POST['capacity'] : null;
$price_level = isset($_POST['price_level']) ? $_POST['price_level'] : null;

// Начинаем формировать запрос
$sql = "SELECT * FROM rooms WHERE 1=1";

// Получаем ID типов номеров
$room_type_ids = [];
foreach ($room_types as $room_type) {
    $room_type_ids[] = $type_mapping[$room_type];
}

// Фильтрация по типу номера
if (!empty($room_type_ids)) {
    $ids = implode(",", $room_type_ids);
    $sql .= " AND room_type_id IN ($ids)";
}

// Фильтрация по цене
if ($price) {
    if ($price == 'up_to_300') {
        $sql .= " AND price <= 300";
    } elseif ($price == 'up_to_450') {
        $sql .= " AND price <= 450";
    } elseif ($price == 'up_to_600') {
        $sql .= " AND price <= 600";
    }
}

// Фильтрация по кол-ву гостей
if ($guests > 0) {
    $sql .= " AND capacity >= $guests";
}

// Фильтрация по количеству кроватей
if (!empty($beds)) {
    $beds_conditions = "'" . implode("','", $beds) . "'";
    $sql .= " AND bed_count IN ($beds_conditions)";
}

// Фильтрация по животным
if ($pets_allowed) {
    $sql .= " AND pet_friendly = 1";
}

// Сортировка по цене
if ($price_level) {
    $sql .= " ORDER BY price " . ($price_level == 'desc' ? 'DESC' : 'ASC');
}

// Сортировка по вместительности
if ($capacity) {
    $sql .= " , capacity " . ($capacity == 'desc' ? 'DESC' : 'ASC');
}

// Запрос
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
        <div class="hero">
            <h1>Номера в SH<span>A</span>RM</h1>
        </div>

        <div class="filt-sort">
            <div class="filter">
                <h2>Фильтровать:</h2>
                <form id="filter-form" method="POST" action="rooms.php">
                    <div class="filter-options">
                        <div class="filter-group">
                            <h3>Тип номера</h3>
                            <label><input type="checkbox" name="room_type[]" value="standard"> Стандарт</label>
                            <label><input type="checkbox" name="room_type[]" value="family"> Семейный</label>
                            <label><input type="checkbox" name="room_type[]" value="luxury"> Люкс</label>
                        </div>
                        <div class="filter-group">
                            <h3>Стоимость</h3>
                            <label><input type="radio" name="price" value="up_to_300"> До 300 BYN/ночь</label>
                            <label><input type="radio" name="price" value="up_to_450"> До 450 BYN/ночь</label>
                            <label><input type="radio" name="price" value="up_to_600"> До 600 BYN/ночь</label>
                        </div>

                        <div class="filter-group">
                            <h3>Гости</h3>
                            <input type="number" id="guests" name="guests" min="1" max="4" value="2" required>
                        </div>
                        <div class="filter-group">
                            <h3>Кровати</h3>
                            <label><input type="checkbox" name="beds[]" value="1_bed"> 1</label>
                            <label><input type="checkbox" name="beds[]" value="2_beds"> 2</label>
                        </div>
                        <div class="filter-group">
                            <h3>C животными</h3>
                            <label><input type="checkbox" name="pets_allowed" value="1"> Можно c животными</label>
                        </div>
                    </div>
                    <button type="submit">Фильтровать</button>
                </form>
            </div>

            <div class="sort">
                <h2>Сортировать:</h2>
                <form id="filter-form" method="POST" action="rooms.php">
                    <div class="filter-options">
                        <div class="filter-group">
                            <h3>По цене</h3>
                            <label><input type="radio" name="price_level" value="asc"> Сначала
                                бюджетные</label>
                            <label><input type="radio" name="price_level" value="desc"> Сначала
                                дорогие</label>
                        </div>
                        <div class="filter-group">
                            <h3>По вместительности</h3>
                            <label><input type="radio" name="capacity" value="asc"> Сначала
                                небольшие</label>
                            <label><input type="radio" name="capacity" value="desc"> Сначала
                                большие</label>
                        </div>
                    </div>
                    <button type="submit">Сортировать</button>
                </form>
            </div>
        </div>

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
                                <p>C животными: <?php echo $room['pet_friendly'] ? 'Да' : 'Нет'; ?></p>
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