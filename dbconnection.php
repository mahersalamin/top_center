<?php 

$servername = "localhost"; 
$username = "root";
$password = "";
$database = "xjobdata";



$conn = mysqli_connect( $servername  , $username , $password , $database );

if (!$conn){

    die("can't connect to database " . mysqli_connect_error());
}



?> 