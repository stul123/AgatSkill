<?php
include('../db/connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['email']) && isset($_POST['password'])) { 
        $stmt = $db->prepare('INSERT INTO users (email, password) VALUES (:email, :password)');
        $stmt->bindValue(':email', $_POST['email'], SQLITE3_TEXT);
        $stmt->bindValue(':password', $_POST['password'], SQLITE3_TEXT);
        if ($stmt->execute()) {
            $user_id = $db->lastInsertRowID();  
            $_SESSION['user_id'] = $user_id;
            $_SESSION['role'] = "student";            
            echo $user_id;
        } else {
            echo 'error';
        }
        $db->close();
    } else {
        echo "error";
    }
}
?>
