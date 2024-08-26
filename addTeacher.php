<?php


require "MyDB.php";

$db = new MyDB();



$email = $_POST['email'];
$password = $_POST['password'];
$name = $_POST['name'];
$specs = $_POST['specs'];
$role = $_POST['role'];


$file_name = $_FILES['file']['name'];
$file_size = $_FILES['file']['size'];
$file_tmp_name = $_FILES['file']['tmp_name'];
$error = $_FILES['file']['error'];


$file_ex = pathinfo($file_name, PATHINFO_EXTENSION);
$file_ex_lc = strtolower($file_ex);


if (isset($_FILES['file']) && $file_ex_lc) {

    $img = uniqid("file-", true) . 'top' . $file_ex_lc;
    $file_upload_path = 'upload/' . $img;
    move_uploaded_file($file_tmp_name, $file_upload_path);

    $result = $db->addTeacher($email, $password, $name, $specs, $img, $role);
} else {
    $result = $db->addTeacher($email, $password, $name, $specs, '', $role);
}


// var_dump($result); die(' /result');

if ($result) {
    $status = "success";
    header("Location: ./page/newMission.php?status=$status&message=تم اضافة المعلم بنجاح");
} else {

    $status = "error";
    header("Location: ./page/newMission.php?status=$status&message=حدث خطأ");
}
