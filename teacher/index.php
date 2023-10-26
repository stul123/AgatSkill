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
        .main_stud_list {
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
                <div class="list active">
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
                <div class="list">
                    <img src="/img/bookmark.svg" alt="bookmark">
                    <p><a href="/answers/">Просмотр ответов</a></p>
                </div>
                <div class="list">
                    <img src="/img/paper clip.svg" alt="paper clip">
                    <p><a href="/summary_table">Сводная таблица</a></p>
                </div>
            </div>
        </header>
        <main class="main_stud_list">
            <div class="title-block-bum">
                <div class="title_box">
                    <div class="main_title">
                        <h2>Список студентов</h2>
                    </div>
                </div>
                <div class="stud_table">
                <div class="table_nav">
                        <p class="nav_name">Имя</p>
                        <p class="nav_mail">Почта</p>
                        <p class="nav_info">Контактные данные</p>
                        <p class="nav_id">ID</p>
                    </div>
                    <?php
                    
                
              
                
             
                    ?>
                 
                    <div class="members">
                        <?php
                        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                            echo '
                            <div class="member">
                            <div class="name">
                                <img src="'.$row['avatar'].'" alt="member">
                                <p class="member_name">'.$row['name']. ' ' .$row['surname'] .'</p>
                            </div>
                            <div class="nav_mail">
                                <p>'.$row['email'].'</p>
                            </div>
                            <p class="nav_info">'.$row['phone'].'</p>
                            <p class="nav_id">'.$row['user_id'].'</p>
                        </div>
                            ';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </main>
<?php
        $db->close();
?>
    </div>
</body>

</html>