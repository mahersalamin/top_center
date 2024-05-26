<?php require 'header.php'; ?>

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Student Data</title>
        <style>
            /* Style for student cards */
            .student-card {
                border: 1px solid #ccc;
                border-radius: 8px;
                padding: 20px;
                margin: 10px;
                width: 200px;
                display: inline-block;
            }

            .student-card img {
                max-width: 100%;
                border-radius: 8px;
            }

            .student-card h3 {
                margin-top: 10px;
                font-size: 18px;
            }

            .student-card p {
                font-size: 14px;
                margin: 5px 0;
            }
        </style>
    </head>


<?php
$d = date('d/m/Y');


$db = new MyDB();
$mis = $db->getTeacherOpenSessions($_COOKIE['id']);

if (count($mis)) {
    ?>
    <div class="container text-center mt-5 color-white">
        <h1> حصص غير منتهية</h1>
    </div>

    <?php
}

foreach ($mis as $row) {


    ?>

    <div class="row m-5  text-right" dir="rtl">
        <div class="col-md-10 shadow p-3  bg-body rounded  panel panel-default ">

            <div class="col-md-10">
                <h3>اسم الطالب : <?php echo $row['sname']; ?> </h3>
                <p style="color:gray">اسم المعلم: <?php echo $row['teacher_name']; ?> </p>


                <?php
                if ($row['date'] > $d) {
                    ?>
                    <h5 style="color : green"> التاريخ: <?php echo $row['date']; ?> </h5>
                <?php } elseif ($row['date'] < $d) {
                    ?>
                    <h5 style="color : red"> البداية :<?php echo $row['enter']; ?> </h5>
                    <?php
                } elseif ($row['date'] == $d) {

                    ?>
                    <h5 style="color : yellow"> النهاية :<?php echo $row['end']; ?> </h5>
                <?php } ?>


                <form method="POST" action="singleRequest.php">
                    <input type="hidden" name="id" value="<?php echo $row['tec_id']; ?>">
                    <button type="submit" class="btn btn-outline-success">عرض التفاصيل</button>
                </form>


            </div>


        </div>
        <div class="col-md-2 shadow p-3  bg-body rounded">
            <img class="img-fluid" src="../upload//<?php echo $row['img'] ?>" alt="">
        </div>
    </div>
    <?php
}

?>


<?php

$teacherStudents = $db->getTeacherAllStudents($_COOKIE['id']);


?>

    <div class="container text-center">
        <div class="row">
            <div class="col-sm-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">الدورات الخاصة</h5>
                    </div>
                    <div class="card-footer">
                        <a href="teacherPrivateSessions.php" class="btn btn-primary">اذهب</a>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">الحقائب المدرسية</h5>
                    </div>
                    <div class="card-footer">
                        <a href="teacherBackPack.php" class="btn btn-primary">اذهب</a>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">الاشتراكات الشهرية</h5>
                    </div>
                    <div class="card-footer">
                        <a href="teacherMSubs.php" class="btn btn-primary">اذهب</a>
                    </div>
                </div>
            </div>
        </div>

    </div>


<?php require 'footer.php'; ?>