<?php 

require 'dbconnection.php';



$query = "select * from jobs"; 
$result = mysqli_query($conn , $query); 

if (mysqli_error($conn)){

    echo mysqli_error($conn) ;

}

else {

    header('page/bodyHome.php');
}




?>