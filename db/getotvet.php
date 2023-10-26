<?php
session_start();


function connectToDatabase()
{
    $db = new SQLite3('./db.db');
    return $db;
}

// Проверяем, авторизован ли пользователь (можете использовать свои условия для этой проверки)
if (!isset($_SESSION['user_id'])) {
    echo 'error';
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['lesson_id'])) {
    $user_id = $_SESSION['user_id'];
    $lesson_id = $_POST['lesson_id'];

    $db = connectToDatabase();
    $stmt = $db->prepare('SELECT appraisal, text FROM answers WHERE user_id = :user_id AND lesson_id = :lesson_id');
    $stmt->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
    $stmt->bindValue(':lesson_id', $lesson_id, SQLITE3_INTEGER);
    $result = $stmt->execute();
    $answer = $result->fetchArray(SQLITE3_ASSOC);
    if ($answer) {
        $response = array(
            'appraisal' => $answer['appraisal'],
            'text' => $answer['text']
        );
        header('Content-Type: application/json');
        echo json_encode($response);
    } else {
        echo 'запись не найдена';
    }
    $db->close();
}
?>
