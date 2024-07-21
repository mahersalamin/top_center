<?php
require 'MyDB.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sessionId = $_POST['session_id'];
    $db = new MyDB();
    $students = $db->allStudentsDailyReport($sessionId);

    echo json_encode($students);
}
?>
