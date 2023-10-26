<?php
$db = new SQLite3('../db/db.db');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['lesson_ids'])) {
    $lesson_ids = $_POST['lesson_ids']; // Массив с числами из POST-запроса
    $lessonsData = array();
    $stmt = $db->prepare('SELECT lesson_id, lesson_name, description FROM lessons WHERE lesson_id = :lesson_id');

    foreach ($lesson_ids as $lesson_id) {
        $stmt->bindValue(':lesson_id', $lesson_id, SQLITE3_INTEGER);
        $result = $stmt->execute();
        $lesson = $result->fetchArray(SQLITE3_ASSOC);

        if ($lesson) {
            $lessonsData[] = $lesson;
        }
    }
    $db->close();
    header('Content-Type: application/json');
    echo json_encode($lessonsData);
}
?>
