<?php
require 'db.php';

// поиск максимальной вместимости
$sql_max_capacity = "SELECT MAX(capacity) AS max_capacity FROM rooms";
$result_max = $conn->query($sql_max_capacity);
$row = $result_max->fetch_assoc();
$max_capacity = intval($row['max_capacity']);


// вывод всех типов номеров (пункт фильтрации)
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


// Значения bed_count
$sql_beds = "SELECT DISTINCT bed_count FROM rooms";
$result_beds = $conn->query($sql_beds);
$beds_options = [];
while ($row = $result_beds->fetch_assoc()) {
    $beds_options[] = $row['bed_count'];
}


// вывод номеров

$room_id = $_GET['type'];
$sql = 'SELECT * FROM rooms';

$conditions = []; // для фильтрации
$order_conditions = []; // для сортировки

if ($room_id != 'all') {
    $conditions[] = "room_type_id = " . $room_id;
}

// Инициализация переменных для фильтрации
$room_types = isset($_POST['room_type']) ? $_POST['room_type'] : [];
$price = isset($_POST['price']) ? $_POST['price'] : null;
$guests = isset($_POST['guests']) ? intval($_POST['guests']) : 0;
$beds = isset($_POST['beds']) ? $_POST['beds'] : [];
$pets_allowed = isset($_POST['pets_allowed']) ? 1 : 0;

// Фильтрация по типу номера
if (!empty($room_types)) {
    $ids = implode(",", $room_types);
    $conditions[] = "room_type_id IN ($ids)";
}

// Фильтрация по цене
if ($price) {
    if ($price == 'up_to_300') {
        $conditions[] = "price <= 300";
    } elseif ($price == 'up_to_450') {
        $conditions[] = "price <= 450";
    } elseif ($price == 'up_to_600') {
        $conditions[] = "price <= 600";
    }
}

// Фильтрация по количеству гостей
if ($guests > 0) {
    $conditions[] = "capacity >= " . $guests;
}

// Фильтрация по количеству кроватей
if (!empty($beds)) {
    $beds_conditions = "'" . implode("','", $beds) . "'";
    $conditions[] = "bed_count IN ($beds_conditions)";
}

// Фильтрация по животным
if ($pets_allowed) {
    $conditions[] = "pet_friendly = 1";
}

// Добавление условий фильтрации
if (!empty($conditions)) {
    $sql .= " WHERE " . implode(" AND ", $conditions);
}

// Сортировка по цене
if (isset($_POST['price_level'])) {
    $price_level = $_POST['price_level'];
    $order_conditions[] = "price " . ($price_level == 'desc' ? 'DESC' : 'ASC');
}

// Сортировка по вместимости
if (isset($_POST['capacity'])) {
    $capacity = $_POST['capacity'];
    $order_conditions[] = "capacity " . ($capacity == 'desc' ? 'DESC' : 'ASC');
}

// Добавление условий сортировки
if (!empty($order_conditions)) {
    $sql .= " ORDER BY " . implode(", ", $order_conditions);
}

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
                <form id="filter-form" method="POST">
                    <div class="filter-options">
                        <div class="filter-group">
                            <h3>Тип номера</h3>
                            <?php foreach ($room_info as $room): ?>
                                <label><input type="checkbox" name="room_type[]" value="<?= $room['id'] ?>"> <?= $room['type'] ?></label>                                
                            <?php endforeach; ?>
                        </div>
                        <div class="filter-group">
                            <h3>Стоимость</h3>
                            <label><input type="radio" name="price" value="up_to_300"> До 300 BYN/ночь</label>
                            <label><input type="radio" name="price" value="up_to_450"> До 450 BYN/ночь</label>
                            <label><input type="radio" name="price" value="up_to_600"> До 600 BYN/ночь</label>
                        </div>

                        <div class="filter-group">
                            <h3>Гости</h3>
                            <input type="number" id="guests" name="guests" min="1" max="<?php echo $max_capacity; ?>"
                                value="2" required>
                        </div>
                        <div class="filter-group">
                            <h3>Кровати</h3>
                            <?php foreach ($beds_options as $bed_count): ?>
                                <label>
                                    <input type="checkbox" name="beds[]" value="<?php echo htmlspecialchars($bed_count); ?>"> 
                                    <?php echo $bed_count; ?>
                                </label>
                            <?php endforeach; ?>
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
                <form id="filter-form" method="POST">
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
        <?php if (empty($rooms)): ?>
        <p style = "color:black; text-align: center;">К сожалению, ничего не найдено по вашим критериям фильтрации.</p>
        <?php else: ?>
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
            <?php endif; ?>
        </section>

        <section class="gallery">
            <h2>Мы ждем тебя!</h2>
            <p>Выберите свой комфорт! Наш отель располагает разнообразными номерами.</p>
        </section>

        <?php include 'footer.php'; ?>
    </main>
</body>

</html>