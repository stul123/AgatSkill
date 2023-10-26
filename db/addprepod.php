<?php
// Функция для подключения к базе данных SQLite
function connectToDatabase()
{
    $db = new SQLite3('./db.db');
    return $db;
}

// Проверка роли "admin"
session_start();
if (!isset($_SESSION['user_id'])) {
    // Пользователь не авторизован, выполните необходимую обработку (например, перенаправление на страницу входа).
    header('Location: /signin');
    exit();
}

$user_id = $_SESSION['user_id'];

$db = connectToDatabase();

// Проверка роли "admin" для текущего пользователя
$adminStmt = $db->prepare('SELECT role FROM users WHERE user_id = :user_id');
$adminStmt->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
$adminResult = $adminStmt->execute();
$userData = $adminResult->fetchArray(SQLITE3_ASSOC);

if ($userData['role'] !== 'admin') {
      header('Location: /'); // Например, перенаправление на главную страницу.
    exit();
}

// Если пользователь имеет роль "admin", продолжаем выполнение кода.

// Часть 1: Добавление новой записи в таблицу trainings из POST-запроса
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['course_name']) && isset($_POST['course_info'])) {
    $course_name = $_POST['course_name'];
    $course_info = $_POST['course_info'];

    $stmt = $db->prepare('INSERT INTO trainings (course_name, course_info) VALUES (:course_name, :course_info)');
    $stmt->bindValue(':course_name', $course_name, SQLITE3_TEXT);
    $stmt->bindValue(':course_info', $course_info, SQLITE3_TEXT);
    $stmt->execute();

    // Получение course_id только что добавленной строки
    $course_id = $db->lastInsertRowID();

    // Часть 2: Изменение роли пользователя на "teacher"
    if (isset($_POST['user_id'])) {
        $user_id_to_promote = $_POST['user_id'];
        $roleUpdateStmt = $db->prepare('UPDATE users SET role = :role WHERE user_id = :user_id');
        $roleUpdateStmt->bindValue(':role', 'teacher', SQLITE3_TEXT);
        $roleUpdateStmt->bindValue(':user_id', $user_id_to_promote, SQLITE3_INTEGER);
        $roleUpdateStmt->execute();
    }

    echo 'Данные успешно обновлены.';
}

// Закрываем соединение с базой данных
$db->close();
?>
