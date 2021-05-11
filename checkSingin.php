<?php 


require 'dbconnection.php'; 





if (isset($_POST['email']) && isset($_POST['password']) ){
 
$email = $_POST['email'] ;
$password =  $_POST['password'] ;

  
$query = "SELECT * FROM users WHERE email='$email' AND password='$password'";

  $result = mysqli_query($conn, $query);
  

  foreach($result as $row){

     $Uid = $row['id'];
     $name =  $row['user_name'];
     $role =  $row['role'];

  }

  if (mysqli_num_rows($result) > 0){

      setcookie("id", $Uid, time() + 1000);
      setcookie("name", $name, time() + 1000);
      setcookie("role", $role, time() + 1000);


      if($role == 1 ){
        header("location:page/homeAdmin.php");
        
      }
       else if ($role == 2){
        header("location:page/postJob.php");
      }
      else {
        header("location:page/bodyHomeUser.php");
      }

      
  }
  else{
      
      header("location:page/singin.php?error=1");
  }
}





?>