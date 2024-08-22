<?php
require('../dbconnection.php');
require('../MyDB.php');
$db = new MyDB();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $student_id = $_POST['id'];

    // Call the method to unarchive the teacher
    $result = $db->unarchiveStudent($student_id);

    if ($result) {
        // Redirect to a success page or display a success message
        $status = "success";
        header("Location: homeAdmin.php?id=$student_id&status=$status&message=تم استرجاع الطالب بنجاح");
        exit();
    } else {
        // Redirect to an error page or display an error message
        $status = "error";
        header("Location: homeAdmin.php?id=$student_id&status=$status&message=حدث خطأ أثناء استرجاع الطالب");
        exit();
    }
} else {
    // Redirect to an error page or display an error message for invalid request
    $status = "error";
    header("Location: ../editStudent.php?status=$status&message=طلب غير صالح");
    exit();
}
?>
