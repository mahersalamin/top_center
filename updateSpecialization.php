<?php
// Include necessary files and initialize database connection
require "MyDB.php";
require 'dbconnection.php';
$db = new MyDB();
// Check if the form is submitted

//
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $id = $_POST['id'];
    $new_spec = $_POST['new_spec'];
    $class = '';
    $active = '';
    if(isset($_POST['active'])){
        $active = $_POST['active'];
    }

    if(isset($_POST['class_type'])){
        $class = $_POST['class_type'];
    }

//    var_dump(strlen($class));die();


    $success = $db->updateSpecialization($id, $new_spec, $class,$active);
    if ($success) {
        // Redirect to a success page or display a success message
        $status = "success";

        header("Location: ./page/newMissionSpecialization.php?status=$status&message=تم تعديل التخصص بنجاح");
        exit();
    } else {
        // Handle the case where adding the specialization failed
        $status = "error";

        header("Location: ./page/newMissionSpecialization.php?status=$status&message=لم يتم التعديل");
    }
} else {
    // If the form is not submitted, redirect to appropriate page
    header("Location: " . ($_COOKIE['role'] == 2 ? "bodyHomeUser.php" : ($_COOKIE['role'] == 1 ? "homeAdmin.php" : "")));
    exit;
}
?>
