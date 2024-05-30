<?php
require "MyDB.php";






$from = $_POST['from'] ;
$title = $_POST['title'];
$des =  $_POST['description'] ;


$file_name = $_FILES['file']['name'];
$file_size = $_FILES['file']['size'];
$file_tmp_name = $_FILES['file']['tmp_name'];
$error= $_FILES['file']['error'];


$file_ex = pathinfo($file_name , PATHINFO_EXTENSION);
$file_ex_lc = strtolower($file_ex);


$rr = new MyDB ();



if (isset($_FILES['file']) && $file_ex_lc){

    $new_file_name = uniqid("file-", true) . 'top' .$file_ex_lc;
    $file_upload_path ='upload/'.$new_file_name;
    move_uploaded_file($file_tmp_name,$file_upload_path);

    $result =  $rr->AddReq($from,$title,$des,$new_file_name);  



}
else{
    $result =  $rr->AddReq($from,$title,$des,'');  
}




if($result){

    
 header('location:page/RequestSuccess.php');

}
else {

   
}





