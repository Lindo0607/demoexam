<?php
session_start();
include('db.php');
if(isset($_GET['index'])){
	session_destroy();
	header('Location:index.php');
	exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['feedback'])) {
    $feedback = mysqli_real_escape_string($con, $_POST['feedback']);
    $booking_id = (int)$_POST['booking_id'];
    $user_id = $_SESSION['user_id'];
    $stmt = $con->prepare("UPDATE bookings SET feedback = ? WHERE id = ? AND users_id = ? AND status = 'Мероприятие завершено'");
    $stmt->bind_param("sii", $feedback, $booking_id, $user_id);
    $stmt->execute();
    $stmt->close();
    header('Location: profile.php?updated=1');
    exit;
}
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM bookings WHERE users_id = $user_id ORDER BY date DESC";
$result = mysqli_query($con, $query);
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Личный кабинет - Конференции.РФ</title>
		<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
	</head>
	<body>
		<div >
			<div >
				<a href="index.php">Конференции.РФ</a>
				<div >
					<a href="history.php" >Личный кабинет</a>
					<a href="create.php" >Забранировать</a>
					<a href="?index=1" name="index" >Выход</a>
				</div>
			</div>
		</div>
		<div >
        <h1>Мои заявки</h1>
        <?php if (isset($_GET['created'])): ?>
            <div >Заявка успешно создана!</div>
        <?php endif; ?>
        <?php if (isset($_GET['updated'])): ?>
            <div >Отзыв сохранен!</div>
        <?php endif; ?>
        <div >
            <?php if (mysqli_num_rows($result) === 0): ?>
                <div >
                    <p>У вас пока нет заявок на бронирование.</p>
                    <a href="create_booking.php">Создать заявку</a>
                </div>
            <?php else: ?>
                <?php while ($booking = mysqli_fetch_assoc($result)): ?>
                    <div >
                        <div >
                            <span class="status status-<?= str_replace(' ', '-', strtolower($booking['status'])) ?>">
                                <?= htmlspecialchars($booking['status']) ?>
                            </span>
                            <span>#<?= $booking['id'] ?></span>
                        </div>
                        <div >
                            <h3><?= htmlspecialchars($booking['room_type']) ?></h3>
                            <p><strong>Дата:</strong> <?= date('d.m.Y', strtotime($booking['date'])) ?></p>
                            <p><strong>Оплата:</strong> <?= htmlspecialchars($booking['payment']) ?></p>
                            
                            <?php if ($booking['status'] === 'Мероприятие завершено'): ?>
                                <form method="POST" >
                                    <input type="hidden" name="booking_id" value="<?= $booking['id'] ?>">
                                    <div>
                                        <label for="feedback_<?= $booking['id'] ?>">Ваш отзыв:</label>
                                        <textarea name="feedback" id="feedback_<?= $booking['id'] ?>" rows="3"><?= htmlspecialchars($booking['feedback'] ?? '') ?></textarea>
                                    </div>
                                    <button type="submit" >Сохранить отзыв</button>
                                </form>
                            <?php elseif ($booking['feedback']): ?>
                                <div >
                                    <strong>Отзыв:</strong>
                                    <p><?= nl2br(htmlspecialchars($booking['feedback'])) ?></p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>
	</div>
	</body>
</html>
	




