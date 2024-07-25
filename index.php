<?php

require 'dbconnection.php';



$query = "select * from teacher";
$result = mysqli_query($conn , $query);

if (mysqli_error($conn)){

    echo mysqli_error($conn) ;

}

else {

    header('location:page/signin.php');
}




?>