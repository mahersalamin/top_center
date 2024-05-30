<?php
require('dbconnection.php');
require('MyDB.php');
$db = new MyDB();
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['teacher_id'])) {
    $teacher_id = $_POST['teacher_id'];

    // Call the deleteTeacher method from your database class
    $result = $db->deleteTeacher($teacher_id);

    if ($result) {
        // Redirect to a success page or display a success message
        $status = "success";

        header("Location: ./page/homeAdmin.php?status=$status&message=تم حذف المعلم بنجاح");
        exit();
    } else {
        // Redirect to an error page or display an error message
        $status = "error";

        header("Location: ./page/homeAdmin.php?status=$status&message=حدث خطأ أثناء حذف المعلم");
        exit();
    }
} else {
    // Redirect to an error page or display an error message for invalid request
    $status = "success";

    header("Location: ./page/homeAdmin.php?status=$status&message=طلب غير صالح");
    exit();
}
?>
