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
$type = 'اشتراك شهري';
$monthlySessions = $db->getTeacherPrivateSessions($_COOKIE['id'], $type);
$activeAtt = $db->getActiveAttendanceStudents($_COOKIE['id']);
//var_dump($single);die();
?>
<div class="container text-center">
    <h1>الاشتراكات الشهرية</h1>

    <?php
    if ($activeAtt) {
    ?>
        <table class="table table-striped text-dark">
            <thead>
                <tr>
                    <th scope='col'>اسم الطالب</th>
                    <th scope='col'>رقم الهاتف</th>
                    <th scope='col'>وقت البدء</th>
                    <th scope='col'></th> <!-- Empty column for buttons -->
                </tr>
            </thead>
            <tbody>
                <?php foreach ($activeAtt as $data) { ?>
                    <tr>
                        <td><?php echo $data['name']; ?></td>
                        <td><?php echo $data['phone']; ?></td>
                        <td><?php echo $data['enter']; ?></td>
                        <td>
                            <?php if ($data['InSess'] == 0) { ?>
                                <form action="../openAtt.php" method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="id_tech" value="<?php echo $_COOKIE['id']; ?>">
                                    <button type="submit" value="1" name="status" class="btn btn-outline-success">بدء حصة ✓</button>
                                </form>
                            <?php } else { ?>
                                <form action="../ChangRequestStatus.php" method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="id" value="<?php echo $data['session_id']; ?>">
                                    <input type="hidden" name="type" value="<?php echo $type ?>">
                                    <button type="submit" value="0" name="status" class="btn btn-outline-danger">انهاء ✕</button>
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
            $monthlySessions

            as $monthlySession
        ) {
        ?>

            <div class="col-md-3">
                <div class="card">
                    <form action="../openAtt.php" method="POST" enctype="multipart/form-data">

                        <div class="card-body">
                            <h5 class="card-title"><?php echo $monthlySession['session_name']; ?></h5>
                        </div>
                        <ul class="list-group list-group-flush">
                            الطلاب:
                            <?php
                            $studentNames = explode(',', $monthlySession['student_names']);
                            if (count($studentNames) == 1) {
                                echo '<li class="list-group-item">' . $studentNames[0] . '</li>';
                            } else {

                                foreach ($studentNames as $name) {

                            ?>
                                    <li class="list-group-item">
                                        <div class="row justify-content-between">
                                            <input id="<?php echo $name ?>" class="form-check-input"
                                                value="<?php echo $name ?>"
                                                type="checkbox" name="student_names[]">
                                            <label class="form-check-label"
                                                for="<?php echo $name ?>"><?php echo $name ?></label>
                                        </div>
                                    </li>
                            <?php

                                }
                            }
                            ?>
                        </ul>
                        <!--                            material-->
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                المادة:
                                <select class="mb-3 form-select" name="material">
                                    <?php

                                    $materials = explode(',', $monthlySession['materials']);

                                    foreach ($materials as $material) {
                                        echo '<option value="' . $material . '">' . $material . '</option>';
                                    }
                                    ?>

                                </select>
                                <!--                                    <li class="list-group-item">-->
                                <!--                                        السعر: --><? //=$monthlySession['price']
                                                                                        ?>
                                <!--                                    </li>-->
                            </ul>
                        </div>
                        <div class="card-body">
                            <button <?php $activeAtt ? print "disabled" : ""; ?> type="submit" class="btn btn-outline-info
                                text-center">بدء
                            </button>
                            <input type="hidden" name="teacher_id" value="<?php echo $_COOKIE['id']; ?>">
                            <input type="hidden" name="type" value="<?php echo $type ?>">

                            <input <?php
                                    count($studentNames) !== 1 ? print "disabled" : print "";
                                    ?> type="hidden"
                                name="student_name"
                                value="<?php echo $monthlySession['student_names']; ?>">
                            <input type="hidden" name="pSessionID" value="<?php echo $monthlySession['id']; ?>">


                        </div>
                    </form>
                </div>
            </div>

        <?php } ?>
    </div>
    <?php if (!$monthlySessions) {
    ?>
        <div class="col-md-12">
            <div class="alert alert-warning" role="alert">
                لا توجد جلسات خاصة بعد!
            </div>
        </div>
    <?php } ?>
</div>


<?php require 'footer.php'; ?>