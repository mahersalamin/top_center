<?php require 'header.php'; ?>
<style>
    .dropdown {
        position: relative;
        display: inline-block;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        background-color: #f9f9f9;
        min-width: 250px;
        overflow-y: auto;
        border: 1px solid #ddd;
        z-index: 1;
    }

    .dropdown-content label {
        display: block;
        margin: 3px 0;
    }

    .dropdown-content input[type="checkbox"] {
        margin: 5px;
    }

    .show {
        display: block;
    }
</style>
<?php

if (!isset($_COOKIE['id'])) {
    header("location:signin.php");
}
$db = new MyDB();
$type = 'حقيبة مدرسية';
$backpacks = $db->getTeacherPrivateSessions($_COOKIE['id'], $type);
$activeAtt = $db->getActiveAttendanceStudents($_COOKIE['id']);

?>
<div class="container text-center">
    <h1>الحقائب المدرسية</h1>

    <?php
    if ($activeAtt) {
    ?>
        <table class="table table-striped text-dark">
            <thead>
                <tr>
                    <th scope='col'>اسم الطالب</th>
                    <th scope='col'>رقم الهاتف</th>
                    <th scope='col'>وقت البدء</th>
                    <th scope='col'>اسم الدورة</th>
                    <th scope='col'></th> <!-- Empty column for buttons -->
                </tr>
            </thead>
            <tbody>
                <?php foreach ($activeAtt as $data) { ?>
                    <tr>
                        <td><?php echo $data['name']; ?></td>
                        <td><?php echo $data['phone']; ?></td>
                        <td><?php echo $data['enter']; ?></td>
                        <td><?php echo $data['session_name']; ?></td>
                        <td>
                            <?php if ($data['InSess'] == 0) { ?>
                                <form action="../openAtt.php" method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="id_tech" value="<?php echo $_COOKIE['id']; ?>">
                                    <button type="submit" value="1" name="status" class="btn btn-outline-success">بدء حصة
                                        ✓
                                    </button>
                                </form>
                            <?php } else { ?>
                                <form action="../ChangRequestStatus.php" method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="id" value="<?php echo $data['session_id']; ?>">
                                    <input type="hidden" name="type" value="<?php echo $type ?>">
                                    <button type="submit" value="0" name="status" class="btn btn-outline-danger">انهاء ✕
                                    </button>
                                </form>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php
    }
    ?>


    <div class="row">

        <?php foreach (
            $backpacks

            as $backpack
        ) {
        ?>

            <div class="col-md-3">
                <div class="card">
                    <form action="../openAtt.php" method="POST" enctype="multipart/form-data">

                        <div class="card-body">
                            <h5 class="card-title"><?php echo $backpack['session_name']; ?></h5>
                        </div>

                        <!--                            material-->
                        <div class="card-body">

                            <ul class="list-group list-group-flush">
                                المادة:
                                <select class="mb-3 form-select" name="material">
                                    <?php

                                    $materials = explode(',', $backpack['materials']);

                                    foreach ($materials as $material) {
                                        echo '<option value="' . $material . '">' . $material . '</option>';
                                    }
                                    ?>

                                </select>

                            </ul>
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="teacher_meetings">عدد اللقاءات الكلي</label>
                                    <input class="form-control" value="<?= $backpack['meetings'] ?>" id="teacher_meetings" type="number" readonly>
                                </div>
                                <div class="col-md-12">
                                    <label for="teacher_finished_meetings">عدد اللقاءات</label>
                                    <input class="form-control" value="<?= $backpack['meetings_count'] ?>" id="teacher_finished_meetings" type="number" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <button <?php $activeAtt ? print "disabled" : ""; ?> type="submit" class="btn btn-outline-info
                                text-center">بدء
                            </button>
                            <input type="hidden" name="teacher_id" value="<?php echo $_COOKIE['id']; ?>">
                            <input type="hidden" name="type" value="<?php echo $type ?>">

                            <input type="hidden"
                                name="student_name"
                                value="<?php echo $backpack['student_names']; ?>">
                            <input type="hidden" name="pSessionID" value="<?php echo $backpack['id']; ?>">


                        </div>
                    </form>
                </div>
            </div>

        <?php } ?>
    </div>
    <?php if (!$backpacks) {
    ?>
        <div class="col-md-12">
            <div class="alert alert-warning" role="alert">
                لا توجد جلسات خاصة بعد!
            </div>
        </div>
    <?php } ?>
</div>



<?php require 'footer.php'; ?>