<?php
session_start();
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
function connectToDatabase()
{
    $db = new SQLite3('./db.db');
    return $db;
}

$db = connectToDatabase();
 
if ( isset($_POST['lesson_name']) && isset($_POST['description']) && isset($_POST['timing']) && isset($_POST['objectives']) && isset($_POST['steps']) && isset($_POST['homework'])) {
    $lesson_name = $_POST['lesson_name'];
    $description = $_POST['description'];
    $timing = $_POST['timing'];
    $objectives = $_POST['objectives'];
    $steps = $_POST['steps'];
    $homework = $_POST['homework'];

    $stmt = $db->prepare('INSERT INTO lessons (lesson_name, description, timing, objectives, steps, homework) VALUES (:lesson_name, :description, :timing, :objectives, :steps, :homework)');
    $stmt->bindValue(':lesson_name', $lesson_name, SQLITE3_TEXT);
    $stmt->bindValue(':description', $description, SQLITE3_TEXT);
    $stmt->bindValue(':timing', $timing, SQLITE3_TEXT);
    $stmt->bindValue(':objectives', $objectives, SQLITE3_TEXT);
    $stmt->bindValue(':steps', $steps, SQLITE3_TEXT);
    $stmt->bindValue(':homework', $homework, SQLITE3_TEXT);
    $stmt->execute();
    $lesson_id = $db->lastInsertRowID();

    // Часть 3: Обновление записи в таблице trainings
    if (isset($_POST['courses_id'])) {
        $courses_id = $_POST['courses_id'];
        $stmt = $db->prepare('SELECT course_lessons FROM trainings WHERE courses_id = :courses_id');
        $stmt->bindValue(':courses_id', $courses_id, SQLITE3_INTEGER);
        $result = $stmt->execute();
        $training = $result->fetchArray(SQLITE3_ASSOC);

        if ($training) {
            $course_lessons = json_decode($training['course_lessons']);
            if (!is_array($course_lessons)) {
                $course_lessons = array();
            }
            $course_lessons[] = $lesson_id;
            $stmt = $db->prepare('UPDATE trainings SET course_lessons = :course_lessons WHERE courses_id = :courses_id');
            $stmt->bindValue(':course_lessons', json_encode($course_lessons), SQLITE3_TEXT);
            $stmt->bindValue(':courses_id', $courses_id, SQLITE3_INTEGER);
            $stmt->execute();
        }
    }
    echo 'Урок успешно добавлен.';
} else {
    echo "error";
}

$db->close();
?>
