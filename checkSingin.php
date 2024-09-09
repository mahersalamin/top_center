<?php


require 'dbconnection.php';


if (isset($_POST['email']) && isset($_POST['password'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare the statement
    $stmt = $conn->prepare("SELECT * FROM teacher WHERE user = ? AND password = ?");
    $stmt->bind_param("ss", $email, $password); // Bind parameters

    // Execute the statement
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $Uid = $row['id'];
        $name = $row['name'];
        $role = $row['role'];
        $is_archived = $row['is_archived'];

        if ($is_archived == 1) {
            header("location:page/signin.php?error=2");
        } else {
            setcookie("id", $Uid, time() + 2000);
            setcookie("name", $name, time() + 2000);
            setcookie("role", $role, time() + 2000);

            if ($role == 1) {
                header("location:page/homeAdmin.php");
            } else {
                header("location:page/bodyHomeUser.php");
            }
        }
    } else {
        header("location:page/signin.php?error=1");
    }

}