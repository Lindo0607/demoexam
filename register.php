<?php
session_start();
include('db.php');
$errors = [];
$form_data = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $form_data = [
        'login' => trim($_POST['login']),
        'fullname' => trim($_POST['fullname']),
        'phone' => trim($_POST['phone']),
        'email' => trim($_POST['email'])
    ];
    $password = $_POST['password'];
    if (!preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,}$/', $form_data['login'])) {
        $errors['login'] = 'Логин должен содержать минимум 6 символов, латинские буквы и цифры.';
    }
    if (strlen($password) < 8) {
        $errors['password'] = 'Пароль должен содержать минимум 8 символов.';
    }
    if (empty($form_data['fullname'])) {
        $errors['fullname'] = 'Введите ФИО.';
    }
    if (!preg_match('/^\+7\(\d{3}\)\d{3}-\d{2}-\d{2}$/', $form_data['phone'])) {
        $errors['phone'] = 'Введите корректный номер телефона.';
    }
    if (!filter_var($form_data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Введите корректный email.';
    }
    if (empty($errors)) {
        $check_stmt = $con->prepare("SELECT id FROM users WHERE login = ?");
        $check_stmt->bind_param("s", $form_data['login']);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        if ($check_result->num_rows > 0) {
            $errors['login'] = 'Этот логин уже занят.';
        }
        $check_stmt->close();
    }
    if (empty($errors)) {
        $stmt = $con->prepare("INSERT INTO users (login, password, fullname, phone, email) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $form_data['login'], $password, $form_data['fullname'], $form_data['phone'], $form_data['email']);
        if ($stmt->execute()) {
            header('Location: login.php?registered=1');
            exit;
        } else {
            $errors['general'] = 'Ошибка регистрации. Попробуйте позже.';
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Регистрация - Конференции.РФ</title>
		<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
		<link rel="stylesheet" href="styles/style.css">
	</head>
	<body>
		<div class="header">
			<div class="nav container">
				<a href="index.php" class="logo">Конференции.РФ</a>
				<div class="nav-btn">
					<a href="login.php" class="btn btn-outline">Вход</a>
					<a href="register.php" class="btn btn-primary">Регистрация</a>
				</div>
			</div>
		</div>
		<div class="container form-container">
        <form method="POST" class="auth-form">
            <h2>Регистрация</h2>
            <?php if (isset($errors['general'])): ?>
                <div class="alert alert-error"><?= $errors['general'] ?></div>
            <?php endif; ?>
            <div class="form-group">
                <label for="login">Логин *</label>
                <input type="text" id="login" name="login" value="<?= htmlspecialchars($form_data['login'] ?? '') ?>" required>
                <?php if (isset($errors['login'])): ?>
                    <span class="error-msg"><?= $errors['login'] ?></span>
                <?php endif; ?>
                <small>Латинские буквы и цифры, мин. 6 символов</small>
            </div>
            <div class="form-group">
                <label for="password">Пароль *</label>
                <input type="password" id="password" name="password" required>
                <?php if (isset($errors['password'])): ?>
                    <span class="error-msg"><?= $errors['password'] ?></span>
                <?php endif; ?>
                <small>Минимум 8 символов</small>
            </div>
            <div class="form-group">
                <label for="fullname">ФИО *</label>
                <input type="text" id="fullname" name="fullname" value="<?= htmlspecialchars($form_data['fullname'] ?? '') ?>" required>
                <?php if (isset($errors['fullname'])): ?>
                    <span class="error-msg"><?= $errors['fullname'] ?></span>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label for="phone">Телефон *</label>
                <input type="tel" id="phone" name="phone" value="<?= htmlspecialchars($form_data['phone'] ?? '') ?>" placeholder="+7 (999) 123-45-67" required>
                <?php if (isset($errors['phone'])): ?>
                    <span class="error-msg"><?= $errors['phone'] ?></span>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label for="email">Email *</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($form_data['email'] ?? '') ?>" required>
                <?php if (isset($errors['email'])): ?>
                    <span class="error-msg"><?= $errors['email'] ?></span>
                <?php endif; ?>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Зарегистрироваться</button>
            <p class="form-footer">Уже есть аккаунт? <a href="login.php">Войти</a></p>
        </form>
    </div>
	</body>
</html>
