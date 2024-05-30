<?php


require "MyDB.php";

$db = new MyDB();
//echo json_encode($_POST);die();

//$teachers = $_POST['teachers'];
//$hours = $_POST['hours'];
//$price = $_POST['price'];
//$materials = $_POST['materials'];
$name = $_POST['name'];
$phone = $_POST['phone'];
$school_name = $_POST['school_name'];
$class = $_POST['class'];
//$session_package = $_POST['session_package'];
//$is_group=$_POST['is_group'];

//$file_name = $_FILES['file']['name'];
//$file_size = $_FILES['file']['size'];
//$file_tmp_name = $_FILES['file']['tmp_name'];
//$error = $_FILES['file']['error'];
//
//
//$file_ex = pathinfo($file_name, PATHINFO_EXTENSION);
//$file_ex_lc = strtolower($file_ex);


//if (isset($_FILES['file']) && $file_ex_lc) {
//
//    $new_file_name = uniqid("file-", true) . 'top' . $file_ex_lc;
//    $file_upload_path = 'upload/' . $new_file_name;
//    move_uploaded_file($file_tmp_name, $file_upload_path);
//
//    $result = $db->AddST($teachers
//        $hours
//        $price
//        $materials
//        $name
//        $phone
//        $school_name
//        $class
//        $session_package
//    );
//} else {
    $result = $db->AddST(
        $name,
        $phone,
        $school_name,
        $class
    );
//}

//var_dump($result);die();

if ($result) {

    $status = "success";
    header("Location: ./page/newMission.php?status=$status&message=تمت الإضافة بنجاح");
} else {

    $status = "error";
    header("Location: ./page/newMission.php?status=$status&message=$result");
}
