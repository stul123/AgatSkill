<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>AgatSkill</title>
    <link rel="stylesheet" href="/css/style.css" />
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
                    <img src="/img/hat.svg" alt="hat">
                    <p><a href="/teacher/">Список студентов</a></p>
                </div>
                <div class="list active">
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
        <main class="create_less">
            <div class="less_con">
                <div class="less_title">
                    <h2>Создание урока</h2>
                
                </div>
                <div class="less_settings_block">
                    <div class="less_settings">
                        <h2>Давайте создадим новый урок!</h2>
                        <p>Выберете курс</p>
                        <div class="input_block">
                            <select name="selectkurs" id="selectkurs">
                            <?php
                                $stmtv3 = $db->prepare('SELECT course_name, courses_id FROM trainings');
                                $resultv3 = $stmtv3->execute();
                                while ($rowv3 = $resultv3->fetchArray(SQLITE3_ASSOC)) {
                                    echo '<option value="' . $rowv3['courses_id'] . '">'.$rowv3['course_name'].'</option>';
                                
                                }
                                
                            ?>  
                            </select>
                        </div>
                        <p>Название урока</p>
                        <div class="input_block">
                            <input type="name" id="name">
                            <label for="name">Название</label>
                        </div>
                        <p>Описание урока</p>
                        <div class="input_block">
                            <input type="description" id="description">
                            <label for="description">Описание урока</label>
                        </div>
                        <p>Цели урока</p>
                        <div class="input_block">
                            <input type="text" id="objectives">
                            <label for="objectives">Цели урока</label>
                        </div>
                        <p>Шаги урока</p>
                        <div class="input_block">
                            <input type="less_steps" id="less_steps">
                            <label for="less_steps">Шаги урока</label>
                        </div>
                        <p>Домашнее задание</p>
                        <div class="input_block">
                            <input type="homework" id="homework">
                            <label for="homework">Домашнее задание</label>
                        </div>
                    </div>
                    <div class="second_block">
                        <div class="send_file">
                            <div class="nav">
                                <h2>Прикрепите файлы</h2> <img src="/img/export.svg" alt="export">
                            </div>
                            <div class="input_block">
                                <input type="send_message" id="send_message">
                                <label for="send_message">Написать сообщение...</label>
                            </div>
                            <label for="fileinput" class="btn">Выберете тут файл</label>
                            <input type="file" id="fileinput" style="display: none;">
                        </div>
                        <div class="timetable">
                            <h2>Добавить в расписание</h2>
                            <div class="input_box">
                                <p>Время</p>
                                <div class="input_block">
                                    <input type="time" id="timetable">
                                    <button class="btn"><img src="/img/arrow_down_white.svg" alt="arrow down"></button>
                                </div>
                            </div>
                            <div class="input_box">
                                <p>День</p>
                                <div class="input_block">
                                    <input type="date" id="day">
                                    <button class="btn"><img src="/img/arrow_down_white.svg" alt="arrow down"></button>
                                </div>
                            </div>
                        </div>
                        <button class="create_less" id="create_less"><img src="/img/white_plus.svg" alt="plus">Добавить урок</button>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <p class="error none"></p>
    <script src="../js/code.jquery-3.6.1.min.js"></script>
    <script src="../js/main.js"></script>
</body>

</html>