<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: /signin'); 
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $db = new SQLite3('./db.db');
    $stmt = $db->prepare('DELETE FROM users WHERE user_id = :user_id');
    $stmt->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
    if ($stmt->execute()) {
        session_unset();
        session_destroy();
        echo 'delete';
        exit();
    } else {
        echo 'error';
    }
    $db->close();
}
?>
