<?php 


require 'dbconnection.php'; 


$user_name =  $_POST['user_name'] ;
$email = $_POST['email'] ;
$password =  $_POST['password'] ;
$address =  $_POST['address'] ;
$telephone = $_POST['telephone'] ;


$query = "INSERT INTO users (user_name,email,password,address,telephone) VALUES ('$user_name','$email','$password','$address','$telephone')";


$result =  mysqli_query($conn , $query);


if($result){

  header('location:page/singin.php');

}
else {

    echo "error" ; 
    echo mysqli_error ($conn);
}





?>