<?php


if(isset($_COOKIE['id'])){
    setcookie("id", "", time() - 3600);
}
header("location:/top/page/signin.php");

?>