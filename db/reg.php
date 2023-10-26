 
<?php
session_start();

function connectToDatabase()
{
    $db = new SQLite3('./db.db');
    return $db;
}

if (isset($_POST['email']) && isset($_POST['password'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $db = connectToDatabase();
    $stmt = $db->prepare('INSERT INTO users (email, password) VALUES (:email, :password)');
    $stmt->bindValue(':email', $email, SQLITE3_TEXT);
    $stmt->bindValue(':password', $password, SQLITE3_TEXT);
    $stmt->execute();
    $user_id = $db->lastInsertRowID();
    $db->close();
    $_SESSION['user_id'] = $user_id;
    $_SESSION['role'] = "student";     
    echo $user_id;
} else {
    echo "error";
}
?>