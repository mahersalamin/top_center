<?php
require('dbconnection.php');
require('MyDB.php');
$db = new MyDB();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the data from the POST request
    $studentId = isset($_POST['student_id']) ? $_POST['student_id'] : null;
    $sessionId = isset($_POST['session_id']) ? $_POST['session_id'] : null;
    $note = isset($_POST['note']) ? $_POST['note'] : null;

    if ($studentId && $sessionId && $note) {
        // Check if a note with the same student_id and session_id already exists
        $stmt = $db->connect()->prepare("SELECT id FROM notes WHERE student_id = ? AND session_id = ?");
        $stmt->bind_param("ii", $studentId, $sessionId);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // If a note exists, update it
            $stmt->bind_result($noteId);
            $stmt->fetch();
            $updateStmt = $db->connect()->prepare("UPDATE notes SET note = ? WHERE id = ?");
            $updateStmt->bind_param("si", $note, $noteId);

            if ($updateStmt->execute()) {
                echo 'Note updated successfully!';
            } else {
                echo 'Error updating note: ' . $updateStmt->error;
            }

            $updateStmt->close();
        } else {
            // If no note exists, insert a new one
            $insertStmt = $db->connect()->prepare("INSERT INTO notes (student_id, session_id, note) VALUES (?, ?, ?)");
            $insertStmt->bind_param("iis", $studentId, $sessionId, $note);

            if ($insertStmt->execute()) {
                echo 'Note saved successfully!';
            } else {
                echo 'Error saving note: ' . $insertStmt->error;
            }

            $insertStmt->close();
        }

        $stmt->close();
    } else {
        echo 'Invalid input. Please provide student ID, session ID, and note.';
    }
} else {
    echo 'Invalid request method.';
}
?>
