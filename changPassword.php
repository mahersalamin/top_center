<?php

require "MyDB.php";

    $id = $_POST['id'];
    $old = $_POST['old'];
    $new = $_POST['new'];


    $db = new MyDB ();
//var_dump($_POST);die();

    $result =  $db-> getPassword($id, $old);
    if($result){
        $result = $result->fetch_assoc();

        if ($result["password"] == $old){

            $rr =  $db-> changPassword($id,$new);

            header("location:page/changePassword.php?status=success&message=تم تغيير كلمة المرور بنجاح");
        }else {

            header("location:page/changePassword.php?status=error&message=فشل تغيير كلمة المرور");

        }
    } else {
        header("location:page/changePassword.php?status=error&message=فشل تغيير كلمة المرور");

    }





?>