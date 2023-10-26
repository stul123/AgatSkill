<?php
session_start();

function connectToDatabase()
{
    $db = new SQLite3('./db.db');
    return $db;
}

if (!isset($_SESSION['user_id'])) {
    echo 'error';
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['text']) && isset($_POST['lesson_id'])) {
    $user_id = $_SESSION['user_id'];
    $text = $_POST['text'];
    $lesson_id = $_POST['lesson_id'];

    $db = connectToDatabase();
    $stmt = $db->prepare('SELECT * FROM answers WHERE user_id = :user_id AND lesson_id = :lesson_id');
    $stmt->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
    $stmt->bindValue(':lesson_id', $lesson_id, SQLITE3_INTEGER);
    $result = $stmt->execute();
    $existingAnswer = $result->fetchArray(SQLITE3_ASSOC);

    if ($existingAnswer) {
        $stmt = $db->prepare('UPDATE answers SET text = :text WHERE user_id = :user_id AND lesson_id = :lesson_id');
        $stmt->bindValue(':text', $text, SQLITE3_TEXT);
        $stmt->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
        $stmt->bindValue(':lesson_id', $lesson_id, SQLITE3_INTEGER);
        
        if ($stmt->execute()) {
            echo 'ответ успешно обновлен в базе данных';
        } else {
            echo 'error';
        }
    } else {
        $stmt = $db->prepare('INSERT INTO answers (user_id, text, lesson_id) VALUES (:user_id, :text, :lesson_id)');
        $stmt->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
        $stmt->bindValue(':text', $text, SQLITE3_TEXT);
        $stmt->bindValue(':lesson_id', $lesson_id, SQLITE3_INTEGER);
        
        if ($stmt->execute()) {
            echo 'ответ успешно добавлен в базу данных';
        } else {
            echo 'error';
        }
    }
    $db->close();
}
?>
