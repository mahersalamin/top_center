<?php
// Include necessary files and initialize database connection
require "MyDB.php";
require 'dbconnection.php';
$db = new MyDB();
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $id = $_POST['id'];
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $class = $_POST['class'];
    $selectedTeachers = isset($_POST['emp']) ? $_POST['emp'] : [];

    // Update student data in the database
    $success = $db->updateStudent($id, $name, $phone, $class, $selectedTeachers);
    // Check if the update was successful
    if ($success) {
        // Redirect to appropriate page based on user role

        header("Location: " .
            ($_COOKIE['role'] == 2 ? "page/bodyHomeUser.php" : ($_COOKIE['role'] == 1 ? "page/homeAdmin.php" : "page/editStudent.php"))
        );
        exit;
    } else {
        echo "Error updating student data.";
    }
} else {
    // If the form is not submitted, redirect to appropriate page
    header("Location: " . ($_COOKIE['role'] == 2 ? "bodyHomeUser.php" : ($_COOKIE['role'] == 1 ? "homeAdmin.php" : "")));
    exit;
}
?>
