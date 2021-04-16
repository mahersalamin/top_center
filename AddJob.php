<?php 


require 'dbconnection.php'; 


$user_id =  $_POST['user_id'] ;
$title = $_POST['title'] ;
$description =  $_POST['description'] ;
$salary =  $_POST['salary'] ;
$location = $_POST['location'] ;
$contact_tele =  $_POST['contact_tele'] ;
$categorie_id = $_POST['category'] ;
$type =  $_POST['type'] ;
$contact_email = $_POST['contact_email'] ;
$company_name = $_POST['company_name'];
$image = $_POST['image'] ;



$query = "INSERT INTO jobs (user_id,title,description,salary,location,categorie_id,company_name,contact_tele,type,contact_email,image) VALUES ($user_id,'$title','$description',$salary,'$location',$categorie_id,'$company_name','$contact_tele',$type,'$contact_email','$image')";


$result =  mysqli_query($conn , $query);


if($result){

  header('location:page/bodyHomeUser.php');

}
else {

    echo "error" ; 
    echo mysqli_error ($conn);
}





?>