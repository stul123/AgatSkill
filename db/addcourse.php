<?php
session_start();

// Проверяем, авторизован ли пользователь
if (!isset($_SESSION['user_id'])) {
    header('Location: /signin');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['course_to_add'])) {
    $user_id = $_SESSION['user_id'];
    $course_to_add = $_POST['course_to_add'];
    $db = new SQLite3('../db/db.db');
    $stmt = $db->prepare('SELECT courses FROM users WHERE user_id = :user_id');
    $stmt->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
    $result = $stmt->execute();
    $user = $result->fetchArray(SQLITE3_ASSOC);

    if ($user) {
        $courses = json_decode($user['courses'], true);
        if (empty($courses)) {
            $courses = array();
        }
        if (!in_array($course_to_add, $courses)) { 
            $courses[] = $course_to_add;
            $stmt = $db->prepare('UPDATE users SET courses = :courses WHERE user_id = :user_id');
            $stmt->bindValue(':courses', json_encode($courses), SQLITE3_TEXT);
            $stmt->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
    
            if ($stmt->execute()) {
                echo 'Курс успешно добавлен в список пользователя.';
            } else {
                echo 'ошибка при обновлении списка курсов пользователя';
            }
        } else {
            echo 'курс уже имеется у вас';
        }
    } else {
        echo 'пользователь не найден';
    }
    $db->close();
}
?>
