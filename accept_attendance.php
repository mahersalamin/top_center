<?php
error_reporting(E_ERROR | E_PARSE);
require "MyDB.php";
$db = new MyDB();


// Start transaction
$conn = $db->connect();
// Get the POST data
$sessionId = $_POST['session_id'];
$attendanceId = $_POST['attendance_id'];
$studentIds = explode(',', $_POST['student_ids']);
$hours = $_POST['hours'];

// Start transaction
$conn->begin_transaction();

try {
    // Update hours for each student

    foreach ($studentIds as $studentId) {
        // Fetch current hours for the student in the session
        $query = "SELECT total FROM att WHERE session_id = ? AND st_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('ii', $sessionId, $studentId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        $currentHours = timeToDecimal($row['total']);

        // Fetch total hours for the session
        $query = "SELECT hours FROM sessions WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $sessionId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $totalSessionHours = $row['hours'];
        // Calculate new hours
        $newHours = min((float)  $hours - $currentHours, $totalSessionHours);
//        var_dump((float)  $hours - $currentHours , $totalSessionHours);die();

        $studentId=(int)$studentId;
        $sessionId=(int)$sessionId;
        // Update session_students with new hours
        $query = "UPDATE session_students SET hours = ? WHERE session_id = ? AND student_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('dii', $newHours, $sessionId, $studentId);
        var_dump($newHours, $sessionId, $studentId); die();
        $stmt->execute();
    }

    // Mark the attendance as accepted
    $query = "UPDATE att SET processed = 1 WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $attendanceId);
    $stmt->execute();

    // Commit transaction
    $conn->commit();

    echo json_encode(['message' => 'تم القبول.']);
} catch (Exception $e) {
    // Rollback transaction in case of error
    $conn->rollback();
    echo json_encode(['message' => 'خطأ في القبول: ' . $e->getMessage()]);
}



function timeToDecimal($time): string
{
    list($hours, $minutes, $seconds) = explode(':', $time);

    $decimalHours = (int)$hours + ($minutes / 60) + ($seconds / 3600);
    return number_format($decimalHours, 2);
}