<!DOCTYPE html>
<html lang="ru">
<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: /signin');  
    exit();
}
$db = new SQLite3('../db/db.db');
$user_id = $_GET['id'];
$stmt = $db->prepare('SELECT * FROM users WHERE user_id = :user_id');
$stmt->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
$result = $stmt->execute();
$user = $result->fetchArray(SQLITE3_ASSOC);

 
$stmt = $db->prepare('SELECT * FROM users WHERE user_id = :user_id');
$stmt->bindValue(':user_id', $_SESSION['user_id'], SQLITE3_INTEGER);
$result = $stmt->execute();
$mainuser = $result->fetchArray(SQLITE3_ASSOC);

if (!$user) {
    header('Location: /signin');  
    $db->close();
    exit();
}
if ($user['avatar'] == '') {
    $avatar = "/img/avatar.png";$mainavatar = "/img/avatar.png";
} else {
    $avatar = $user['avatar'];
    $mainavatar = $mainuser['avatar'];
}



$db->close();
?>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Личный кабинет</title>
    <script>
        const user_id = '<?php echo $user['user_id'];?>';
        const siteuserid = '<?php echo $user_id;?>';
    </script>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="apple-touch-icon" sizes="180x180" href="/fav/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/fav/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/fav/favicon-16x16.png">
    <link rel="manifest" href="/fav/site.webmanifest">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">
</head>

<body>
    <?php include('../header.php'); ?>
    <main class="main_lk">
        <div class="title">
            <?php
            if ($_SESSION['user_id'] == $user_id) {
                echo "<h2>Мой личный кабинет</h2>";
            }  
            ?>
            
        </div>
        <div class="lich_cab">
            <div class="lk">
            
                <div class="profile">
                    <img src="<?php echo $avatar;?>" class="proflie_photo" alt="proflie_photo" />
                    <h2><?php echo $user['name'] ." ". $user['surname']; ?></h2>
                    <h3><?php echo $user['phone'];?></h3>
                    <div class="id">
                        <h2>ID</h2>
                        <p><?php echo $user['user_id'];?></p>
                    </div>
                </div>
                <?php  if ($mainuser['role'] == 'teacher') {
                echo '<a href="/teacher" class="teacherlk">Перейти в учительский лк<a>';
            }?>
                <div class="subc">
                    <h2>Подписки</h2>
                    
                </div>
            </div>
            <?php
           
            if ($_SESSION['user_id'] == $user_id) { 
                echo '<div class="block2">
                <div class="info">
                    <h2>Изменить данные</h2>
                    <div class="info-con">
                    <h3>Имя</h3>
                    <div class="input_block">
                        <input type="name" id="name">
                        <label for="name">Имя</label>
                    </div>
                    <h3>Фамилия</h3>
                    <div class="input_block">
                        <input type="surname" id="surname">
                        <label for="surname">Фамилия</label>
                    </div>
                    <h3>Почта</h3>
                    <div class="input_block">
                        <input type="email" id="email">
                        <label for="email">Почта</label>
                    </div>
                    <h3>Пароль</h3>
                    <div class="input_block">
                        <input type="password" id="pass">
                        <label for="pass">Пароль</label>
                    </div>
                    <h3>Номер телефона</h3>
                    <div class="input_block">
                        <input type="contact_info" id="tel">
                        <label for="tel">Номер</label>
                    </div>
                </div>
                    <button class="save" id="save_data"><a >Сохранить</a></button>
                </div>
                <div class="dell-acc">
                    <div class="text">
                        <h3>Удаление аккаунта</h3>
                        <p>
                            Внимание! Перед удалением аккаунта убедитесь, что вы сохранили все важные данные и информацию, связанную с этим аккаунтом. Удаление аккаунта приведет к потере всех данных, профиля, сообщений и другой информации, связанной с ним.
                        </p>
                    </div>
                    <button class="dell-btn"><a >Удалить</a></button>
                </div>
                <button class="logout-btn"><img src="/img/User Cross Rounded.svg"> Выйти из аккаунта</button>
            </div>';
            }
            ?>
            
           
        </div>
    </main>
    <div class="error none"></div>
    <?php include('../footer.php'); ?>
    <script src="../js/code.jquery-3.6.1.min.js"></script>
    <script src="../js/main.js"></script>
    <script>
        loadcourses()
    </script>
</body>

</html>