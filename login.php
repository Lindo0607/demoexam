<?php
session_start();
include('db.php');
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login =  $_POST['login'];
    $password = $_POST['password'];
    $stmt = $con->prepare("SELECT * FROM users WHERE login = ?");
    $stmt->bind_param("s", $login);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    if ($user && $password === $user['password']) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['admin'] = ($user['login'] === 'Admin26');
        header('Location: index.php');
        exit;
    } else {
        $error = 'Неверный логин или пароль.';
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Вход - Конференции.РФ</title>
		<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
	</head>
	<body>
		<div>
			<div>
				<a href="index.php" >Конференции.РФ</a>
				<div>
					<a href="login.php" >Вход</a>
					<a href="register.php">Регистрация</a>
				</div>
			</div>
		</div>
		<div >
        <form method="POST" >
            <h2>Авторизация</h2>
            <?php if ($error): ?>
                <div ><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <div >
                <label for="login">Логин</label>
                <input type="text" id="login" name="login" required>
            </div>
            <div >
                <label for="password">Пароль</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" >Войти</button>
            <p >Еще не зарегистрированы? <a href="register.php">Регистрация</a></p>
        </form>
    </div>
	</body>
</html>

