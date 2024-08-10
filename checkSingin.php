<?php


require 'dbconnection.php';


if (isset($_POST['email']) && isset($_POST['password'])) {

    $email = $_POST['email'];
    $password = $_POST['password'];


    $Semail = mysqli_real_escape_string($conn, $email);
    $Spassword = mysqli_real_escape_string($conn, $password);


    $query = "SELECT * FROM teacher WHERE user='$Semail' AND password='$Spassword'";
    $result = mysqli_query($conn, $query);



    foreach ($result as $row) {

        $Uid = $row['id'];
        $name = $row['name'];
        $role = $row['role'];
        $is_archived = $row['is_archived'];
    }
    if($is_archived==1){
        header("location:page/signin.php?error=2");
    }
    if (mysqli_num_rows($result) > 0) {

        setcookie("id", $Uid, time() + 2000);
        setcookie("name", $name, time() + 2000);
        setcookie("role", $role, time() + 2000);

        if ($role == 1) {

            header("location:page/homeAdmin.php");
        } else if ($role == 2) {
            header("location:page/bodyHomeUser.php");
        } else {
            header("location:page/bodyHomeUser.php");
        }
    } else {

        header("location:page/signin.php?error=1");
    }
}
