<?php


require "MyDB.php";

$db = new MyDB();




$name = $_POST['name'];
$type = $_POST['type'];




    $result = $db->addSchool($name, $type);


// var_dump($result); die(' /result');

if ($result) {
    $status = "success";
    header("Location: ./page/newSchool.php?status=$status&message=تم اضافة المدرسة بنجاح");
} else {

    $status = "error";
    header("Location: ./page/newSchool.php?status=$status&message=حدث خطأ");
}
