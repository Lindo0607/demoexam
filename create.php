<?php
session_start();
if(isset($_GET['index'])){
	session_destroy();
	header('Location:index.php');
	exit;
}
include('db.php');
if (!isset($_SESSION['user_id'])) {
    die('Чтобы забронировать помещение, необходимо <a href="login.php">войти</a>.');
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $room_type = $_POST['room_type'];
    $date = $_POST['date'];
    $payment = $_POST['payment'];
    $user_id = $_SESSION['user_id'];
    $stmt = $con->prepare("INSERT INTO bookings (room_type, date, payment, users_id) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $room_type, $date, $payment, $user_id);
    if ($stmt->execute()) {
        header('Location: profile.php?created=1');
        exit;
    } else {
        $error = 'Ошибка при создании заявки.';
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Новая заявка - Конференции.РФ</title>
		<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
	</head>
	<body>
		<div >
			<div>
				<a href="index.php">Конференции.РФ</a>
				<div>
					<a href="profile.php" >Личный кабинет</a>
					<a href="create.php" >Забранировать</a>
					<a href="?index=1" name="index" >Выход</a>
				</div>
			</div>
		</div>
		<div >
        <form method="POST" >
            <h2>Бронирование помещения</h2>
            <?php if (isset($error)): ?>
                <div><?= $error ?></div>
            <?php endif; ?>
            <div>
                <label for="room_type">Тип помещения</label>
                <select id="room_type" name="room_type" required>
                    <option value="Аудитория">Аудитория</option>
                    <option value="Коворкинг">Коворкинг</option>
                    <option value="Кинозал">Кинозал</option>
                </select>
            </div>
            <div>
                <label for="date">Дата начала конференции</label>
                <input type="date" id="date" name="date" required>
            </div>
            <div>
                <label for="payment">Способ оплаты</label>
                <select id="payment" name="payment" required>
                    <option value="Наличными в кассу">Наличными в кассу</option>
                    <option value="Безлимитный перевод по счёту">Безлимитный перевод по счёту</option>
                    <option value="Онлайн банковсткой карты">Онлайн банковсткой карты</option>
                </select>
            </div>
            <button type="submit">Отправить заявку</button>
        </form>
    </div>
	</body>
</html>
