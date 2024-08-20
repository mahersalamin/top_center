<?php
// Include necessary files and initialize database connection
require "../MyDB.php";

$db = new MyDB();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $id = $_POST['id'];
    $name = $_POST['new_school'];
    $schoolType = $_POST['type'];
    $action = $_POST['action'];
    $success = false;
    switch ($action) {
        case 1:
            $success = $db->updateSchool($id, $name, $schoolType);
            break;
        case 2:
            $success = $db->deleteSchool($id);
            break;
        case 3:
            $success = $db->unArchiveSchool($id);
            break;
        default:
            echo "طلب غير صالح";
    }

    if ($success) {
        // Redirect to appropriate page based on user role

        header("Location: newSchool.php");
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
