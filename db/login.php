<?php
session_start();
$db = new SQLite3('./db.db');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['email']) && isset($_POST['password'])) { 
        $stmt = $db->prepare('SELECT user_id, email, role, password FROM users WHERE email = :email');
        $stmt->bindValue(':email', $_POST['email'], SQLITE3_TEXT);
        $result = $stmt->execute();
        $user = $result->fetchArray(SQLITE3_ASSOC);
        if ($user && $_POST['password'] === $user['password']) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user_role'] = $user['role']; 
            echo $user['user_id'];
        } else {
            echo 'error';
        }
        $db->close();
    } else {
        echo "error";
    }
} else {
    echo "error";
}
?>


