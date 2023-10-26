<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: /signin'); 
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $user_id = $_POST['id'];
    $db = new SQLite3('../db/db.db');
    $stmt = $db->prepare('SELECT courses FROM users WHERE user_id = :user_id');
    $stmt->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
    $result = $stmt->execute();
    $user = $result->fetchArray(SQLITE3_ASSOC);

    $response = array(); // Массив для хранения данных о курсах

    if ($user) {
        $courses = json_decode($user['courses']);
        if ($courses == '') {
            $courseData = array(
                'name' => 'нету курсов',
            );
            $response[] = $courseData;
        } else {
            foreach ($courses as $course) {
                $stmt = $db->prepare('SELECT course_name, course_info, period FROM trainings WHERE courses_id = :courses_id');
                $stmt->bindValue(':courses_id', $course, SQLITE3_INTEGER);
                
                $result = $stmt->execute();
                $training = $result->fetchArray(SQLITE3_ASSOC);
            
                if ($training) {
                    $courseData = array(
                        'name' => $training['course_name'],
                        'id' => $course,
                        'info' => $training['course_info'],
                        'period' => $training['period']
                    );
                    $response[] = $courseData;
                }
            }
        }        
    } else {
        echo "error";
    }

    $db->close();
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>
