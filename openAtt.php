
<?php
require "MyDB.php";
//echo json_encode($_POST);die();
$db = new MyDB();
$sName = '';
$material = $_POST['material'];
$teacher_id = $_POST['teacher_id'];

if(isset($_POST['student_name'])){
    $sName =  $_POST['student_name'];
}else{
    $sName=$_POST['student_names'];
    if(is_array($sName)){
        $sName = implode(",",$sName);
    }
}
$id = $_POST['pSessionID'];
$type = $_POST['type'];

//var_dump($sName);die();
$result3 =  $db->OpenATT($id, $teacher_id,$sName,$material);

if ($result3) {
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

    echo "error";
}



?>