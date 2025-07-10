<?php
require_once '../MyDB.php';

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception("Invalid request method");
    }

    $sessionId = $_POST['session_id'] ?? null;
    if (!$sessionId || !is_numeric($sessionId)) {
        throw new Exception("Invalid or missing session ID");
    }

    $db = new MyDB();
    $result = $db->deleteSession((int)$sessionId);

    if ($result === true) {
        header("Location: SessionsReport.php?success=1&message=" . urlencode("تم حذف الدورة بنجاح"));
    } else {
        header("Location: SessionsReport.php?error=1&message=" . urlencode("فشل الحذف: $result"));
    }
    exit;

} catch (Exception $e) {
    header("Location: SessionsReport.php?error=1&message=" . urlencode($e->getMessage()));
    exit;
}
