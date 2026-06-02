<?php 
session_start();
if(isset($_GET['index'])){
    session_destroy();
    header('Location:index.php');
    exit;}
?>
<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
        <title>Конференции.РФ - Бронирование помещений</title>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="styles/style.css">
    </head>
    <body>
        <div class="header">
            <div class="nav container">
                <a href="index.php" class="logo">Конференции.РФ</a>
                <?php 
                if(!isset($_SESSION['user_id'])){
                    echo'
                    <div class="nav-btn">
                        <a href="login.php" class="btn btn-outline">Вход</a>
                        <a href="register.php" class="btn btn-outline">Регистрация</a>
                    </div>
                    ';
                }
                elseif($_SESSION['admin']){                                     
                    echo'
                    <div class="nav-btn">
                        <a href="admin.php" class="btn btn-outline">Панель администратора</a>
                        <a href="?index=1" name="index" class="btn btn-outline">Выход</a>
                    </div>
                    ';
                }
                else{
                    echo'
                    <div class="nav-btn">
                        <a href="profile.php" class="btn btn-outline">Личный кабинет</a>
                        <a href="create.php" class="btn btn-outline">Забронировать</a>
                        <a href="?index=1" name="index" class="btn btn-outline">Выход</a>
                    </div>
                    ';
                }
                ?>
            </div>
        </div>
         <div class="container">
        <div class="hero">
            <h1>Портал бронирования помещений для конференций</h1>
            <p>Выберите идеальное место для вашего мероприятия: аудитория, коворкинг или кинозал.</p>
        </div>
        <div class="slider-container">
            <div class="slider">
                <div class="slides">
                    <img src="assets/gallery/0.jpg" alt="Конференц-зал" class="slide active">
                    <img src="assets/gallery/1.jpg" alt="Коворкинг" class="slide">
                    <img src="assets/gallery/2.jpg" alt="Аудитория" class="slide">
                    <img src="assets/gallery/3.jpg" alt="Кинозал" class="slide">
                </div>
                <button class="slider-btn prev" data-action="prev">&#10094;</button>
                <button class="slider-btn next" data-action="next">&#10095;</button>
                <div class="dots"></div>
            </div>
        </div>
</div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="js/fun.js"></script>
    </body>
</html>
