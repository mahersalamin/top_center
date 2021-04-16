<?php 
require 'dbconnection.php'; 

if (isset($_POST['email']) && isset($_POST['password']) )

$email = $_POST['email'] ;
$password =  $_POST['password'] ;


$query = "SELECT * FROM users 
WHERE email='$email' AND password='$password'";


$result =  mysqli_query($conn , $query);

if(mysqli_num_rows($result) > 0 ){
  header('location:page/bodyHomeUser.php');
}
else {

    echo "error PASSWORD OR EMAIL IS INVALID !" ; 
   
}





?>