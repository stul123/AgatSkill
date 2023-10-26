<?php
$db = new SQLite3('../db/db.db');
$stmt = $db->prepare('SELECT courses_id, course_name, period FROM trainings');
$result = $stmt->execute();
$trainings = array();
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    $trainings[] = $row;
}
$db->close();
header('Content-Type: application/json');
echo json_encode($trainings);
?>
