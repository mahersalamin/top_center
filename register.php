<?php 

require "MyDB.php";

$user_name =  $_POST['user_name'] ;
$email = $_POST['email'] ;
$password =  $_POST['password'] ;
$address =  $_POST['address'] ;
$telephone = $_POST['telephone'] ;
$photo = $_POST['photo'] ;
$role = $_POST['role'] ;




$db = new MyDB ();

if ($db->CheckExist($email)){
  header("location:page/singup.php?error=$email");
}
else{
  $result =  $db->AddUser($user_name,$email,$password,$address,$telephone,$role,$photo);
  
}


if($result){

  header('location:page/singin.php');

}
else {
    echo "error" ; 
}


?>