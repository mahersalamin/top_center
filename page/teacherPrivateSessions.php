<?php require 'header.php'; ?>
<style>

    .list-group{
        padding-right: 0;
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
    .student-list-container {
        max-height: 200px; /* Maximum height */
        overflow-y: auto; /* Enable vertical scrolling */
    }

</style>
<?php

if (!isset($_COOKIE['id'])) {
    header("location:signin.php");
}
$db = new MyDB();
$type = 'دورة خاصة';
$privateSessions = $db->getTeacherPrivateSessions($_COOKIE['id'], $type);


$activeAttendanceStudents = $db->getActiveAttendanceStudents($_COOKIE['id']);
$teacherMaterials = $db->getTeacherSpecializations($_COOKIE['id']);
$teacherMaterialsNames = $db->getTeacherSpecializationsNames($_COOKIE['id']);
//echo json_encode($teacherMaterialsNames);die();
?>
<button onclick="history.go(-1);">رجوع</button>
<div class="container text-center">
    <h1>الدورات الخاصة</h1>

    <?php
    if ($activeAttendanceStudents) {
        ?>
        <table class="table table-striped text-dark">
            <thead>
            <tr>
                <th scope='col'>اسم الطالب</th>
                <th scope='col'>رقم الهاتف</th>
                <th scope='col'>وقت البدء</th>
                <th scope='col'><?php echo $activeAttendanceStudents[0]['session_name'].' - '.$activeAttendanceStudents[0]['type'] ?></th> <!-- Empty column for buttons -->
            </tr>
            </thead>
            <tbody>
            <?php foreach ($activeAttendanceStudents as $data) { ?>
                <tr>
                    <td><?php echo $data['name']; ?></td>
                    <td><?php echo $data['phone']; ?></td>
                    <td><?php echo $data['enter']; ?></td>
                    <td>
                        <?php if ($data['InSess'] == 0) { ?>
                            <form action="../openAtt.php" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="id_tech" value="<?php echo $_COOKIE['id']; ?>">
                                <input type="hidden" name="type" value="<?php echo $type ?>">
                                <button type="submit" value="1" name="status"
                                        class="btn btn-outline-success">بدء حصة ✓
                                </button>
                            </form>
                        <?php } else { ?>
                            <form action="../ChangRequestStatus.php" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="id" value="<?php echo $data['session_id']; ?>">
                                <input type="hidden" name="type" value="<?php echo $type ?>">
                                <button type="submit" value="0" name="status"
                                        class="btn btn-outline-danger">انهاء ✕
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

    <ul class="nav nav-tabs nav-pills nav-fill justify-content-center mt-4" id="sessionsTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link active" id="individual-tab" data-toggle="tab" href="#individual" role="tab"
               aria-controls="individual" aria-selected="true">دورات منفردة</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="group-tab" data-toggle="tab" href="#group" role="tab" aria-controls="group"
               aria-selected="false">دورات جماعية</a>
        </li>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content mt-4">
        <!-- Individual Tab -->
        <div class="tab-pane fade show active" id="individual" role="tabpanel" aria-labelledby="individual-tab">
            <div class="row">
                <?php
                // Flag to track if any private sessions match the material
                $hasPrivateSessions = false;

                foreach ($privateSessions as $pSessions) {
                    $studentNames = explode(',', $pSessions['student_names']);

                    if ($pSessions['is_group'] == "0") {
                        // Flag to track if current session matches the material

                        $matchesMaterial = false;

                        // If $pSessions['materials'] is a string, explode it into an array
                        $materials = is_array($pSessions['materials']) ? $pSessions['materials'] : explode(',', $pSessions['materials']);

                        foreach ($materials as $material) {
                            foreach ($teacherMaterialsNames as $teacherMaterialsName) {

                                if ($teacherMaterialsName['spec_name'] === $material) {
                                    $matchesMaterial = true;
                                    $hasPrivateSessions = true;
                                    break 2; // Exit both loops once match is found
                                }
                            }
                        }

                        if ($matchesMaterial) {
                            ?>
                            <div class="col-md-3">
                                <div class="card">
                                    <form action="../openAtt.php" method="POST" enctype="multipart/form-data">
                                        <div class="card-header">
                                            <h5 class="card-title"><?php echo $pSessions['session_name']; ?></h5>
                                        </div>
                                        <ul class="list-group list-group-flush">
                                            الطلاب:
                                            <?php echo '<li class="list-group-item">' . $studentNames[0] . '</li>'; ?>
                                        </ul>
                                        <!-- Material -->
                                        <div class="card-body">
                                            <ul class="list-group list-group-flush">
                                                المادة:
                                                <select class="mb-3 form-select" name="material">
                                                    <?php
                                                    foreach ($materials as $material) {
                                                        echo '<option value="' . $material . '">' . $material . '</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </ul>
                                        </div>
                                        <div class="card-body">
                                            <button <?php echo $activeAttendanceStudents ? 'disabled' : ''; ?> type="submit" class="btn btn-outline-info text-center">بدء</button>
                                            <input type="hidden" name="teacher_id" value="<?php echo $_COOKIE['id']; ?>">
                                            <input type="hidden" name="type" value="<?php echo $type ?>">
                                            <input <?php echo count($studentNames) !== 1 ? 'disabled' : ''; ?> type="hidden" name="student_name" value="<?php echo $pSessions['student_names']; ?>">
                                            <input type="hidden" name="pSessionID" value="<?php echo $pSessions['id']; ?>">
                                        </div>
                                    </form>
                                </div>
                            </div>
                        <?php } // End if $matchesMaterial
                    } // End if count($studentNames) == 1

                } // End foreach $privateSessions
//var_dump($hasPrivateSessions);die();
                // Show alert if no private sessions match the material
                if (!$hasPrivateSessions) {
                    ?>
                    <div class="col-md-12">
                        <div class="alert alert-warning" role="alert">
                            لا توجد جلسات خاصة بعد!
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>

        <!-- Group Tab -->
        <div class="tab-pane fade" id="group" role="tabpanel" aria-labelledby="group-tab">
            <div class="row">
                <?php
                $hasPrivateSessions = false;

                foreach ($privateSessions as $pSessions) {
                    $studentNames = explode(',', $pSessions['student_names']);

                    // Check if the session material matches any of the teacher's materials
                    $sessionMaterials = explode(',', $pSessions['materials']);
                    foreach ($sessionMaterials as $sessionMaterial) {
                        foreach ($teacherMaterialsNames as $teacherMaterial) {
                            if ($sessionMaterial === $teacherMaterial['spec_name']) {
                                $hasPrivateSessions = true;
                                ?>
                                <div class="col-md-3">
                                    <div class="card">
                                        <form action="../openAtt.php" method="POST" enctype="multipart/form-data">
                                            <div class="card-header">
                                                <h5 class="card-title"><?php echo $pSessions['session_name']; ?></h5>
                                            </div>
                                            <ul class="list-group list-group-flush">
                                                الطلاب:
                                                <?php
                                                foreach ($studentNames as $studentName) {
                                                    echo '<li class="list-group-item">' . $studentName . '</li>';
                                                }
                                                ?>
                                            </ul>
                                            <!-- Material -->
                                            <div class="card-body">
                                                <?php $materials = explode(',', $pSessions['materials']); ?>
                                                المادة:
                                                <select class="mb-3 form-select" name="material">
                                                    <?php
                                                    foreach ($materials as $material) {
                                                        echo '<option value="' . $material . '">' . $material . '</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="card-body">
                                                <button <?php echo $activeAttendanceStudents ? 'disabled' : ''; ?> type="submit" class="btn btn-outline-info text-center">بدء</button>
                                                <input type="hidden" name="teacher_id"
                                                       value="<?php echo $_COOKIE['id']; ?>">
                                                <input type="hidden" name="type"
                                                       value="<?php echo $pSessions['type']; ?>">
                                                <input <?php echo count($studentNames) !== 1 ? 'disabled' : ''; ?>
                                                        type="hidden" name="student_name"
                                                        value="<?php echo $pSessions['student_names']; ?>">
                                                <input type="hidden" name="pSessionID"
                                                       value="<?php echo $pSessions['id']; ?>">
                                                <input type="hidden" name="student_names"
                                                       value="<?php echo $pSessions['student_names']; ?>">
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <?php
                                // Break the loop once a matching material is found
                                break 2;
                            }
                        }
                    }
                } // End foreach $privateSessions

                // Show alert if no private sessions match any of the teacher's materials
                if (!$hasPrivateSessions) {
                    ?>
                    <div class="col-md-12">
                        <div class="alert alert-warning" role="alert">
                            لا توجد جلسات خاصة تطابق المواد المحددة!
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>


    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        // Handle select all checkbox click event
        $('.select-all').change(function () {
            var activeSelectAllCount = $('.select-all:checked').length;
            if (activeSelectAllCount > 1) {
                alert('تنبيه: لا يمكن تحديد الكل في أكثر من بطاقة واحدة في نفس الوقت.');
                $(this).prop('checked', false);
            } else {
                $(this).closest('.card').find('input[type="checkbox"]').prop('checked', $(this).prop('checked'));
            }
        });

        // Handle search input keyup event
        $('#searchInput').keyup(function () {
            var searchText = $(this).val().toLowerCase();
            $(this).closest('.card').find('.list-group-item').each(function () {
                var studentName = $(this).text().toLowerCase();
                if (studentName.includes(searchText)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });
    });
</script>



<?php require 'footer.php'; ?>
