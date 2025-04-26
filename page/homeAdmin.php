<?php

require 'header.php';
$db = new MyDB();
$tec_id = $_COOKIE['id'];
$role = $_COOKIE['role'];
if (!isset($_COOKIE['id'])) {

    header("location:signin.php");
}
?>
<style>


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

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $response = "";
        if (isset($_POST['login'])) {
            $response = $db->logSecretaryLogin($tec_id);
        }

        if (isset($_POST['logout'])) {
            $response = $db->logSecretaryLogout($tec_id);
        }

        // Pass the response to the client-side for alert
        echo "<script>
        alert('$response');
        window.location.href = window.location.href; // Refresh the page after showing the alert
    </script>";
    }


    ?>
    <?php
    
        if($role == 3){

    ?>
    <div class="container" style="text-align: center; margin-top: 20px;">
        <form method="POST">
            <button type="submit" name="login" class="btn btn-primary" style="margin: 10px;">تسجيل دخول</button>
            <button type="submit" name="logout" class="btn btn-danger" style="margin: 10px;">تسجيل خروج</button>
        </form>
    </div>
<?php }
    ?>
    <div class="container text-center mt-5 color-white">
        <h1>الحصص الجارية</h1>
    </div>

    <?php
    $opensec = $db->getAllOpenSessions($_COOKIE['id']);

    if (!count($opensec)) {
        ?>
        <div class="container text-center">
            <div class="alert alert-info">
                <h5>لا توجد حصص جارية</h5>
            </div>
        </div>
        <?php
    } else {
        foreach ($opensec as $index => $row) {
            $collapseId = 'collapseSession_' . $index;
            ?>
            <div class="row m-5">
                <!-- Collapsible Header -->
                <div class="col-12 shadow p-3 bg-body rounded mb-2" data-bs-toggle="collapse" href="#<?php echo $collapseId; ?>" role="button" aria-expanded="false" aria-controls="<?php echo $collapseId; ?>" style="cursor: pointer;">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><?php echo $row['session_name']; ?></h5>
                        <span class="badge bg-primary"><?php echo count(explode(',', $row['student_names'])); ?> طلاب</span>
                    </div>
                    <small class="text-muted">معلم: <?php echo $row['teacher_name']; ?></small>
                </div>

                <!-- Collapsible Content -->
                <div class="collapse col-12" id="<?php echo $collapseId; ?>">
                    <div class="card card-body shadow-sm">
                        <div class="row">
                            <div class="col-md-2">
                                <img class="img-fluid rounded" src="../upload/<?php echo $row['img']; ?>" alt="صورة الحصة">
                            </div>
                            <div class="col-md-10">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h4>أسماء الطلبة:</h4>
                                        <ul class="list-group">
                                            <?php
                                            $studentsNames = explode(',', $row['student_names']);
                                            foreach ($studentsNames as $stName) {
                                                echo '<li class="list-group-item">' . htmlspecialchars($stName) . '</li>';
                                            }
                                            ?>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <h5><span class="text-success">وقت بدء الحصة:</span> <?php echo $row['enter']; ?></h5>
                                        </div>
                                        <div class="mb-3">
                                            <h5><span class="text-success">نوع الحصة:</span> <?php echo $row['session_name']; ?></h5>
                                        </div>
                                        <form action="singleRequest.php" method="POST" enctype="multipart/form-data">
                                            <input type="hidden" name="id" value="<?php echo $row['tec_id']; ?>">
                                            <button type="submit" value="3" name="status" class="btn btn-info">
                                                <i class="fas fa-info-circle"></i> تفاصيل الحصة
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
    }
    ?>

    <?php
    
    if ($role == 1 || $role == 3) {
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
                <div class="text-center m-2">
                    <!-- Search bar for students -->
                    <label for="studentSearchInput"></label>
                    <input type="text" class="form-control"
                       id="studentSearchInput" placeholder="بحث عن طالب..."
                       onkeyup="filterStudents()">
                    <label for="classFilter"></label>
                    <select class="form-control" id="classFilter" onchange="filterStudents()">
                        <option value="">جميع الصفوف</option>

                    </select>
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

                                        <div class="row">
                                            <div class="col-md-auto">
                                                <form action="editStudent.php" method="POST"
                                                      enctype="multipart/form-data">
                                                    <input type="hidden" name="id"
                                                           value="<?php echo $student['id']; ?>">
                                                    <button type="submit" value="3" name="status"
                                                            class="btn btn-outline-info text-center">تفاصيل ←
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
                <div class="text-center m-2">
                    <!-- Search bar for students -->
                    <label for="studentSearchInput"></label>
                    <input type="text" class="form-control" id="studentSearchInput" placeholder="بحث عن طالب..."
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
                                                            class="btn btn-outline-info text-center">تفاصيل ←
                                                    </button>
                                                </form>
                                            </div>
                                            <div class="col-md-auto">
                                                <form action="unarchiveStudent.php" method="POST"
                                                      enctype="multipart/form-data">
                                                    <input type="hidden" name="id"
                                                           value="<?php echo htmlspecialchars($student['id']); ?>">
                                                    <button type="submit" name="status"
                                                            class="btn btn-danger text-center">استعادة
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
                    <div class="text-center m-2">
                        <!-- Search bar for teachers -->
                        <label for="teacherSearchInput"></label><input type="text" class="form-control" id="teacherSearchInput" placeholder="بحث عن معلم..."
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
                                    <a href="newMissionTeacher.php" class="btn btn-success">إضافة</a>
                                </div>
                            </div>
                        </div>

                        <?php
                        $teachers = $db->getAllTeachers(); // Get all teachers including archived ones
                        foreach ($teachers as $teacher) {
                            // Determine if teacher is archived
                            $isArchived = $teacher['is_archived'] ? 'archived-teacher' : '';
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
                                                                class="btn btn-outline-info text-center">تفاصيل ←
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
                    <div class="text-center m-2">
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
                                                                class="btn btn-outline-info text-center">تفاصيل ←
                                                        </button>
                                                    </form>

                                                </div>
                                                <div class="col-md-auto">
                                                    <form action="unarchiveTeacher.php" method="POST"
                                                          enctype="multipart/form-data">
                                                        <input type="hidden" name="id"
                                                               value="<?php echo htmlspecialchars($teacher['id']); ?>">
                                                        <button type="submit" name="status"
                                                                class="btn btn-danger text-center">استعادة
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
            document.addEventListener('DOMContentLoaded', function() {
                const studentCards = document.querySelectorAll('.student-card');
                const classSet = new Set();

                // Extract all unique class values
                studentCards.forEach(card => {
                    const classText = card.querySelector('.card-text:nth-of-type(2)').textContent;
                    const studentClass = classText.replace('الصف: ', '').trim();
                    if (studentClass) {
                        classSet.add(studentClass);
                    }
                });

                // Convert set to array and sort numerically
                const classFilter = document.getElementById('classFilter');
                const classes = Array.from(classSet);

                // Custom sorting function for mixed numeric and non-numeric classes
                classes.sort((a, b) => {
                    // Try to convert to numbers first
                    const numA = parseInt(a, 10);
                    const numB = parseInt(b, 10);

                    // If both are numbers, compare numerically
                    if (!isNaN(numA) && !isNaN(numB)) {
                        return numA - numB;
                    }
                    // If one is number and one isn't, numbers come first
                    else if (!isNaN(numA)) {
                        return -1;
                    }
                    else if (!isNaN(numB)) {
                        return 1;
                    }
                    // If neither is number, compare as strings
                    else {
                        return a.localeCompare(b);
                    }
                });

                // Populate the dropdown with sorted classes
                classes.forEach(cls => {
                    const option = document.createElement('option');
                    option.value = cls;
                    option.textContent = cls;
                    classFilter.appendChild(option);
                });
            });

            function filterStudents() {
                const searchInput = document.getElementById('studentSearchInput').value.toUpperCase();
                const classFilter = document.getElementById('classFilter').value;
                const studentCards = document.querySelectorAll('.student-card');

                studentCards.forEach(card => {
                    const studentName = card.querySelector('.card-title a').textContent.toUpperCase();
                    const classText = card.querySelector('.card-text:nth-of-type(2)').textContent;
                    const studentClass = classText.replace('الصف: ', '').trim();

                    // Check both filters
                    const nameMatch = searchInput === "" || studentName.includes(searchInput);
                    const classMatch = classFilter === "" || studentClass === classFilter;

                    card.style.display = (nameMatch && classMatch) ? 'flex' : 'none';
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