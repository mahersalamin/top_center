<?php
require "MyDB.php";



if (isset($_POST['id'] )){
  

    $id = $_POST['id'];
    $db = new MyDB();

   
    $result =  $db-> ApproveJob($id);
 

    if($result){
        header("location:page/homeAdmin.php");  
    }
    else{
        echo "Something went wrong!";
    }
}
else{
    header("location:page/SponsoredJob.php");
}


?>