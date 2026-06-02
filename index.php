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
    </head>
    <body>
        <div>
            <div>
                <a href="index.php">Конференции.РФ</a>
                <?php 
                if(!isset($_SESSION['user_id'])){
                    echo'
                    <div>
                        <a href="login.php" >Вход</a>
                        <a href="register.php" >Регистрация</a>
                    </div>
                    ';
                }
                elseif($_SESSION['admin']){                                     
                    echo'
                    <div >
                        <a href="admin.php" >Панель администратора</a>
                        <a href="?index=1" name="index" >Выход</a>
                    </div>
                    ';
                }
                else{
                    echo'
                    <div >
                        <a href="profile.php" >Личный кабинет</a>
                        <a href="create.php" ">Забронировать</a>
                        <a href="?index=1" name="index" >Выход</a>
                    </div>
                    ';
                }
                ?>
            </div>
        </div>
         <div >
        <div>
            <h1>Портал бронирования помещений для конференций</h1>
            <p>Выберите идеальное место для вашего мероприятия: аудитория, коворкинг или кинозал.</p>
        </div>
       
</div>
    </body>
</html>
