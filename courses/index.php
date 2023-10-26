<!DOCTYPE html>
<html lang="en">
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
</head>

<body>
    <?php 
    include('../header.php');
    ?>
    <main class="main_view_course">
        <div class="main_title">
            <h1>Просмотр курсов</h1>
            <p>Открой мир знаний через наши курсы!</p>
        </div>
        <div class="place">
            <!-- <div class="course_list">
                <div class="coruses">
                    <p>Список курсов</p>
                    <div class="btns">
                        <button class="btn">Все курсы</button>
                        <button class="btn">Программирование</button>
                        <button class="btn">Дизайн</button>
                        <button class="btn">Английский язык</button>
                        <button class="btn">Менеджмент</button>
                        <button class="btn">Общее развитие </button>
                        <button class="btn">Другое</button>
                    </div>
                </div>
            </div> -->
            <div class="course_checklist">
                <!-- <div class="filters">
                    <div class="filters_nav"><img src="/img/filter.svg" alt="filter">
                        <h2>Фильтры</h2>
                    </div>
                    <form class="table_lvl">
                        <div class="p_name">
                            <p>Сложность обучения</p>
                        </div>
                        <p><input type="radio" id="radio" checked>soft</p>
                        <p><input type="radio" id="radio">medium</p>
                        <p><input type="radio" id="radio">hard</p>
                    </form>
                    <form class="table_lvl">
                        <div class="p_name">
                            <p>Срок обучения</p>
                        </div>
                        <p><input type="radio" id="radio">1-2 месяца</p>
                        <p><input type="radio" id="radio">3-5 месяцев</p>
                        <p><input type="radio" id="radio">6-12 месяцев</p>
                        <p><input type="radio" id="radio" checked>12+ месяцев</p>
                    </form>
                    <form class="table_lvl">
                        <div class="p_name">
                            <p>Кол-во курсов</p>
                        </div>
                        <p><input type="radio" id="radio">1-2 курса</p>
                        <p><input type="radio" id="radio">3-5 курса</p>
                        <p><input type="radio" id="radio">6-12 курсов</p>
                        <p><input type="radio" id="radio">12+ курсов</p>
                    </form>
                </div> -->
                <div class="all_courses">
                    <h3>Курсы (93)</h3>
                    <div class="courses_table">
                        <div class="first_half">
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script src="../js/code.jquery-3.6.1.min.js"></script>
    <script src="../js/main.js"></script>
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
    <script>loadallcourses()</script>
</body>

</html>