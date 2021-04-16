<?php 
require 'dbconnection.php'; 

$name = $_POST['name'] ;

$query = "INSERT INTO categories (name) VALUES ('$name')";
$result =  mysqli_query($conn , $query);

if($result){
  header('location:page/homeAdmin.php');
}
else {
    echo "error" ; 
    echo mysqli_error ($conn);
}

?>