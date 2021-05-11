<?php 

require "MyDB.php";

$user_id =  $_POST['user_id'] ;
$title = $_POST['title'] ;
$description =  $_POST['description'] ;
$salary =  $_POST['salary'] ;
$location = $_POST['location'] ;
$categorie_id = $_POST['category'] ;
$company_name = $_POST['company_name'];
$contact_tele =  $_POST['contact_tele'] ;
$type =  $_POST['type'] ;
$contact_email = $_POST['contact_email'] ;
$image = $_POST['image'] ;




$db = new MyDB ();


$result =  $db->AddJob($user_id,$title,$description,$salary,$location,$categorie_id,$company_name,$contact_tele,$type,$contact_email,$image);


if($result){
  header('location:page/bodyHomeUser.php');
}
else {
    echo "error" ; 

}




?>