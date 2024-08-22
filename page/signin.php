<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TOP</title>
    <link rel="icon" href="../sysdata/logo.jpg">

    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">

    <!-- JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf"
            crossorigin="anonymous"></script>


    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
          rel="stylesheet">

    <link href="ut/sb-admin-2.min.css" rel="stylesheet">


</head>

<body class="bg-dark">

<div class="container-fluid text-center ">
    <div class="row  mt-5 justify-content-center ">
        <div class="col-md-3 shadow p-3  bg-body rounded m-5">
            <div class="col-md-12">
                <?php

                if (isset($_GET['error'])) {
                    if ($_GET['error'] == 1) { ?>
                        <div class="alert alert-danger" role="alert">
                            الايميل او كلمة المرور غير صحيح
                        </div>
                        <?php
                    }
                    if ($_GET['error'] == 2) {

                        ?>

                        <div class="alert alert-danger" role="alert">
                            حسابك موقوف، يرجى التواصل مع السكرتيرة
                        </div>
                        <?php
                    }
                }
                ?>
            </div>

            <img style="width: 250px; height: auto; object-fit: cover;" src="../upload/top_logo.jpg"
                 alt="">

            <h1 class="text-center m-4 text-success">أهلا بعودتك</h1>


            <form action="../checkSingin.php" method="POST">

                <div class="form-group mb-2 ">
                    <input required type="email" name="email" value="
                         <?php if (isset($_GET['error'])) {
                        if ($_GET['error'] != 1) {
                            echo $_GET['error'];
                        }
                    } ?>
                            " class="form-control" aria-describedby="emailHelp" placeholder="البريد الالكتروني">
                </div>

                <div class="form-group mb-4">
                    <input required type="password" name="password" class="form-control" aria-describedby="emailHelp"
                           placeholder="كلمة المرور">
                </div>

                <button type="submit" class="btn btn-success mb-3  container-fluid fs-4 text-white ">دخول</button>

            </form>

        </div>
</body>

</html>