<?php
require "MyDB.php";

$sessionId = $_POST['id'];
$type = $_POST['type'];


$db = new MyDB ();
$result = $db->endSession($sessionId);

if ($result) {

    if ($_COOKIE['role'] == 1) {
        header("location:page/homeAdmin.php");
    } else {
        switch ($type){
            case 'دورة خاصة':
                header("location:page/teacherPrivateSessions.php");
                break;
            case 'اشتراك شهري':
                header("location:page/teacherMSubs.php");
                break;
            case 'حقيبة مدرسية':
                header("location:page/teacherBackPack.php");
                break;
        }
    }
} else {

    echo "Something went wrong!";
}


?>