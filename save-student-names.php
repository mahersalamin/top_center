<?php

require 'MyDB.php';
$db = new MyDB();
$id = $_POST['attendance_id'];
$student_ids = $_POST['student_ids'];

// Update the database
$sql = "UPDATE att SET st_id = ? WHERE id = ?";
$stmt = $db->connect()->prepare($sql);

if ($stmt) {
    // Bind parameters
    $stmt->bind_param('si', $student_ids, $id); // 'si' means string and integer

    // Execute the statement
    $result = $stmt->execute();

    // Close the statement
    $stmt->close();
} else {
    $result = false;
}

// Return a JSON response
echo json_encode(['success' => $result]);
