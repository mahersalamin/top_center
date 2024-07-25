<?php

require '../MyDB.php';

$db = new MyDB();


?>

<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Top Center </title>


    <link rel="icon" href="../sysdata/logo.jpg">


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">

    <!-- DataTables Buttons CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css">
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>


    <!-- CSS only -->
    <link rel="stylesheet" href="https://bootswatch.com/4/flatly/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">

    <!-- fontawesome -->
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"
          integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/duotone.css"
          integrity="sha384-R3QzTxyukP03CMqKFe0ssp5wUvBPEyy9ZspCB+Y01fEjhMwcXixTyeot+S40+AjZ" crossorigin="anonymous"/>
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/fontawesome.css"
          integrity="sha384-eHoocPgXsiuZh+Yy6+7DsKAerLXyJmu2Hadh4QYyt+8v86geixVYwFqUvMU8X90l" crossorigin="anonymous"/>


    <!-- JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-p34f1UUtsS3wqzfto5wAAmdvj+osOnFyQFpp4Ua3gs/ZVWx6oOypYoCJhGGScy+8"
            crossorigin="anonymous"></script>


    <!-- style sheet -->
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="sstyle.css">


    <link href="ut/all.min.css" rel="stylesheet" type="text/css">


    <link href="ut/sb-admin-2.min.css" rel="stylesheet">

    <link href="ut/dataTables.bootstrap4.min.css" rel="stylesheet">


    <link
            href="https://fonts.googleapis.com/css?family=Cairo:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
            rel="stylesheet">


    <!--    <link rel="stylesheet" type="text/css" href="../../ViewerJS/dist/viewer.css">-->
    <!--    <script src="../../ViewerJS/dist/viewer.js"></script>-->


</head>

<body>


<?php

if (!isset($_COOKIE['id'])) {
    header("location:signin.php");
} ?>


<?php
$user = $db->getSingleTeacher($_COOKIE['id']);
foreach ($user

         as $row){
?>


<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top ">
    <div class="container">
        <a class="navbar-brand font-weight-bold "><img
                    style="width: 30px; height: 30px; object-fit: cover; border-radius: 50%;"
                    src="../sysdata/super.jpg" alt=""> <?php echo "  |" . $_COOKIE['name']; ?>  </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">

            <ul class="navbar-nav">

                <li class="nav-item"><a class="nav-link " aria-current="page" 3
                                        href= <?php $_COOKIE['role'] == 1 ? print "homeAdmin.php" : print "bodyHomeUser.php" ?>>الرئيسية</a>
                </li>


                <li class="nav-item"><a class="nav-link" aria-current="page" href="userProfile.php">الملف الشخصي</a>
                </li>

                <?php
                if ($_COOKIE['role'] == 1) {
                    ?>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-right" href="#" id="navbarDropdown" role="button"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            إضافة جديد
                        </a>
                        <div class="dropdown-menu text-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="newMission.php">إضافة طالب جديد</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="newMissionTeacher.php">إضافة معلم جديد</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="newMissionSpecialization.php">إضافة تخصص جديد</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="assignToPackages.php">الدورات</a>
                        </div>
                    </li>

                    <li class="nav-item"><a class="nav-link" aria-current="page" href="finance.php">المالية</a>
                    </li>
                <?php }
                if ($_COOKIE['role'] == 2) { ?>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            التقارير
                        </a>
                        <div class="dropdown-menu text-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="mySessions.php">كل الدورات</a>
                            <div class="dropdown-divider"></div>

                            <a class="dropdown-item" href="acceptedMission.php">حصص أُعطيت</a>
                        </div>
                    </li>


                <?php } ?>


                <?php }
                if ($_COOKIE['role'] == 1) { ?>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-right" href="#" id="navbarDropdown" role="button"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            التقارير
                        </a>
                        <div class="dropdown-menu text-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="SessionsReport.php">تقرير الدورات</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="MissionsReports.php">التقرير الشامل</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="MyRequest.php">تقرير الحصص الموافق عليها</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="daily_report.php">تقرير الحضور</a>
                        </div>
                    </li>


                <?php } ?>

                <li class="nav-item"><a class="nav-link" href="../logout.php">تسجيل الخروج</a></li>

            </ul>
        </div>
    </div>
</nav>


<div class="px-4 pt-5 my-5 text-center">
    <img class="d-block mx-auto mb-2 " style="width: 100px; height: 100px; object-fit: cover; border-radius: 50%;"
         src="../sysdata/super.jpg" alt="">
    <h1 class="display-5 fw-bold text-info " style="font-family: 'Cairo'">مرحبا بك في توب سنتر
        يا <?php echo $_COOKIE['name']; ?></h1>
    <div class="col-lg-6 mx-auto">
        <p class="lead mb-4" style="font-family: 'Cairo'">مرحبا بكم في نظام التسجيل وتنظيم الوقت
        </p>

    </div>
</div>


<?php require 'plugs.php'; ?>


