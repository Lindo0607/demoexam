<?php
include('db.php');
session_start();
if(isset($_GET['index'])){
	session_destroy();
	header('Location:index.php');
	exit;
}
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    die('Доступ запрещен. Только для администратора.');
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['booking_id'])) {
    $booking_id = (int)$_POST['booking_id'];
    $status = mysqli_real_escape_string($con, $_POST['status']);
    $stmt = $con->prepare("UPDATE bookings SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $booking_id);
    $stmt->execute();
    $stmt->close();
    header('Location: admin.php?updated=1');
    exit;
}
$filter_status = $_GET['status'] ?? '';
$sort_by = $_GET['sort'] ?? 'date';
$sort_order = $_GET['order'] ?? 'DESC';

$allowed_sort = ['date', 'id', 'room_type'];
$sort_by = in_array($sort_by, $allowed_sort) ? $sort_by : 'date';
$sort_order = ($sort_order === 'ASC') ? 'ASC' : 'DESC';
$sql = "SELECT b.*, u.login, u.fullname, u.email 
        FROM bookings b 
        JOIN users u ON b.users_id = u.id";
if ($filter_status && in_array($filter_status, ['Новая', 'Мероприятие назначено', 'Мероприятие завершено'])) {
    $sql .= " WHERE b.status = '$filter_status'";
}
$sql .= " ORDER BY b.$sort_by $sort_order";
$result = mysqli_query($con, $sql);
$total_bookings = mysqli_num_rows($result);
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Панель администратора - Конференции.РФ</title>
		<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
	</head>
	<body>
		<div>
			<div>
				<a href="index.php">Конференции.РФ</a>
				<div>
					<a href="admin.php">Панель админа</a>
					<a href="?index=1" name="index">Выход</a>
				</div>
        	</div>
		</div>
		<div>
        <div>
            <h1>Управление заявками</h1>
            <?php if (isset($_GET['updated'])): ?>
                <div id="notification">Статус заявки обновлен!</div>
            <?php endif; ?>
        </div>
        <div>
            <form method="GET">
                <div>
                    <label for="status">Фильтр по статусу:</label>
                    <select name="status" id="status">
                        <option value="">Все заявки</option>
                        <option value="Новая" <?= $filter_status === 'Новая' ? 'selected' : '' ?>>Новая</option>
                        <option value="Мероприятие назначено" <?= $filter_status === 'Мероприятие назначено' ? 'selected' : '' ?>>Мероприятие назначено</option>
                        <option value="Мероприятие завершено" <?= $filter_status === 'Мероприятие завершено' ? 'selected' : '' ?>>Мероприятие завершено</option>
                    </select>
                </div>
                <div>
                    <label for="sort">Сортировка по:</label>
                    <select name="sort" id="sort">
                        <option value="date" <?= $sort_by === 'date' ? 'selected' : '' ?>>Дате</option>
                        <option value="id" <?= $sort_by === 'id' ? 'selected' : '' ?>>ID</option>
                        <option value="room_type" <?= $sort_by === 'room_type' ? 'selected' : '' ?>>Типу помещения</option>
                    </select>
                </div>
                <div>
                    <label for="order">Порядок:</label>
                    <select name="order" id="order">
                        <option value="DESC" <?= $sort_order === 'DESC' ? 'selected' : '' ?>>По убыванию</option>
                        <option value="ASC" <?= $sort_order === 'ASC' ? 'selected' : '' ?>>По возрастанию</option>
                    </select>
                </div>
                <button type="submit">Применить</button>
                <a href="admin.php">Сбросить</a>
            </form>
        </div>

        <div >
            <div>Найдено заявок: <?= $total_bookings ?></div>
            
            <?php if ($total_bookings === 0): ?>
                <div>Заявок не найдено.</div>
            <?php else: ?>
                <?php while ($booking = mysqli_fetch_assoc($result)): ?>
                    <div>
                        <div>
                            <span class="status status-<?= str_replace(' ', '-', strtolower($booking['status'])) ?>">
                                <?= htmlspecialchars($booking['status']) ?>
                            </span>
                            <span >Заявка #<?= $booking['id'] ?></span>
                        </div>
                        <div>
                            <div >
                                <div >
                                    <strong>Пользователь:</strong> <?= htmlspecialchars($booking['fullname']) ?> (@<?= htmlspecialchars($booking['login']) ?>)
                                </div>
                                <div>
                                    <strong>Email:</strong> <?= htmlspecialchars($booking['email']) ?>
                                </div>
                                <div >
                                    <strong>Помещение:</strong> <?= htmlspecialchars($booking['room_type']) ?>
                                </div>
                                <div >
                                    <strong>Дата:</strong> <?= date('d.m.Y', strtotime($booking['date'])) ?>
                                </div>
                                <div>
                                    <strong>Оплата:</strong> <?= htmlspecialchars($booking['payment']) ?>
                                </div>
                                <?php if ($booking['feedback']): ?>
                                    <div >
                                        <strong>Отзыв:</strong> <?= nl2br(htmlspecialchars($booking['feedback'])) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <form method="POST" >
                                <input type="hidden" name="booking_id" value="<?= $booking['id'] ?>">
                                <div >
                                    <label for="status_<?= $booking['id'] ?>">Изменить статус:</label>
                                    <select name="status" id="status_<?= $booking['id'] ?>">
                                        <option value="Новая" <?= $booking['status'] === 'Новая' ? 'selected' : '' ?>>Новая</option>
                                        <option value="Мероприятие назначено" <?= $booking['status'] === 'Мероприятие назначено' ? 'selected' : '' ?>>Мероприятие назначено</option>
                                        <option value="Мероприятие завершено" <?= $booking['status'] === 'Мероприятие завершено' ? 'selected' : '' ?>>Мероприятие завершено</option>
                                    </select>
                                </div>
                                <button type="submit" >Обновить</button>
                            </form>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>
	</div>

    <script>
        // Автоматическое скрытие уведомления через 3 секунды
        setTimeout(() => {
            const notification = document.getElementById('notification');
            if (notification) notification.style.display = 'none';
        }, 3000);
    </script>
	</body>
</html>

