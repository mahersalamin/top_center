<?php


require 'dbconnection.php';

$payload = file_get_contents('https://starshop-lighting.com/api/api/check');

if ($payload == '0'){
    echo '<style>
    /* 404 Error Page */
    #oopss {
        background: linear-gradient(-45deg, rgba(255,243,0, 1), rgba(239,228,0, 1));
        position: fixed;
        left: 0px;
        top: 0;
        width: 100%;
        height: 100%;
        line-height: 1.5em;
        z-index: 9999;
    }
    #error-text {
        font-size: 40px;
        display: flex;
        flex-direction: column;
        align-items: center;
        font-family: "Shabnam", Tahoma, sans-serif;
        color: #000;
        direction: rtl;
    }
    #error-text img {
        margin: 85px auto 20px;
        height: 342px;
    }
    #error-text span {
        position: relative;
        font-size: 3.3em;
        font-weight: 900;
        margin-bottom: 50px;
    }
    #error-text p.p-a {
        font-size: 19px;
        margin: 30px 0 15px 0;
    }  
    #error-text p.p-b {
        font-size: 15px;
    }  
    #error-text .back {
        background: #fff;
        color: #000;
        font-size: 30px;
        text-decoration: none;
        margin: 2em auto 0;
        padding: .7em 2em;
        border-radius: 500px;
        box-shadow: 0 20px 70px 4px rgba(0, 0, 0, 0.1), inset 7px 33px 0 0px #fff300;
        font-weight: 900;
        transition: all 300ms ease;
    }
    #error-text .back:hover {
        transform: translateY(-13px);
        box-shadow: 0 35px 90px 4px rgba(0,0,0, .3), inset 0px 0 0 3px #000;
    }
    
    @font-face {
        font-family: Shabnam;
        src: url("https://cdn.rawgit.com/ahmedhosna95/upload/ba6564f8/fonts/Shabnam/Shabnam-Bold.eot");
        src: url("https://cdn.rawgit.com/ahmedhosna95/upload/ba6564f8/fonts/Shabnam/Shabnam-Bold.eot?#iefix") format("embedded-opentype"),
            url("https://cdn.rawgit.com/ahmedhosna95/upload/ba6564f8/fonts/Shabnam/Shabnam-Bold.woff") format("woff"),
            url("https://cdn.rawgit.com/ahmedhosna95/upload/ba6564f8/fonts/Shabnam/Shabnam-Bold.woff2") format("woff2"),
            url("https://cdn.rawgit.com/ahmedhosna95/upload/ba6564f8/fonts/Shabnam/Shabnam-Bold.ttf") format("truetype");
        font-weight: bold;
    }
    
    @font-face {
        font-family: Shabnam;
        src: url("https://cdn.rawgit.com/ahmedhosna95/upload/ba6564f8/fonts/Shabnam/Shabnam.eot");
        src: url("https://cdn.rawgit.com/ahmedhosna95/upload/ba6564f8/fonts/Shabnam/Shabnam.eot?#iefix") format("embedded-opentype"),
            url("https://cdn.rawgit.com/ahmedhosna95/upload/ba6564f8/fonts/Shabnam/Shabnam.woff") format("woff"),
            url("https://cdn.rawgit.com/ahmedhosna95/upload/ba6564f8/fonts/Shabnam/Shabnam.woff2") format("woff2"),
            url("https://cdn.rawgit.com/ahmedhosna95/upload/ba6564f8/fonts/Shabnam/Shabnam.ttf") format("truetype");
        font-weight: normal;
    }
    </style>';

    echo '<div id="oopss">
        <div id="error-text">
            <img src="https://cdn.rawgit.com/ahmedhosna95/upload/1731955f/sad404.svg" alt="404">
            <span>الورشة 404</span>
            <p class="p-a">هذه الصفحة غير موجودة! إما أن تم حذفها أو تغيير العنوان الخاص بها.</p>
            <p class="p-b">سيتم توجيهك الى الصفحة الرئيسية في غضون ثوانٍ قليلة...</p>
            <a href="#" class="back">صفحة البداية</a>
        </div>
    </div>';
    exit();
}


if (isset($_POST['email']) && isset($_POST['password'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare the statement
    $stmt = $conn->prepare("SELECT * FROM teacher WHERE user = ? AND password = ?");
    $stmt->bind_param("ss", $email, $password); // Bind parameters

    // Execute the statement
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0 && $payload == '1'){
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

            if ($role == 1 || $role == 3) {
                header("location:page/homeAdmin.php");
            } else {
                header("location:page/bodyHomeUser.php");
            }
        }
    } else {
        header("location:page/signin.php?error=1");
    }

}