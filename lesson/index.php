<!DOCTYPE html>
<html lang="ru">
<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: /signin');  
    exit();
}
$db = new SQLite3('../db/db.db');
$user_id = $_SESSION['user_id'];
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
$lesson_id = $_GET['id'];
if ($lesson_id > 0) {
    $stmt = $db->prepare('SELECT lesson_name, description, objectives, steps, homework FROM lessons WHERE lesson_id = :lesson_id');
    $stmt->bindValue(':lesson_id', $lesson_id, SQLITE3_INTEGER);
    $result = $stmt->execute();
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $lesson_name = $row['lesson_name'];
        $description = $row['description'];
        $objectives = $row['objectives'];
        $steps = $row['steps'];
        $homework = $row['homework'];
    }
    $db->close();
} else {
    header('Location: /courses');  
    $db->close();
    exit();
}


$db->close();
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AgatSkill</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="apple-touch-icon" sizes="180x180" href="/fav/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/fav/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/fav/favicon-16x16.png">
    <link rel="manifest" href="/fav/site.webmanifest">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">
    <script>
        const lessonjsid = <?php echo $lesson_id;?>;
    </script>
</head>

<body>
    <?php 
    include('../header.php');
    ?>
    <main class="main_course">
        <div class="main-title">
            <a href="/courses">Просмотр курсов</a><img src="/img/Right Arrow.svg" alt="arrow">
            <p>Курс</p><img src="/img/Right Arrow.svg" alt="arrow">
            <p class="p-web"><?php echo $lesson_name;?></p>
        </div>
        <h1><?php echo $lesson_name;?></h1>
        <h3 class="line_title"><?php echo $lesson_name;?></h3>
        <div class="less">
            <div class="less-block1">
                <h3>Урок: <?php echo $lesson_name;?></h3>
                <h3>Описание урока:</h3>
                <p><?php echo $description;?></p>
                <h3>Цели урока:</h3>
                <p><?php echo $objectives;?></p>
                <h3>Шаги урока:</h3>
                <p><?php echo $steps;?></p>
                <h3>Домашнее задание:</h3>
                <p><?php echo $homework;?></p>
             </div>
            <div class="less-block2">
                <img src="/img/pages.svg" alt="pages">
                <div class="send-less">
                    <div class="send-less_title">Присылать домашнее задание вы можете здесь <img src="/img/arrow down.svg" alt="arrow down"></div>
                    <div class="send-less_con">
                        <div class="nav">
                            <div>
                            <h3>Ответ на урок </h3> 
                            <p></p>
                            </div>
                            <img class="export_btn" src="/img/export.svg" alt="Export">
                        </div>
                        <textarea cols="75" rows="4" placeholder="Написать сообщение..." id="lessoninput"></textarea>
                        <label for="inputfile" class="send-btn drop-area" id="dropArea">
                            <a>выбери файл тут (тыкни)</a>
                        </label>
                        <input type="file" id="inputfile" style="display: none;">
                        <div class="btns">
                            <div class="send-cancel">
                                <button class="btn-send" id="btn-send_lesson"><a  >Отправить</a></button>
                            </div>
                            <!-- <button class="btn-del"><a  class="a-del">Удалить файл</a>
                          <img src="/img/trash.svg" alt="Trash">
                        </button> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <?php 
    include('../footer.php');
    ?>
    <p class="error none"></p>
    <script src="../js/code.jquery-3.6.1.min.js"></script>
    <script src="../js/main.js"></script>
    <script>checkforocenka()</script>
</body>

</html>