<?php require '../dbconnection.php'; ?>
<?php require 'header.php'; ?>


<?php

$id = $_POST['id'];
$db = new MyDB();

$teacher = $db->getTeacherData($id);
echo json_encode($teacher);die();
$teacher = $teacher[0];

if ($teacher) {
    ?>
    <div class="px-4 py-5 my-5 text-center">
        <form action="../updateTeacher.php" method="POST" enctype="multipart/form-data">
            <div class="col-md-5 shadow p-1 bg-body rounded d-block mx-auto mb-4">
                <img class="img-fluid" src="../upload/<?php echo $teacher['img']; ?> " alt="">
            </div>

            <h1 class="display-4 fw-bold" style="color: green; display: inline;"><?php echo $teacher['name']; ?></h1>

            <div class="col-lg-5 mx-auto pt-4">

                <div class=" gap-5 ">
                    <ul class="list-group">
                        <li class="list-group-item">

                            <label for="name" style="display: inline; font-weight: bold">اسم المعلم</label>
                            <br><br>
                            <input class="form-control" type="text" id="name" name="name" value="<?php echo $teacher['name']; ?>">
                        </li>
                        <li class="list-group-item">

                            <label for="email" style="color: black; display: inline; font-weight: bold">ايميل المعلم :</label>
                            <br><br>
                            <input class="form-control" type="email" id="email" name="email" value="<?php echo $teacher['user']; ?>">
                        </li>
                        <li class="list-group-item">
                            <label style="color: black; display: inline; font-weight: bold" for="specs">اختر التخصصات:</label><br>
                            <br>
                            <?php
                            $teacher_specializations = $db->getTeacherSpecializations($teacher['id']); // Assuming you have a method to fetch the teacher's specializations
                            $specializations = $db->getSpecializations();
                            foreach ($specializations as $spec) {
                                $checked = false;
                                foreach ($teacher_specializations as $teacher_spec) {
                                    if (($teacher_spec['spec'] == $spec['id'])) {
                                        $checked = true;
                                        break;
                                    }
                                }
                                ?>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox"
                                           name="specs[<?php echo $spec['id']; ?>][id]"
                                           value="<?php echo $spec['id']; ?>"
                                           id="spec_<?php echo $spec['id']; ?>" <?php echo $checked ? 'checked' : ''; ?>>
                                    <label class="form-check-label"
                                           for="spec_<?php echo $spec['id']; ?>"><?php echo $spec['name']; ?></label>
                                    <?php
                                    $price = $db->getSpecializationPriceForTeacher($teacher['id'], $spec['id']);
                                    ?>
                                    <label>
                                        <input type="number" class="form-control"
                                               name="specs[<?php echo $spec['id']; ?>][price]" placeholder="السعر"
                                               value="<?php echo $price; ?>" >
                                    </label>
                                </div>
                                <?php
                            }
                            ?>
                        </li>
                    </ul>

                    <input type="hidden" name="id" value="<?php echo $teacher['id']; ?>">

                    <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                    <a class="btn btn-secondary"
                       href="<?php echo $_COOKIE['role'] == 2 ? "bodyHomeUser.php" : ($_COOKIE['role'] == 1 ? "homeAdmin.php" : ""); ?>">
                        رجوع &gt;
                    </a>
                </div>
            </div>
        </form>
        <form action="../deleteTeacher.php" method="POST">
            <input type="hidden" name="teacher_id" value="<?php echo $teacher['id']; ?>">
            <button type="submit" class="btn btn-danger">حذف المعلم</button>
        </form>
    </div>

    <?php
} ?>

<?php require 'footer.php'; ?>