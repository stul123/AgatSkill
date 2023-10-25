<?php
session_start();


if (!isset($_SESSION['user_id'])) {
    header('Location: /signin');
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = new SQLite3('./db.db');
    $updateQuery = 'UPDATE users SET ';
    $updateData = array();
    if (!empty($_POST['email'])) {
        $updateData[] = 'email = :email';
    }

    if (!empty($_POST['password'])) {
        $updateData[] = 'password = :password';
    }

    if (!empty($_POST['name'])) {
        $updateData[] = 'name = :name';
    }

    if (!empty($_POST['surname'])) {
        $updateData[] = 'surname = :surname';
    }

    if (!empty($_POST['phone'])) {
        $updateData[] = 'phone = :phone';
    }

    if (!empty($updateData)) {
        $updateQuery .= implode(', ', $updateData);
        $updateQuery .= ' WHERE user_id = :user_id';
        
        $stmt = $db->prepare($updateQuery);
        $stmt->bindValue(':user_id', $user_id, SQLITE3_INTEGER);

        if (!empty($_POST['email'])) {
            $stmt->bindValue(':email', $_POST['email'], SQLITE3_TEXT);
        }

        if (!empty($_POST['password'])) {
            $stmt->bindValue(':password', $_POST['password'], SQLITE3_TEXT);
        }

        if (!empty($_POST['name'])) {
            $stmt->bindValue(':name', $_POST['name'], SQLITE3_TEXT);
        }

        if (!empty($_POST['surname'])) {
            $stmt->bindValue(':surname', $_POST['surname'], SQLITE3_TEXT);
        }

        if (!empty($_POST['phone'])) {
            $stmt->bindValue(':phone', $_POST['phone'], SQLITE3_TEXT);
        }

        if ($stmt->execute()) {
            echo 'like';
        } else {
            echo 'error';
        }
    } else {
        echo 'нет данных для обновления';
    }

    $db->close();
}
?>
