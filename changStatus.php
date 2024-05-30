
<?php
require "MyDB.php";

// var_dump($_POST);die();
$status = $_POST['status'];
$id = $_POST['row_id'];


    $db = new MyDB ();
    $result =  $db-> changStatus($id,$status);

    if($result){
        if ($_COOKIE['role'] == 1) {
            header("location:page/MissionsReports.php");
        } else {
            header("location:page/acceptedMission.php");
        }
        // header("location:page/acceptedMission.php"); 
    }
    else{
        echo "Something went wrong!";  
    }





?>