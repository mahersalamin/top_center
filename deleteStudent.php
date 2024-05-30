<?php
// Include the database class
require_once 'dbconnection.php';
require('MyDB.php');
// Check if student id is provided
if (isset($_POST['student_id'])) {
    // Get student id from POST data
    $student_id = $_POST['student_id'];

    // Create a new instance of the database class
    $db = new MyDB();

    // Call the deleteStudent method to delete the student
    $result = $db->deleteStudent($student_id);


    // Check if deletion was successful
    if ($result) {
        // Set the message and status
        $message = "تم حذف الطالب بنجاح";
        $status = "success";

        // Redirect to the homeAdmin.php page with message and status as query parameters
        header("Location: ./page/homeAdmin.php?message=" . urlencode($message) . "&status=" . urlencode($status));
        exit();
    } else {
        // Redirect to an error page or display an error message
        header("Location: error.php");
        exit();
    }
} else {
    // If student id is not provided, redirect to an error page
    $message = "لم يتم حذف الطالب";
    $status = "error";

    header("Location: ./page/homeAdmin.php?message=" . urlencode($message) . "&status=" . urlencode($status));
    exit();
}
?>
