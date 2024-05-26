<?php

require 'header.php';
$db = new MyDB();

if (!isset($_COOKIE['id'])) {

    header("location:signin.php");
}
?>

<div class=" text-right">


    <?php
    if (isset($_GET['message']) && isset($_GET['status'])) {
        // Get the message and status from the query parameters
        $message = $_GET['message'];
        $status = $_GET['status'];

        // Display the message based on the status
        if ($status === "success") {
            echo '<div class="alert alert-success alert-dismissible" role="alert">' . $message . '<span type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></span>' . '</div>';
        } elseif ($status === "error") {
            echo '<div class="alert alert-danger alert-dismissible" role="alert">' . $message . '<span type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></span>' . '</div>';
        }
    }
    ?>
    <div class="container text-center mt-5 color-white">
        <h1>الحصص الجارية</h1>
    </div>

    <?php
    $opensec = $db->getAllOpenSessions($_COOKIE['id']);

    if (!count($opensec)) {
        ?>
        <div class="container text-center">
            <div class="alert alert-info ">
                <h5>لا توجد حصص جارية</h5>
            </div>
        </div>
        <?php

    } else {

        foreach ($opensec as $row) {
            ?>
            <div class="row m-5 ">
                <div class="col-md-2 shadow p-3  bg-body rounded">
                    <img class="img-fluid" src="../upload/<?php echo $row['img']; ?> " alt="">
                </div>
                <div class="col-md-10 shadow p-3  bg-body rounded  panel panel-default ">
                    <div class="row m-5">
                        <div class="col-md-5">
                            <h3>أسماء الطلبة:  </h3>
                            <ul>
                            <?php
                            $studentsNames = explode(',',$row['student_names']);
                            foreach ($studentsNames as $stName){
                                echo '<li>'.$stName.'</li>';
                            }
                            ?>
                            </ul>
                            <p style="color:gray">اسم المعلم: <?php echo $row['teacher_name']; ?> </p>
                        </div>
                        <div class="col-md-5">

                            <h5 style="color : green"> وقت بدء الحصة: <?php echo $row['enter']; ?> </h5>
                            <br>
                            <h5 style="color : green"> الحصة: <?php echo $row['session_name']; ?> </h5>

                            <form action="singleRequest.php" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="id" value="<?php echo $row['tec_id']; ?>">
                                <button type="submit" value="3" name="status"
                                        class="btn btn-outline-info text-center">
                                    تفاصيل→
                                </button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
    }
    ?>

    <?php
    $tec_id = $_COOKIE['id'];
    $rr = $_COOKIE['role'];
    if ($rr == 1) {
    } else {
        header("location:signin.php");
    }
    ?>

    <div class=" text-right" dir="rtl">
        <br>
        <br>
        <br>
        <br>

        <h1 class="text-center" style="font-family: Cairo">الطلاب والمعلمين</h1>
        <br>
        <br>

        <ul class="nav nav-tabs justify-content-center mb-4">
            <li class="nav-item">
                <a class="nav-link active" id="students-tab" data-toggle="tab" href="#students" role="tab"
                   aria-controls="students" aria-selected="true">قائمة الطلاب</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="teachers-tab" data-toggle="tab" href="#teachers" role="tab"
                   aria-controls="teachers" aria-selected="false">قائمة المعلمين</a>
            </li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane fade show active" id="students" role="tabpanel" aria-labelledby="students-tab">
                <div class="row justify-content-center">
                    <div class="ml-3 col-md-auto mb-3  ">
                        <div class="card" style="width:15rem;">
                            <div class="card-header">
                                <img style="height: 220px; object-fit: cover; border-radius: 2%;" class="card-img-top"
                                     src="../upload/student.jpg" alt="Card image cap">
                            </div>
                            <div class="card-body">
                                <h5 class="card-title" style="font-family: 'Cairo'">
                                    <a style="
                                        overflow: hidden;
                                        display: -webkit-box;
                                        -webkit-line-clamp: 1; /* number of lines to show */
                                        line-clamp: 1;
                                        -webkit-box-orient: vertical;
                                        font-family: 'Cairo';
                                        color : blue
                                        "> إضافة طالب جديد</a>
                                </h5>
                                <a href="newMission.php" class="btn btn-success">إضافة</a>
                            </div>
                        </div>
                    </div>

                    <?php
                    $students = $db->getAllStudents($tec_id);
                    foreach ($students as $student) {
                        $teacher_names = explode(',', $student['teacher_names']); // Split teacher names by comma
                        $BOD = $student['class']; // Assuming BOD is the column name for the Date of Birth
                        $InSess = $student['InSess']; // Assuming InSess is the column name for the In Session status
                        ?>
                        <div class="ml-3 col-md-auto mb-3  ">
                            <div class="card" style="width:15rem;">
                                <div class="card-header">
                                    <img style="height: 220px; object-fit: cover; border-radius: 2%;"
                                         class="card-img-top"
                                         src="../upload/<?php echo $student['img']; ?>  " alt="Card image cap">
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title" style="font-family: 'Cairo'">
                                        <a style="
                                            overflow: hidden;
                                            display: -webkit-box;
                                            -webkit-line-clamp: 1; /* number of lines to show */
                                            line-clamp: 1;
                                            -webkit-box-orient: vertical;
                                            font-family: 'Cairo';
                                            color : blue
                                            "> <?php echo $student['name']; ?></a>
                                    </h5>
                                    <p style="
                                        overflow: hidden;
                                        display: -webkit-box;
                                        -webkit-line-clamp: 1; /* number of lines to show */
                                        line-clamp: 1;
                                        -webkit-box-orient: vertical;
                                        font-family: 'Cairo'" class="card-text">رقم
                                        الهاتف: <?php echo $student['phone']; ?>
                                    </p>
                                    <hr>
                                    <p class="card-text">الصف: <?php echo $BOD; ?></p>
                                    <hr>
                                    <p class="card-text">في الحصة: <?php echo $InSess == 1 ? 'نعم' : 'لا'; ?></p>
                                    <hr>
                                    <p class="card-text">المعلمون:
                                    <ol>
                                        <?php foreach ($teacher_names as $teacher_name) { ?>
                                            <li><?php echo $teacher_name; ?></li>
                                        <?php } ?>
                                    </ol>
                                    </p>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-auto">
                                            <form action="editStudent.php" method="POST" enctype="multipart/form-data">
                                                <input type="hidden" name="id" value="<?php echo $student['id']; ?>">
                                                <button type="submit" value="3" name="status"
                                                        class="btn btn-outline-info text-center">تفاصيل→
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>

            <div class="tab-pane fade" id="teachers" role="tabpanel" aria-labelledby="teachers-tab">
                <div class="row justify-content-center">
                    <div class="ml-3 col-md-auto mb-3  ">
                        <div class="card" style="width:15rem;">
                            <div class="card-header">
                                <img style="height: 220px; object-fit: cover; border-radius: 2%;" class="card-img-top"
                                     src="../upload/super.jpg" alt="Card image cap">
                            </div>
                            <div class="card-body">
                                <h5 class="card-title" style="font-family: 'Cairo'">
                                    <a style="
                                        overflow: hidden;
                                        display: -webkit-box;
                                        -webkit-line-clamp: 1; /* number of lines to show */
                                        line-clamp: 1;
                                        -webkit-box-orient: vertical;
                                        font-family: 'Cairo';
                                        color : blue
                                        "> إضافة معلم جديد</a>
                                </h5>
                                <a href="newMission.php" class="btn btn-success">إضافة</a>
                            </div>
                        </div>
                    </div>

                    <?php
                    $teachers = $db->getAllTeachers();

                    foreach ($teachers as $teacher) {
                        $teacher_specializations = $db->getTeacherSpecializationsNames($teacher['id']);
                        $InSess = $teacher['att_id'];
                        ?>
                        <div class="ml-3 col-md-auto mb-3  ">
                            <div class="card" style="width:15rem;">
                                <div class="card-header">
                                    <img style="height: 220px; object-fit: cover; border-radius: 2%;"
                                         class="card-img-top"
                                         src="../upload/<?php echo $teacher['img']; ?>  " alt="Card image cap">
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title" style="font-family: 'Cairo'">
                                        <a style="
                                            overflow: hidden;
                                            display: -webkit-box;
                                            -webkit-line-clamp: 1; /* number of lines to show */
                                            line-clamp: 1;
                                            -webkit-box-orient: vertical;
                                            font-family: 'Cairo';
                                            color : blue
                                            "> <?php echo $teacher['name']; ?></a>
                                    </h5>
                                    <p style="
                                        overflow: hidden;
                                        display: -webkit-box;
                                        -webkit-line-clamp: 1; /* number of lines to show */
                                        line-clamp: 1;
                                        -webkit-box-orient: vertical;
                                        font-family: 'Cairo'" class="card-text">الايميل: <?php echo $teacher['user']; ?>
                                    </p>
                                    <hr>
                                    <p> التخصصات
                                    <ol>
                                        <?php
                                        foreach ($teacher_specializations as $teacher_spec) {
                                            ?>
                                            <li> <?php echo $teacher_spec['spec_name']; ?></li>

                                            <?php
                                        }
                                        ?>
                                    </ol>
                                    </p>
                                    <hr>
                                    <p class="card-text">في الحصة: <?php echo $InSess == 1 ? 'نعم' : 'لا'; ?></p>

                                    <hr>
                                    <div class="row">
                                        <div class="col-md-auto">
                                            <form action="editTeacher.php" method="POST" enctype="multipart/form-data">
                                                <input type="hidden" name="id" value="<?php echo $teacher['id']; ?>">
                                                <button type="submit" value="3" name="status"
                                                        class="btn btn-outline-info text-center">تفاصيل→
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>

            </div>
        </div>

        <?php require 'footer.php'; ?>
