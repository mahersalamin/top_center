<?php
   
   require 'dbconnection.php';
if (isset($_POST['id'])){

    $id = $_POST['id'];

       
$query = "SELECT * FROM jobs  WHERE id = $id";
$result =  mysqli_query($conn , $query);
$row = mysqli_fetch_array($result);
    $spo = $row[13];


    if ($spo == 1)
    {
        $query = "UPDATE jobs SET sponsored =0  WHERE id = $id";

    }

    else {
        $query = "UPDATE jobs SET sponsored =1  WHERE id = $id";

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
    header("location:page/homeAdmin.php");
}


?>