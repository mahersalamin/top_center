<?php require 'header.php'; ?>
<style>
    #regForm {
        background-color: #ffffff;
        padding: 40px;
        width: 100%;
        min-width: 300px;
    }

    h1 {
        text-align: center;
    }

    button {
        background-color: #04AA6D;
        color: #ffffff;
        border: none;
        padding: 10px 20px;
        font-size: 17px;
        font-family: Raleway;
        cursor: pointer;
    }

    button:hover {
        opacity: 0.8;
        transition: 0.3s;
    }

</style>
<?php

if (!isset($_COOKIE['id'])) {
    header("location:signin.php");
}
$db = new MyDB();

$attendances = $db->dailyReport();

?>


<div class="container-fluid text-right" style="font-family: 'Cairo' ">
    <h2 class="mb-4">سجل الحضور</h2>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>التاريخ</th>
            <th>المعلم</th>
            <th>الطالب</th>
            <th>الحصة</th>
            <th>مدة الحصة</th>
            <th>عدد الساعات</th>
        </tr>
        </thead>
        <tbody>
        <?php if (!empty($attendances)) { ?>
            <?php foreach ($attendances as $attendance) { ?>
                <tr>
                    <td><?php echo $attendance['date']; ?></td>
                    <td><?php echo $attendance['tname']; ?></td>
                    <td><?php echo $attendance['snames']; ?></td>
                    <td><?php echo $attendance['session_name']; ?></td>
                    <td><?php echo $attendance['total']; ?></td>
                    <td><?php echo $attendance['hours']; ?></td>
                    <td>
                        <button class="btn btn-success accept-attendance"
                            <?php $attendance['processed'] == 1 ? print "disabled" : ""; ?>
                                data-session-id="<?php echo $attendance['session_id']; ?>"
                                data-attendance-id="<?php echo $attendance['id']; ?>"
                                data-student-ids="<?php echo $attendance['st_id']; ?>"
                                data-hours="<?php echo $attendance['hours']; ?>">
                            تأكيد الحصة
                        </button>
                    </td>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr>
                <td colspan="5" class="text-center">لا يوجد سجلات</td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
<script>
    $(document).ready(function () {
        $('.accept-attendance').click(function () {
            let sessionId = $(this).data('session-id');
            let attendanceId = $(this).data('attendance-id');
            let studentIds = $(this).data('student-ids');
            let hours = $(this).data('hours');

            $.ajax({
                url: '../accept_attendance.php', // The URL to your PHP script
                type: 'POST',
                data: {
                    session_id: sessionId,
                    attendance_id: attendanceId,
                    student_ids: studentIds,
                    hours: hours
                },
                success: function (response) {
                    // console.log(response)
                    response = JSON.parse(response)
                    alert(response.message);
                    location.reload();
                },
                error: function (xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        });
    });
</script>