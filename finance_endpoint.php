<?php
require 'MyDB.php'; // Include the database connection class

$db = new MyDB();
$conn = $db->connect();


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
    $stmt = $conn->prepare($query);

    if (!$stmt) {
        die('Error in preparing SQL statement: ' . $db->error);
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

// Fetch current total_payments and session_cost
    $query = "SELECT total_payments, session_cost FROM session_students WHERE student_id = ? AND session_id = ?";
    $stmt = $conn->prepare($query);

    if (!$stmt) {
        die('Error in preparing SQL statement: ' . $db->error);
    }

    $bindResult = $stmt->bind_param('ss', $studentId, $sessionId);
    if (!$bindResult) {
        die('Error in binding parameters: ' . $stmt->error);
    }

    $executeResult = $stmt->execute();
    if (!$executeResult) {
        die('Error in executing SQL query: ' . $stmt->error);
    }

    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    $currentTotalPayments = $row['total_payments'];
    $sessionCost = $row['session_cost'];

    $newTotalPayments = $currentTotalPayments + $amount;
    $newPaymentStatus = ($newTotalPayments >= $sessionCost) ? 'paid' : 'partially paid';

    $stmt->close();

// Update total_payments and payment_status
    $query = "UPDATE session_students SET total_payments = ?, payment_status = ? WHERE student_id = ? AND session_id = ?";
    $stmt = $conn->prepare($query);

    if (!$stmt) {
        die('Error in preparing SQL statement: ' . $db->error);
    }

    $bindResult = $stmt->bind_param('dsss', $newTotalPayments, $newPaymentStatus, $studentId, $sessionId);
    if (!$bindResult) {
        die('Error in binding parameters: ' . $stmt->error);
    }

    $executeResult = $stmt->execute();
    if (!$executeResult) {
        die('Error in executing SQL query: ' . $stmt->error);
    }

//    $stmt->close();
//    $db->close();

// Redirect back to page/finance.php after successful submission
    header('Location: page/finance.php');
    exit();
}

// Handle form submission for outcomes
if (isset($_POST['outcome_submit'])) {
//    echo json_encode($_POST);die();
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

    $bindResult = $stmt->bind_param('ssdss', $date, $type, $receiver, $amount, $notes);

    if (!$bindResult) {
        die('Error in binding parameters: ' . $stmt->error);
    }

    $executeResult = $stmt->execute();
    if (!$executeResult) {
        die('Error in executing SQL query: ' . $stmt->error);
    }

    $stmt->close();

    // Update session_teachers table
    // Fetch the current session_amount and paid_amount
    $sessionId = $_POST['session_id']; // Assuming session_id is passed in POST data
    $teacherId = $_POST['teacher_id']; // Assuming teacher_id is passed in POST data

    $selectQuery = "SELECT session_amount, paid_amount FROM session_teachers WHERE session_id = ? AND teacher_id = ?";
    $selectStmt = $db->connect()->prepare($selectQuery);

    if (!$selectStmt) {
        die('Error in preparing SQL statement: ' . $db->connect()->error);
    }

    $selectBindResult = $selectStmt->bind_param('ii', $sessionId, $teacherId);

    if (!$selectBindResult) {
        die('Error in binding parameters: ' . $selectStmt->error);
    }

    $selectExecuteResult = $selectStmt->execute();
    if (!$selectExecuteResult) {
        die('Error in executing SQL query: ' . $selectStmt->error);
    }

    $selectStmt->bind_result($sessionAmount, $paidAmount);
    $selectStmt->fetch();
    $selectStmt->close();

    // Calculate the new paid amount
    $newPaidAmount = $paidAmount + $amount;
    if ($newPaidAmount > $sessionAmount) {
        die('Error: paid amount cannot be greater than session amount.');
    }

    // Determine the payment status
    if ($newPaidAmount == $sessionAmount) {
        $paymentStatus = 'paid';
    } elseif ($newPaidAmount > 0) {
        $paymentStatus = 'partially paid';
    } else {
        $paymentStatus = 'not paid';
    }

    // Update the session_teachers table
    $updateQuery = "UPDATE session_teachers SET paid_amount = ?, payment_status = ? WHERE session_id = ? AND teacher_id = ?";
    $updateStmt = $db->connect()->prepare($updateQuery);

    if (!$updateStmt) {
        die('Error in preparing SQL statement: ' . $db->connect()->error);
    }

    $updateBindResult = $updateStmt->bind_param('dsii', $newPaidAmount, $paymentStatus, $sessionId, $teacherId);

    if (!$updateBindResult) {
        die('Error in binding parameters: ' . $updateStmt->error);
    }

    $updateExecuteResult = $updateStmt->execute();
    if (!$updateExecuteResult) {
        die('Error in executing SQL query: ' . $updateStmt->error);
    }

    $updateStmt->close();

    // Redirect back to page/finance.php after successful submission and update
    header('Location: page/finance.php');
    exit();
}

?>
