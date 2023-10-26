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

$courses_id = $_GET['id'];
if ($courses_id > 0) {
    $stmt = $db->prepare('SELECT course_name, course_info, course_lessons FROM trainings WHERE courses_id = :courses_id');
    $stmt->bindValue(':courses_id', $courses_id, SQLITE3_INTEGER);
    $result = $stmt->execute();
    $courseName = '';
    $courseInfo = '';
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $courseName = $row['course_name'];
        $courseInfo = $row['course_info'];
        $courseLessons = $row['course_lessons'];
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
        const courseLessons = <?php 
        if ($courseLessons == '') {
            $courseLessons = 0;
        } 
        echo $courseLessons
        ?>;
    </script>
</head>

<body>
    <?php 
    include('../header.php');
    ?>
    <main class="main_course_design-graf">
        <div class="main-title">
            <a href="/courses">Просмотр курсов</a><img src="/img/Right Arrow.svg" alt="arrow">
            <p class="p-web">Курс: <?php   echo $courseName;?></p>
        </div>
        <h1>Курс: <?php   echo $courseName;?></h1>
        <h3 class="line_title"><?php   echo $courseName;?></h3>
        <div class="info">
            <div class="text_info">
                <h2>О чем этот курс?</h2>
                <p><?php   echo $courseInfo;?></p>
            </div>
            <img src="/img/curse_info.svg" alt="curse_info">
        </div>
            <h2>Список доступных уроков</h2>
            <div class="first_list"></div>
        </div>
    </main>
    <footer>
        <div class="footer_con">
            <div class="logo_con">
                <div class="logo">
                    <img src="/img/logo.svg" alt="logo">
                    <p>AgatSkill</p>
                </div>
                <p class="text_under_logo">Знания - ключ к воротам возможностей: открой их с нами!</p>
            </div>
            <div class="info_con">
                <div class="list">
                    <p class="title">О AgatSkill</p>
                    <a href="#">О нас</a>
                    <a href="#">Центр карьеры</a>
                    <a href="#">Отзывы</a>
                    <a href="#">О платформе</a>
                </div>
                <div class="list">
                    <p class="title">Контакты</p>
                    <a href="tel:88002228649">8 (800) 222-86-49</a>
                    <a href="tel:+74994449036">+7 499 444 90 36</a>
                </div>
            </div>
        </div>
    </footer>
    <p class="error none"></p>
    <script src="../js/code.jquery-3.6.1.min.js"></script>
    <script src="../js/main.js"></script>
    <script>course_lessons()</script>
</body>

</html>