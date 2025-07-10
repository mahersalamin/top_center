<?php
require_once '../MyDB.php';
//header('Content-Type: application/json');
//var_dump($_POST);die();
try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception("Invalid request method");
    }

    $sessionId = $_POST['session_id'] ?? null;
    if (!$sessionId) {
        throw new Exception("Missing session ID");
    }

    $students = $_POST['students'] ?? [];
    $materials = $_POST['materials'] ?? [];

    $sessionMeta = [
        'session_name' => $_POST['session_name'] ?? '',
        'session_type' => $_POST['session_type'] ?? '',
        'session_hours' => $_POST['session_hours'] ?? 0,
        'session_price' => $_POST['session_price'] ?? 0,
    ];

    // Parse teachers with percentages
    $rawTeachers = $_POST['teachers'] ?? [];
    $teachers = [];

    foreach ($rawTeachers as $teacherId => $teacherData) {
        $id = (int)($teacherData['id'] ?? 0);
        $percentage = trim($teacherData['percentage'] ?? '');

        if ($id > 0 && $percentage !== '' && is_numeric($percentage)) {
            $percentage = (float)$percentage;
            if ($percentage > 0) {
                $teachers[$id] = [
                    'percentage' => $percentage
                ];
            }
        }
    }


    $db = new MyDB();
    $result = $db->updateSessions($students, [$sessionId], $teachers, $materials, $sessionMeta);

    if ($result === true) {
        header("Location: SessionsReport.php?status=success&msg=" . urlencode("تم تحديث الدورة بنجاح"));
        exit;

    } else {
        header("Location: SessionsReport.php?status=error&msg=" . urlencode("فشل في التحديث: " . $result));
        exit;
    }

} catch (Exception $e) {
    header("Location: SessionsReport.php?status=error&msg=" . urlencode($e->getMessage()));
    exit;

}
