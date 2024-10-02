<?php

require_once "MyDB.php";

// Initialize database connection
$db = new MyDB();

// Sanitize and validate input data
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
$specs = $_POST['specs'];
$role = filter_input(INPUT_POST, 'role', FILTER_SANITIZE_STRING);
$id_number = filter_input(INPUT_POST, 'id_number', FILTER_SANITIZE_STRING);
$degree = filter_input(INPUT_POST, 'degree', FILTER_SANITIZE_STRING);
$phone_number = filter_input(INPUT_POST, 'phone_number', FILTER_SANITIZE_STRING);
$address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_STRING);

// Handle file upload
$file = $_FILES['file'] ?? null;
$img = '';

if ($file && $file['error'] === UPLOAD_ERR_OK) {
    // Define allowed file extensions and maximum file size
    $allowed_exts = ['jpg', 'jpeg', 'png', 'gif'];
    $max_file_size = 5 * 1024 * 1024; // 5 MB

    $file_name = basename($file['name']);
    $file_size = $file['size'];
    $file_tmp_name = $file['tmp_name'];

    // Validate file extension and size
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    if (in_array($file_ext, $allowed_exts) && $file_size <= $max_file_size) {
        $img = uniqid("file-", true) . '.' . $file_ext;
        $file_upload_path = 'upload/' . $img;

        // Move uploaded file to the designated folder
        if (!move_uploaded_file($file_tmp_name, $file_upload_path)) {
            // Handle file upload error
            $img = '';
        }
    } else {
        // Invalid file extension or size
        $img = '';
    }
}
//var_dump($email, $password, $name, $specs, $img, $role, $id_number, $degree, $phone_number, $address);die();

// Add teacher to the database
$result = $db->addTeacher($email, $password, $name, $specs, $img, $role, $id_number, $degree, $phone_number, $address);

// Redirect based on result
$status = $result ? "success" : "error";
$message = $result ? "تم اضافة المعلم بنجاح" : "حدث خطأ";
header("Location: ./page/newMissionTeacher.php?status=$status&message=$message");
exit;
