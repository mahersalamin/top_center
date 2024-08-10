<?php

require 'header.php';
$db = new MyDB();

if (!isset($_COOKIE['id'])) {

    header("location:signin.php");
}
?>
<style>
    .archived-teacher {
        opacity: 0.6; /* Makes the card look faded */
        background-color: #f8f9fa; /* Light grey background for archived teachers */
        border: 1px solid #e0e0e0; /* Light border to separate from active teachers */
    }

    /* Optional: Add a badge or indicator for archived status */
    .archived-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        background-color: #dc3545; /* Red badge */
        color: white;
        padding: 5px 10px;
        border-radius: 3px;
        font-size: 0.9rem;
        font-weight: bold;
    }
</style>
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
                            <h3>أسماء الطلبة: </h3>
                            <ul>
                                <?php
                                $studentsNames = explode(',', $row['student_names']);
                                foreach ($studentsNames as $stName) {
                                    echo '<li>' . $stName . '</li>';
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
                                <button type="submit" value="3" name="status" class="btn btn-outline-info text-center">
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

        <hr class="mt-4 mb-4">
        <h1 class="text-center" style="font-family: Cairo">الطلاب والمعلمين</h1>


        <ul class="nav nav-tabs justify-content-around mb-4">
            <li class="nav-item">
                <a class="nav-link active" id="students-tab" data-toggle="tab" href="#students" role="tab"
                   aria-controls="students" aria-selected="true">قائمة الطلاب</a>
            </li>
            <li class="nav-item">
                <a class="nav-link " id="archived-students-tab" data-toggle="tab" href="#archived-students" role="tab"
                   aria-controls="archived-students" aria-selected="false">أرشيف الطللاب</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="teachers-tab" data-toggle="tab" href="#teachers" role="tab"
                   aria-controls="teachers" aria-selected="false">قائمة المعلمين</a>
            </li>
            <li class="nav-item">
                <a class="nav-link " id="archived-teachers-tab" data-toggle="tab" href="#archived-teachers" role="tab"
                   aria-controls="archived-teachers" aria-selected="false">أرشيف المعلمين</a>
            </li>

        </ul>

        <div class="tab-content">
            <div class="tab-pane fade show active" id="students" role="tabpanel" aria-labelledby="students-tab">
                <div class="container text-center">
                    <!-- Search bar for students -->
                    <label for="studentSearchInput"></label><input type="text" class="form-control"
                                                                   id="studentSearchInput" placeholder="بحث عن طالب..."
                                                                   onkeyup="filterStudents()">
                </div>
                <div class="row justify-content-center" id="studentContainer">
                    <!-- Example card for adding new student -->
                    <div class="ml-3 col-md-auto mb-3">
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
                    $students = $db->getAllStudents();
                    foreach ($students as $student) {
                        $teacher_names = explode(',', $student['teacher_names']);
                        $BOD = $student['class'];
                        $InSess = $student['InSess'];
                        if ($student['archived'] == 0) {
                            ?>
                            <div class="ml-3 col-md-auto mb-3 student-card">
                                <div class="card" style="width:15rem;">
                                    <div class="card-header">
                                        <img style="height: 220px; object-fit: cover; border-radius: 2%;"
                                             class="card-img-top" src="../upload/<?php echo $student['img']; ?>"
                                             alt="Card image cap">
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
                                                <form action="editStudent.php" method="POST"
                                                      enctype="multipart/form-data">
                                                    <input type="hidden" name="id"
                                                           value="<?php echo $student['id']; ?>">
                                                    <button type="submit" value="3" name="status"
                                                            class="btn btn-outline-info text-center">تفاصيل→
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php }
                    } ?>
                </div>
            </div>

            <div class="tab-pane fade" id="archived-students" role="tabpanel" aria-labelledby="archived-students-tab">
                <div class="container text-center">
                    <!-- Search bar for students -->
                    <label for="studentSearchInput"></label><input type="text" class="form-control"
                                                                   id="studentSearchInput" placeholder="بحث عن طالب..."
                                                                   onkeyup="filterStudents()">
                </div>
                <div class="row justify-content-center" id="studentContainer">

                    <?php
                    $students = $db->getAllStudents();
                    foreach ($students as $student) {
                        $teacher_names = explode(',', $student['teacher_names']);
                        $BOD = $student['class'];
                        $InSess = $student['InSess'];
                        if ($student['archived'] == 1) {
                            ?>
                            <div class="ml-3 col-md-auto mb-3 student-card">
                                <div class="card" style="width:15rem;">
                                    <div class="card-header">
                                        <img style="height: 220px; object-fit: cover; border-radius: 2%;"
                                             class="card-img-top" src="../upload/<?php echo $student['img']; ?>"
                                             alt="Card image cap">
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
                                        <hr>

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

                                        <div class="row">
                                            <div class="col-md-auto">
                                                <form action="editStudent.php" method="POST"
                                                      enctype="multipart/form-data">
                                                    <input type="hidden" name="id"
                                                           value="<?php echo $student['id']; ?>">
                                                    <button type="submit" value="3" name="status"
                                                            class="btn btn-outline-info text-center">تفاصيل→
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php }
                    } ?>
                </div>
            </div>

            <div class="tab-pane fade" id="teachers" role="tabpanel" aria-labelledby="teachers-tab">
                <div class="row justify-content-center">
                    <div class="container text-center">
                        <!-- Search bar for teachers -->
                        <input type="text" class="form-control" id="teacherSearchInput" placeholder="بحث عن معلم..."
                               onkeyup="filterTeachers()">
                    </div>
                    <div class="row justify-content-center" id="teacherContainer">
                        <!-- Example card for adding new teacher -->
                        <div class="ml-3 col-md-auto mb-3">
                            <div class="card" style="width:15rem;">
                                <div class="card-header">
                                    <img style="height: 220px; object-fit: cover; border-radius: 2%;"
                                         class="card-img-top" src="../upload/super.jpg" alt="Card image cap">
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
                        $teachers = $db->getAllTeachers(); // Get all teachers including archived ones
                        foreach ($teachers as $teacher) {
                            // Determine if teacher is archived
                            $isArchived = $teacher['is_archived'] ? 'archived-teacher' : '';
                            $archivedBadge = $teacher['is_archived'] ? '<span class="archived-badge">في الأرشيف</span>' : '';
                            if ($teacher['is_archived'] == 0) {
                                ?>
                                <div class="ml-3 col-md-auto mb-3 teacher-card <?php echo $isArchived; ?>">
                                    <div class="card" style="width:15rem;">
                                        <div class="card-header">
                                            <img style="height: 220px; object-fit: cover; border-radius: 2%;"
                                                 class="card-img-top"
                                                 src="../upload/<?php echo htmlspecialchars($teacher['img']); ?>"
                                                 alt="Card image cap">
                                        </div>
                                        <div class="card-body">
                                            <h5 class="card-title" style="font-family: 'Cairo'">
                                                <?php echo $archivedBadge; ?>
                                                <a style="
                                    overflow: hidden;
                                    display: -webkit-box;
                                    -webkit-line-clamp: 1; /* number of lines to show */
                                    line-clamp: 1;
                                    -webkit-box-orient: vertical;
                                    font-family: 'Cairo';
                                    color : blue
                                "> <?php echo htmlspecialchars($teacher['name']); ?></a>
                                            </h5>
                                            <hr>
                                            <div class="row">
                                                <div class="col-md-auto">
                                                    <form action="editTeacher.php" method="POST"
                                                          enctype="multipart/form-data">
                                                        <input type="hidden" name="id"
                                                               value="<?php echo htmlspecialchars($teacher['id']); ?>">
                                                        <button type="submit" value="3" name="status"
                                                                class="btn btn-outline-info text-center">تفاصيل→
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php }
                        } ?>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="archived-teachers" role="tabpanel" aria-labelledby="archived-teachers-tab">
                <div class="row justify-content-center">
                    <div class="container text-center">
                        <!-- Search bar for teachers -->
                        <input type="text" class="form-control" id="teacherSearchInput" placeholder="بحث عن معلم..."
                               onkeyup="filterTeachers()">
                    </div>
                    <div class="row justify-content-center" id="teacherContainer">


                        <?php
                        $teachers = $db->getAllTeachers(); // Get all teachers including archived ones
                        foreach ($teachers as $teacher) {
                            // Determine if teacher is archived
                            $isArchived = $teacher['is_archived'] ? 'archived-teacher' : '';
                            $archivedBadge = $teacher['is_archived'] ? '<span class="archived-badge">في الأرشيف</span>' : '';
                            if ($teacher['is_archived'] == 1) {
                                ?>
                                <div class="ml-3 col-md-auto mb-3 teacher-card <?php echo $isArchived; ?>">
                                    <div class="card" style="width:15rem;">
                                        <div class="card-header">
                                            <img style="height: 220px; object-fit: cover; border-radius: 2%;"
                                                 class="card-img-top"
                                                 src="../upload/<?php echo htmlspecialchars($teacher['img']); ?>"
                                                 alt="Card image cap">
                                        </div>
                                        <div class="card-body">
                                            <h5 class="card-title" style="font-family: 'Cairo'">
                                                <?php echo $archivedBadge; ?>
                                                <a style="
                                    overflow: hidden;
                                    display: -webkit-box;
                                    -webkit-line-clamp: 1; /* number of lines to show */
                                    line-clamp: 1;
                                    -webkit-box-orient: vertical;
                                    font-family: 'Cairo';
                                    color : blue
                                "> <?php echo htmlspecialchars($teacher['name']); ?></a>
                                            </h5>
                                            <hr>
                                            <div class="row">
                                                <div class="col-md-auto">
                                                    <form action="editTeacher.php" method="POST"
                                                          enctype="multipart/form-data">
                                                        <input type="hidden" name="id"
                                                               value="<?php echo htmlspecialchars($teacher['id']); ?>">
                                                        <button type="submit" value="3" name="status"
                                                                class="btn btn-outline-info text-center">تفاصيل→
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php }
                        } ?>
                    </div>
                </div>
            </div>



        </div>

        <?php require 'footer.php'; ?>

        <script>
            function filterStudents() {
                let searchInput = document.getElementById('studentSearchInput').value.toUpperCase();
                let studentCards = document.querySelectorAll('.student-card');

                studentCards.forEach(function (card) {
                    let studentName = card.querySelector('.card-title').textContent.toUpperCase();
                    if (searchInput === "" || studentName.includes(searchInput)) {
                        card.style.display = 'flex';
                    } else {
                        card.style.display = 'none';
                    }
                });
            }

            function filterTeachers() {
                let searchInput = document.getElementById('teacherSearchInput').value.toUpperCase();
                let teacherCards = document.querySelectorAll('.teacher-card');

                teacherCards.forEach(function (card) {
                    let teacherName = card.querySelector('.card-title').textContent.toUpperCase();
                    if (searchInput === "" || teacherName.includes(searchInput)) {
                        card.style.display = 'flex';
                    } else {
                        card.style.display = 'none';
                    }
                });
            }
        </script>