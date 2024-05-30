<?php


require "MyDB.php";


$name = $_POST['name'];


$db = new MyDB();



$result =  $db->AddSpc($name);


if ($result) {


  header('location:page/AddSuccess.php');
} else {

  echo  error_reporting($result);
}
