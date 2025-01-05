<?php require '../dbconnection.php'; ?>
<?php require 'header.php'; ?>


<?php
$id = $_POST['id'];
$db = new MyDB();
$student = $db->getStudentData($id);
$classes = $db->getClasses();
$schools = $db->getSchools();
$school_name = '';
foreach($schools as $sch){
    if($sch['id'] == $student['school']){
        $school_name = $sch['name'];
    }

}
if ($student) {
?>


    <div class="container my-5">
        <div class="text-center">
            <div class="col-md-5 shadow p-1 bg-body rounded mx-auto mb-4">
                <img class="img-fluid" src="../upload/<?php echo htmlspecialchars($student['img']); ?>"
                    alt="Teacher Image">
            </div>

            <h1 class="display-4 fw-bold text-success"><?php echo htmlspecialchars($student['name']); ?>
        </div>
        <div class="col-lg-6 mx-auto">
            <form action="../updateStudent.php" method="POST" enctype="multipart/form-data">
                <div class="card mb-4 text-right">
                    <div class="card-header">

                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($student['id']); ?>">
                        <div class="d-flex justify-content-between">
                            <a class="btn btn-secondary"
                                href="<?php echo $_COOKIE['role'] == 2 ? "bodyHomeUser.php" : ($_COOKIE['role'] == 1 ? "homeAdmin.php" : ""); ?>">
                                &lt; رجوع
                            </a>
                            <button type="submit" class="btn btn-primary">حفظ التغييرات</button>

                        </div>
                    </div>

                    <div class="card-body">
                        <div class="mb-3">
                            <label for="name" class="form-label" style="display: inline; font-weight: bold">اسم
                                الطالب</label>
                            <input type="text" id="name" class="form-control" name="name"
                                value="<?php echo $student['name']; ?>">

                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label"
                                style="color: black; display: inline; font-weight: bold">رقم الهاتف</label>
                            <input type="text" class="form-control" id="phone" name="phone"
                                value="<?php echo $student['phone']; ?>">

                        </div>
                        <div class="mb-3">
                            <label for="class" class="form-label">
                                الصف
                            </label>
                            <select class="mb-3 form-select" id="class" name="class">
                                <option disabled selected value="">الصف <?php echo $student['class'] ?> </option>

                                <?php
                                foreach ($classes as $class) {
                                ?>
                                    <option value="<?= $class['id'] ?>"> <?php echo $class['name']; ?></option>

                                <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="school" class="form-label">
                                المدرسة
                            </label>
                            <select class="mb-3 form-select" id="school" name="school">
                                <option disabled selected value=""><?php echo $school_name; ?> </option>

                                <?php
                                foreach ($schools as $school) {
                                ?>
                                    <option value="<?= $school['id'] ?>"> <?php echo $school['name']; ?></option>

                                <?php
                                }
                                ?>
                            </select>
                        </div>


                        <div class="mb-3">
                            <p style="color: black; display: inline; font-weight: bold"> المعلمون</p>
                            <?php
                            $all_teacher_names = explode(',', $student['all_teacher_names']);
                            $teacher_names = explode(',', $student['teacher_names']);

                            foreach ($all_teacher_names as $att_teacher) { ?>
                                <div class="mr-2 form-check row justify-content-center">
                                    <input class="form-check-input ml-3" type="checkbox" name="emp[]"
                                        id="teacher_<?php echo $att_teacher; ?>"
                                        value="<?php echo $att_teacher; ?>"
                                        <?php echo in_array($att_teacher, $teacher_names) ? 'checked' : ''; ?>>
                                    <label class="form-check-label mr-3" for="teacher_<?php echo $att_teacher; ?>">
                                        <?php echo $att_teacher; ?>
                                    </label>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="mb-3">
                            <p style="color: black; display: inline; font-weight: bold">الدورات</p>
                            <?php
                            $s_sessions = $db->getStudentSessions($id);
                            foreach ($s_sessions as $session) { ?>
                                <div class="mr-2 col-md-12 justify-content-center">
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label class="form-check-label"
                                                for="session_<?php echo $session['session_name'];
                                                                ?>">
                                                <?php echo $session['type']; ?>
                                            </label>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-check-label">
                                                <?php echo "السعر: " . $session['price']; ?>
                                            </label>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-check-label">حالة الدورة:
                                                <?php $session['status'] == 1 ? print "جارية" : print "منتهية"; ?>
                                            </label>
                                        </div>
                                        <div class="col-md-12">
                                            <label for="session_<?php echo $session['id']; ?>"></label>
                                            <input readonly class="form-control" type="text"
                                                name="session_<?php echo $session['session_name']; ?>"
                                                id="session_<?php echo $session['id']; ?>"
                                                value="<?php echo $session['session_name']; ?>">
                                        </div>
                                    </div>
                                </div>

                            <?php } ?>
                        </div>


                    </div>

                </div>
            </form>
            <?php if ($student['archived'] == 0) { ?>

                <form id="deleteForm" action="../deleteStudent.php" method="post">
                    <input type="hidden" name="student_id" value="<?php echo $student['id']; ?>">
                    <button type="button" onclick="confirmDelete()" class="btn btn-danger">حذف الطالب</button>
                </form>
        </div>
    <?php } ?>
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