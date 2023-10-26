<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>AgatSkill</title>
    <link rel="stylesheet" href="/css/style.css" />
    <style>
        .header_stud {
            position: fixed;
        }
        .main_stud_answers {
            margin-left: 264px;
        }
    </style>
        <?php
session_start();
function connectToDatabase()
{
    $db = new SQLite3('../db/db.db');
    return $db;
}
if (!isset($_SESSION['user_id'])) {
    header('Location: /signin');
    exit();
}
$user_id = $_SESSION['user_id'];
$db = connectToDatabase();
$stmt = $db->prepare('SELECT role FROM users WHERE user_id = :user_id');
$stmt->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
$result = $stmt->execute();
$user = $result->fetchArray(SQLITE3_ASSOC);
 
 

if ($user && $user['role'] !== 'teacher' && $user['role'] !== 'admin') {
    header('Location: /'); 
    exit();
}
$stmt = $db->prepare('SELECT * FROM users WHERE role = :role');
$stmt->bindValue(':role', 'student', SQLITE3_TEXT);
$result = $stmt->execute();

?>
</head>

<body>
    <div class="admin_block">
        <header class="header_stud">
            <div class="logo"><img src="/img/black_logo,.svg" alt="logo">
                <h2>AgatSkill</h2>
            </div>
            <div class="profile">
            <?php
                $db = new SQLite3('../db/db.db');
                $stmt = $db->prepare('SELECT * FROM users WHERE user_id = :user_id');
                $stmt->bindValue(':user_id', $_SESSION['user_id'], SQLITE3_INTEGER);
                $resultv2 = $stmt->execute();
                $mainuser = $resultv2->fetchArray(SQLITE3_ASSOC);
                
                ?>
                <img src="<?php echo $mainuser["avatar"];?>" class="teacherava" alt="Avatar">
                <p><?php echo $mainuser["name"] . ' ' . $mainuser["surname"];?></p>
           
            </div>
            <div class="nav">
                <p>Навигация</p>
                <div class="list">
                    <a href=""></a>
                    <img src="/img/hat.svg" alt="hat">
                    <p><a href="/teacher/">Список студентов</a></p>
                </div>
                <div class="list">
                    <img src="/img/Book 2.svg" alt="Book">
                    <p><a href="/create_lesson/">Создать урок</a></p>
                </div>
                <div class="list">
                    <img src="/img/histogram.svg" alt="histogram">
                    <p><a href="/lesson_list/">Список уроков</a></p>
                </div>
                <div class="list active">
                    <img src="/img/bookmark.svg" alt="bookmark">
                    <p><a href="/answers/">Просмотр ответов</a></p>
                </div>
                <div class="list">
                    <img src="/img/paper clip.svg" alt="paper clip">
                    <p><a href="/summary_table">Сводная таблица</a></p>
                </div>
            </div>
        </header>
        <main class="main_stud_answers">
            <div class="title-block-bum">
                <div class="title_box">
                    <div class="main_title">
                        <h2>Просмотр ответов</h2>
                        <p></p>
                    </div>
                </div>
            </div>
            <div class="first_block">
            <?php
              
                $db = connectToDatabase();
                $stmt = $db->prepare('SELECT text, user_id, appraisal FROM answers');
                $result = $stmt->execute();
                while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                    $user_id = $row['user_id'];
                    $userStmt = $db->prepare('SELECT name, surname, avatar FROM users WHERE user_id = :user_id');
                    $userStmt->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
                    $userResult = $userStmt->execute();
                    $userData = $userResult->fetchArray(SQLITE3_ASSOC);
                    echo '
                    <div class="card">
                    <div class="name">
                        <img src="' . $userData['avatar'] . '" alt="avatar">
                        <h2>' . $userData['name'] . ' ' .  $userData['surname'].'</h2>
                    </div>
                    <div class="card_text">
                        <h3>Оценка: '.$row['appraisal'] .'</h3>
                        <p>'.$row['text'] .'</p>
                    </div>
                    <button class="btn"><a href="/answer">Подробнее</a></button>
                </div>';
                }
                $db->close();
                ?>
                
            </div>
        </main>
    </div>
</body>

</html>