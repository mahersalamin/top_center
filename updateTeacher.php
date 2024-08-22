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
    $email = $_POST['email'];
    $specs = $_POST['specs'];
    $password = $_POST['password'];

    // Prepare the specs array to only include checked items
    $filteredSpecs = [];
    foreach ($specs as $specId => $specData) {
        if (isset($specData['price'])) {
            $filteredSpecs[$specId] = ['price' => $specData['price']];
        }
    }

    // Update teacher data in the database
    $success = $db->updateTeacher($id, $name, $email, $filteredSpecs,$password);

    // Check if the update was successful
    if ($success) {
        // Redirect to appropriate page based on user role
        header("Location: " .
            ($_COOKIE['role'] == 2 ? "page/bodyHomeUser.php" : ($_COOKIE['role'] == 1 ? "page/homeAdmin.php" : "page/editTeacher.php"))
        );
        exit;
    } else {
        echo "Error updating teacher data.";
    }
} else {
    // If the form is not submitted, redirect to appropriate page
    header("Location: " . ($_COOKIE['role'] == 2 ? "bodyHomeUser.php" : ($_COOKIE['role'] == 1 ? "homeAdmin.php" : "")));
    exit;
}
?>