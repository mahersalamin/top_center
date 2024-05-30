<?php
// Include necessary files and initialize database connection
require "MyDB.php";
require 'dbconnection.php';
$db = new MyDB();
// Check if the form is submitted

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $success = false;
    if ($_POST['pkg'] == 'student-to-package') {
        $student_ids = $_POST['students'];
        $sessions = $_POST['sessions'];
        $materials = $_POST['materials'];


        // Update student data in the database
        $success = $db->updateSessions($student_ids, $sessions, $materials);
    } else if ($_POST['pkg'] == 'package') {
        $price = 0;
        $session_name = $_POST['session_name'];
        $student_ids = $_POST['students'];
        $session_package = $_POST['session_package'];
        $materials = $_POST['materials2'];
        $is_group = $_POST['is_group'];
        if ($is_group == 1) {
            $price = count($_POST['students']) * $_POST['price'];
        } else
            $price = $_POST['price'];
        $hours = $_POST['hours'];
        $teachers = $_POST['teachers'];


        // Update student data in the database
        $success = $db->addSession($session_name, $student_ids, $session_package, $materials, $is_group, $price,
            $hours, $teachers);
    }

    // Get form data

    // Check if the update was successful
    if ($success) {

        header("Location: page/assignToPackages.php?status=success&message=تم التعيين بنجاح");
        exit;
    } else {
        header("Location: page/assignToPackages.php?status=error&message=فشل التعيين");
    }
} else {
    // If the form is not submitted, redirect to appropriate page
    header("Location: " . ($_COOKIE['role'] == 2 ? "bodyHomeUser.php" : ($_COOKIE['role'] == 1 ? "assignPackages.php" : "")));
    exit;
}
?>
