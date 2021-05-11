<?php 
require "MyDB.php";

$name = $_POST['name'] ;

$db = new MyDB();
$result =  $db-> AddCategory($name);


if($result){
  header('location:page/homeAdmin.php');
}
else {
    echo "error" ; 
}

?>