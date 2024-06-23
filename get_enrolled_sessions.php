<?php
// Include your database connection code here
// For example, include database connection and functions file
require "MyDB.php";
// Assuming $db is your PDO object
$db = new MyDB();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['student_id'])) {
    // Call the getEnrolledSessionsForStudent function
    $studentId = $_POST['student_id'];
    $sessions = $db->getEnrolledSessionsForStudent($studentId);
    // Return the result as JSON
    echo json_encode($sessions);
} else if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['teacher_id'])) {
    // Call the getEnrolledSessionsForStudent function
    $teacherId = $_POST['teacher_id'];
    $sessions = $db->getEnrolledSessionsForTeachers($teacherId);
    // Return the result as JSON
    echo json_encode($sessions);
} else {
    // Handle invalid request
    http_response_code(400);
    echo "Invalid request!";
}
?>
