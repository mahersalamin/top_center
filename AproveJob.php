<?php
require 'dbconnection.php';


if (isset($_POST['id'] )){

    $id = $_POST['id'];
    
$query = "SELECT * FROM jobs  WHERE id = $id";
$result =  mysqli_query($conn , $query);
$row = mysqli_fetch_array($result);
    $app = $row[12];

    if ($app == 1 ){

        $query = "UPDATE jobs SET approve = 0  WHERE id = $id";

    }
    else {
        $query = "UPDATE jobs SET approve = 1  WHERE id = $id";

    }


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
    header("location:page/SponsoredJob.php");
}


?>