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
		<link rel="stylesheet" href="styles/style.css">
	</head>
	<body>
		<div class="header">
			<div class="nav container">
				<a href="index.php" class="logo">Конференции.РФ</a>
				<div class="nav-btn ">
					<a href="profile.php" class="btn btn-outline">Личный кабинет</a>
					<a href="create.php" class="btn btn-primary">Забранировать</a>
					<a href="?index=1" name="index" class="btn btn-outline">Выход</a>
				</div>
			</div>
		</div>
		<div class="container form-container">
        <form method="POST" class="booking-form">
            <h2>Бронирование помещения</h2>
            <?php if (isset($error)): ?>
                <div class="alert alert-error"><?= $error ?></div>
            <?php endif; ?>
            <div class="form-group">
                <label for="room_type">Тип помещения</label>
                <select id="room_type" name="room_type" required>
                    <option value="Аудитория">Аудитория</option>
                    <option value="Коворкинг">Коворкинг</option>
                    <option value="Кинозал">Кинозал</option>
                </select>
            </div>
            <div class="form-group">
                <label for="date">Дата начала конференции</label>
                <input type="datetime-local" id="date" name="date" required>
            </div>
            <div class="form-group">
                <label for="payment">Способ оплаты</label>
                <select id="payment" name="payment" required>
                    <option value="Наличными в кассу">Наличными в кассу</option>
                    <option value="Безлимитный перевод по счёту">Безлимитный перевод по счёту</option>
                    <option value="Онлайн банковсткой карты">Онлайн банковсткой карты</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Отправить заявку</button>
        </form>
    </div>
	</body>
</html>