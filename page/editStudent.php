<?php require '../dbconnection.php'; ?>
<?php require 'header.php'; ?>


<?php
$id = $_POST['id'];
$db = new MyDB();
$student = $db->getStudentData($id);


if ($student) {
    ?>


    <div class="px-4 py-5 my-5 text-center">
        <form action="../updateStudent.php" method="POST" enctype="multipart/form-data">
            <div class="col-md-5 shadow p-1 bg-body rounded d-block mx-auto mb-4">
                <img class="img-fluid" src="../upload/<?php echo $student['img']; ?> " alt="">
            </div>

            <h1 class="display-4 fw-bold" style="color: green; display: inline;"><?php echo $student['name']; ?></h1>

            <div class="col-lg-4 mx-auto pt-4">
                <p class="lead mb-4"><?php echo $student['name']; ?></p>

                <div class=" gap-5 ">
                    <ul class="list-group">
                        <li class="list-group-item">
                            <img style="width: 30px; height: 30px; object-fit: cover; border-radius: 20%;"
                                 src="../sysdata/super.jpg" alt="">
                            <h6 style="display: inline; font-weight: bold">| <?php echo $student['name']; ?></h6>
                        </li>
                        <li class="list-group-item">

                            <label for="name" style="display: inline; font-weight: bold">اسم الطالب</label>
                            <input type="text" id="name" name="name" value="<?php echo $student['name']; ?>">
                        </li>
                        <li class="list-group-item">

                            <label for="phone" style="color: black; display: inline; font-weight: bold">رقم الهاتف
                                :</label>
                            <input type="text" id="phone" name="phone" value="<?php echo $student['phone']; ?>">
                        </li>
                        <li class="list-group-item">

                            <p style="color: black; display: inline; font-weight: bold"> المعلمون</p>
                            <?php
                            $all_teacher_names = explode(',', $student['all_teacher_names']);
                            $teacher_names = explode(',', $student['teacher_names']);

                            foreach ($all_teacher_names as $att_teacher) { ?>
                                <div class="mr-2 form-check row justify-content-center">
                                    <input class="form-check-input" type="checkbox" name="emp[]"
                                           id="teacher_<?php echo $att_teacher; ?>"
                                           value="<?php echo $att_teacher; ?>"
                                        <?php echo in_array($att_teacher, $teacher_names) ? 'checked' : ''; // Check if the teacher name exists in the list of selected teacher names ?>
                                    >
                                    <label class="form-check-label" for="teacher_<?php echo $att_teacher; ?>">
                                        <?php echo $att_teacher; ?>
                                    </label>
                                </div>
                            <?php } ?>
                        </li>
                        <li class="list-group-item">

                            <p style="color: black; display: inline; font-weight: bold">الدورات</p>
                            <?php
                            $s_sessions = $db->getStudentSessions($id);


                            foreach ($s_sessions as $session) { ?>
                                <div class="mr-2 row justify-content-center">
                                    <label class="form-check-label" for="session_<?php echo $session['session_name'];
                                    ?>">
                                        <?php echo $session['type']; ?>
                                    </label>
                                    <label class="form-check-label">
                                        <?php echo "السعر: ".$session['price']; ?>
                                    </label>
                                    <label class="form-check-label">حالة الدورة:
                                        <?php $session['status']==1?print "جارية":print "منتهية"; ?>
                                    </label>
                                    <input readonly class="form-control" type="text" name="session_<?php echo $session['session_name'];
                                    ?>"
                                           id="session_<?php echo $session['id']; ?>"
                                           value="<?php echo $session['session_name']; ?>"
                                    >
                                </div>
                                <hr>
                            <?php } ?>
                        </li>
                    </ul>

                    <input type="hidden" name="id" value="<?php echo $student['id']; ?>">

                    <button type="submit" class="btn btn-primary">حفظ التغييرات</button>


                    <a class="btn btn-secondary"
                       href="<?php echo $_COOKIE['role'] == 2 ? "bodyHomeUser.php" : ($_COOKIE['role'] == 1 ? "homeAdmin.php" : ""); ?>">
                        رجوع &gt;
                    </a>
                </div>
            </div>
        </form>
        <br>
        <br>
        <form id="deleteForm" action="../deleteStudent.php" method="post">
            <!-- Add a hidden input field to store the student id -->
            <input type="hidden" name="student_id" value="<?php echo $student['id']; ?>">
            <button type="button" onclick="confirmDelete()" class="btn btn-danger">حذف الطالب</button>

            <!-- Add your other form fields here if needed -->
        </form>
    </div>
    <script>
        function confirmDelete() {
            // Show confirmation dialog
            if (confirm("هل أنت متأكد من رغبتك في حذف الطالب؟")) {
                // If user confirms, submit the form
                document.getElementById("deleteForm").submit();

            }
        }
    </script>

    <?php
} ?>

<?php require 'footer.php'; ?>