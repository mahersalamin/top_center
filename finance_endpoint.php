<?php
require 'MyDB.php'; // Include the database connection class

$db = new MyDB();



// Handle form submission for incomes
if (isset($_POST['income_submit'])) {
    $date = $_POST['income_date'];
    $cashier = $_POST['income_cashier'];
    $amount = $_POST['income_amount'];
    $payer = $_POST['income_payer'];
    $studentId = $_POST['income_student'];
    $sessionId = $_POST['income_session'];
    $notes = $_POST['income_notes'];

    $query = "INSERT INTO income (date, cashier, amount, payer, student_id, session_id, notes) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $db->connect()->prepare($query);

    if (!$stmt) {
        die('Error in preparing SQL statement: ' . $db->connect()->error);
    }

    $bindResult = $stmt->bind_param('ssdssss', $date, $cashier, $amount, $payer, $studentId, $sessionId, $notes);
    if (!$bindResult) {
        die('Error in binding parameters: ' . $stmt->error);
    }

    $executeResult = $stmt->execute();
    if (!$executeResult) {
        die('Error in executing SQL query: ' . $stmt->error);
    }

    $stmt->close();

    // Redirect back to page/finance.php after successful submission
    header('Location: page/finance.php');
    exit();
}

// Handle form submission for outcomes
if (isset($_POST['outcome_submit'])) {
    $date = $_POST['outcome_date'];
    $type = $_POST['outcome_type'];
    $receiver = $_POST['outcome_receiver'];
    $amount = $_POST['outcome_amount'];
    $notes = $_POST['outcome_notes'];

    $query = "INSERT INTO outcome (date, type, receiver, amount, notes) VALUES (?, ?, ?, ?, ?)";
    $stmt = $db->connect()->prepare($query);

    if (!$stmt) {
        die('Error in preparing SQL statement: ' . $db->connect()->error);
    }

    // Adjust the bind_param call to match the number of parameters
    $bindResult = $stmt->bind_param('ssdss', $date, $type, $receiver, $amount, $notes);

    if (!$bindResult) {
        die('Error in binding parameters: ' . $stmt->error);
    }

    $executeResult = $stmt->execute();
    if (!$executeResult) {
        die('Error in executing SQL query: ' . $stmt->error);
    }

    $stmt->close();

    // Redirect back to page/finance.php after successful submission
    header('Location: page/finance.php');
    exit();
}

?>
