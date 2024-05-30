<?php require '../dbconnection.php'; ?>
<?php require 'header.php';

?>


<?php

$teacher_id = $_POST['id'];
$db = new MyDB();
$openSessionData = $db->getOpenSession($teacher_id);
//echo json_encode($openSessionData);die();
$openSessionData = $openSessionData[0];

    ?>




    <div class="px-4 py-5 my-5 text-center">

        <div class="col-lg-4 mx-auto pt-4">

            <h2>المعلومات</h2>
            <div class=" gap-5 ">
                <ul class="list-group">
                    <li class="list-group-item">

                        <h6 style="display: inline; font-weight:bold"><?php echo $openSessionData['session_name']; ?> </h6>
                    </li>
                    <li class="list-group-item">
                        <h6 style="color : black ; display: inline; font-weight:bold"> نوع الدورة : </h6>
                        <h6 style="display: inline; color : green"><?php echo $openSessionData['type']; ?> </h6>
                    </li>

                    <li class="list-group-item">
                        <h6 style="font-weight:bold">المعلم
                            : <?php echo $openSessionData['teacher_name']; ?> </h6>

                        <h6 style="font-weight:bold">المادة
                            : <?php echo $openSessionData['material_name']; ?> </h6>

                        <h6 style="font-weight:bold">السعر الكلي
                            : <?php echo $openSessionData['price']; ?> </h6>
                    </li>

                    <li class="list-group-item">

                        <h6 style="color : black ; display: inline; font-weight:bold"> وقت البداية
                            : <?php echo $openSessionData['enter']; ?>
                        | التاريخ <?php echo $openSessionData['date']; ?>
                        </h6>
                    </li>


                    <li class="list-group-item">
                        <hr>
                    <h6  style="color : black ; display: inline; font-weight:bold">الطلاب</h6>
                    </li>
                        <?php
                    $studentNames = explode(',',$openSessionData['student_names']);
                    foreach ($studentNames as $studentName){
                        echo '<li class="list-group-item">'.$studentName.'</li>';
                    }
                    ?>

                    <form action="../ChangRequestStatus.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?php echo $openSessionData['session_id']; ?>">
                        <input type="hidden" name="type" value="<?php echo $type ?>">
                        <button type="submit" value="0" name="status" class="btn btn-outline-danger">انهاء ✕</button>
                    </form>

                </ul>

                <br>
                <a class="display-6"
                   href="<?php echo $_COOKIE['role'] == 2 ? "bodyHomeUser.php" : ($_COOKIE['role'] == 1 ? "homeAdmin.php" : ""); ?>">
                     رجوع &gt;
            </div>
        </div>
    </div>

    <?php //}
//} ?>

<?php require 'footer.php'; ?>