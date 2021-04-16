<?php

if (isset($_POST['id'])){

    $id = $_POST['id'];
    require 'dbconnection.php';

    $query = "DELETE FROM jobs WHERE id=$id";

    $result = mysqli_query($conn, $query);

    if($result){
        header("location:page/homeAdmin.php");
        
    }
    else{
        echo "Something went wrong!";
        echo mysqli_error($conn);
    }
}
else{
    header("location:page/homeAdmin.php");
}


?>