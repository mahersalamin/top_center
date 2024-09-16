<?php
// Include necessary files and initialize database connection
require "MyDB.php";
require 'dbconnection.php';
$db = new MyDB();

// echo json_encode($_POST);die();
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $success = false;
    
    // Check the type of package
    if ($_POST['pkg'] == 'student-to-package') {
        
        $student_ids = $_POST['students'];
        $sessions = $_POST['sessions'];
        
        // Update student data in the database
        $success = $db->updateSessions($student_ids, $sessions);
        if ($success === true) {
            header("Location: page/assignToPackages.php?status=success&message=" . urlencode("تم التعيين بنجاح"));
            exit;
        } else if (is_string($success)) {
            // If $success is an error message string, pass the error message in the URL
            header("Location: page/assignToPackages.php?status=error&message=" . urlencode($success));
            exit;
        } else {
            // Handle generic failure
            header("Location: page/assignToPackages.php?status=error&message=" . urlencode("فشل التعيين"));
            exit;
        }
        // var_dump($success);die();
    } else if ($_POST['pkg'] == 'package') {
        $price = 0;
        $session_name = $_POST['session_name'];
        $student_ids = $_POST['students'];
        $session_package = $_POST['session_package'];
        $materials = $_POST['materials2'];
        $is_group = $_POST['is_group'];
        $hours = $_POST['hours'];
        $teachers = $_POST['teachers'];
    
        // Calculate price based on group status
        if ($is_group == 1) {
            $price = count($student_ids) * $_POST['price'];
        } else {
            $price = $_POST['price'];
        }
    
        // Add session to the database
        $success = $db->addSession($session_name, $student_ids, $session_package, $materials, $is_group, $price, $hours, $teachers);
    
        // Check if the operation was successful
        if ($success === true) {
            header("Location: page/assignToPackages.php?status=success&message=" . urlencode("تم التعيين بنجاح"));
            exit;
        } else if (is_string($success)) {
            // If $success is an error message string, pass the error message in the URL
            header("Location: page/assignToPackages.php?status=error&message=" . urlencode($success));
            exit;
        } else {
            // Handle generic failure
            header("Location: page/assignToPackages.php?status=error&message=" . urlencode("فشل التعيين"));
            exit;
        }
    }
    
} else {
    // If the form is not submitted, redirect to appropriate page based on user role
    $redirectPage = ($_COOKIE['role'] == 2) ? "bodyHomeUser.php" : ($_COOKIE['role'] == 1 ? "assignPackages.php" : "");
    header("Location: $redirectPage");
    exit;
}
