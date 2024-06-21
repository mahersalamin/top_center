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
