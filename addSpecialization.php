<?php
require_once 'dbconnection.php';
require('MyDB.php');
$db = new MyDB();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the form is submitted with the POST method

    // Retrieve the new specialization name from the form
    $new_spec = $_POST['new_spec'];

    // Add the new specialization to the database

    $result = $db->addSpecialization($new_spec);

    // Check if the specialization was successfully added
    if ($result) {
        // Redirect to a success page or display a success message
        $status = "success";

        header("Location: ./page/newMissionSpecialization.php?status=$status&message=تم اضافة التخصص بنجاح");
        exit();
    } else {
        // Handle the case where adding the specialization failed
        $status = "error";

        header("Location: ./page/newMissionSpecialization.php?status=$status&message=لم تتم الاضافة");
    }
}
?>