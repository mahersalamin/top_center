<?php

require '../MyDB.php';

$db = new MyDB();


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>X-Jobs </title>


 <!-- CSS only -->
    <link rel="stylesheet" href="https://bootswatch.com/4/flatly/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">

    <!-- fontawesome -->
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/duotone.css" integrity="sha384-R3QzTxyukP03CMqKFe0ssp5wUvBPEyy9ZspCB+Y01fEjhMwcXixTyeot+S40+AjZ" crossorigin="anonymous"/>
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/fontawesome.css" integrity="sha384-eHoocPgXsiuZh+Yy6+7DsKAerLXyJmu2Hadh4QYyt+8v86geixVYwFqUvMU8X90l" crossorigin="anonymous"/>




<!-- google font -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap" rel="stylesheet">



<!-- style sheet -->
<link rel="stylesheet" href="style.css">




</head>
<body>




<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container">
            <a class="navbar-brand " href="homeAdmin.php"><img style="width: 50px; height: 70p; "
                    src="https://i.ibb.co/1qNQN1b/xjob.png" alt=""></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item"> <a class="nav-link active" aria-current="page" href="bodyHomeUser.php">Home</a></li>
                    <li class="nav-item"> <a class="nav-link" href="postJob.php"> post a job</a></li>
                    <li class="nav-item"> <a class="nav-link" href="#">log out</a> </li>
                    
                </ul>
            </div>
        </div>
    </nav>








<div class="px-4 pt-5 my-5 text-center">
  <img class="d-block mx-auto mb-2 "  src="https://i.ibb.co/XxShZJx/xjob-BLACK.png" alt=""  width="100" height="100">
  <h1 class="display-5 fw-bold">WELCOM TO XJOBS</h1>
  <div class="col-lg-6 mx-auto">
    <p class="lead mb-4" >We help you choose the best suitable job for you in all fields. We are happy and appreciative of your choice of our company XJobs We hope that you will find your dream job opportunity with us.</p>
   
  </div>
</div>



