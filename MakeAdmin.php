<?php

require "MyDB.php";

if (isset($_POST['id'])){

    $id = $_POST['id'];
    $role = $_POST['role'];
    

    $db = new MyDB ();
    $result =  $db-> MakeAdmin($id , $role);

    if($result){
        header("location:page/homeAdmin.php"); 
    }
    else{
        echo "Something went wrong!";  
    }
}
else{
    header("location:page/homeAdmin.php");
}


?>