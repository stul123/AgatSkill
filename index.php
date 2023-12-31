<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
session_start();
$path_DB = $_SERVER['DOCUMENT_ROOT']."/db/db.db";
$db = new SQLite3($path_DB); 
?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AgatSkill | Главная</title>
    <link rel="stylesheet" href="./css/style.css">
    <link rel="apple-touch-icon" sizes="180x180" href="/fav/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/fav/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/fav/favicon-16x16.png">
    <link rel="manifest" href="/fav/site.webmanifest">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">
</head>

<body>
<?php
    $avatar = "/img/avatar.png";
    $mainavatar = "/img/avatar.png";
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $stmt = $db->prepare('SELECT * FROM users WHERE user_id = :user_id');
    $stmt->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
    $result = $stmt->execute();
    $user = $result->fetchArray(SQLITE3_ASSOC);
    $userid = $user['user_id']; 
    if ($user['avatar'] != '') {
        $avatar = $user['avatar'];
    } 
    $mainavatar = $user['avatar'];
} else {
    $user = 'no';
}

if ($user == "no") {
    $login = "no";
} else  {
    $login = 'y';
}

$db->close();

?>
    <?php 
    include('./header.php');
     ?>
    <main class="main_page">
        <div class="main_page_con">
            <div class="main_text_block">
                <p class="main_title">Изучайте предметы с AgatSkill!</p>
                <p class="main_text">Учеба без границ: ваши знания, ваше будущее!</p>
            </div>
            <div class="why_block">
                <div class="left">
                    <p class="title">Почему же выбирают именно нас?</p>
                    <p class="text">
                        AgatSkill - это не просто платформа обучения, это образовательное сообщество, где каждый студент находит индивидуальный подход. В отличие от остальных, мы предоставляем уникальные интерактивные методы обучения, основанные на проверенных педагогических
                        подходах, которые помогают каждому ученику максимизировать свой потенциал. Мы также фокусируемся на создании широкого спектра образовательных программ, которые позволяют нашим студентам развивать навыки в различных областях одновременно,
                        делая наше образование более всесторонним и полезным для их будущей карьеры.
                    </p>
                    <a href="./courses" class="btn_courses">Начать обучение!</a>
                </div>
                <img src="./img/undraw_education_f8ru 1.png" alt="undraw_education_f8ru" class="why_img">
            </div>
            <div class="bests_sec">
                <div class="block">
                    <img src="./img/Hand Heart.svg" alt="Hand Heart">
                    <p class="title">Персонализированный подход</p>
                    <p class="text">AgatSkill предлагает персонализированный опыт обучения, адаптированный под потребности каждого ученика. Мы предоставляем индивидуальные учебные планы, учитывающие уровень знаний и учебные потребности студентов!</p>
                </div>
                <div class="block">
                    <img src="./img/Smile Circle.svg" alt="Smile Circle">
                    <p class="title">Качество обучения</p>
                    <p class="text">Мы гарантируем высокое качество образования, предлагая программы, разработанные экспертами с многолетним опытом в своих областях. Наши учебные материалы и методики обучения обновляются в соответствии с последними тенденциями в индустрии!</p>
                </div>
                <div class="block">
                    <img src="./img/Verified Check.svg" alt="Verified Check">
                    <p class="title">Гибкость обучения</p>
                    <p class="text"> AgatSkill предлагает гибкий график обучения, который позволяет студентам учиться в удобное для них время. Мы также предоставляем возможность доступа к материалам курсов в любое время и с любого устройства, что позволяет нашим студентам
                        учиться в любом месте и в любое время!</p>
                </div>
            </div>
        </div>
    </main>
    <?php 
    include('./footer.php');
     ?>
</body>

</html>

</html>