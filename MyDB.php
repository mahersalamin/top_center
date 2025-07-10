<?php

/** @noinspection ALL */


class MyDB
{

    private $conn;

    public function connect()
    {
        if ($this->conn == null) {
            $this->conn = new mysqli("localhost", "root", "", "top_data");
            if ($this->conn->connect_error) {
                die("Connection failed: " . $this->conn->connect_error);
            }
        }
        return $this->conn;
    }


    function logSecretaryLogin($teacher_id)
    {
        // Get today's date
        $today = date('Y-m-d');
        $conn = $this->connect();
        // Check if a login record already exists for today with no logout
        $query = "SELECT id FROM secretary_timesheet 
              WHERE teacher_id = ? 
              AND DATE(login_datetime) = ? 
              AND logout_datetime IS NULL";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("is", $teacher_id, $today);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // A login record already exists for today, so do nothing
            return "تم تسجيل الدخول بالفعل لهذا اليوم.";
        }

        // Log the login time
        $loginTime = date('Y-m-d H:i:s');
        $insertQuery = "INSERT INTO secretary_timesheet (teacher_id, login_datetime) VALUES (?, ?)";
        $insertStmt = $conn->prepare($insertQuery);
        $insertStmt->bind_param("is", $teacher_id, $loginTime);

        if ($insertStmt->execute()) {
            return "تم تسجيل الدخول بنجاح.";
        } else {
            die("خطأ أثناء تسجيل الدخول: " . $insertStmt->error);
        }
    }


    function logSecretaryLogout($teacher_id)
    {
        // Define the start and end of the current day
        $todayStart = date('Y-m-d 00:00:00');
        $todayEnd = date('Y-m-d 23:59:59');
        $currentTime = date('Y-m-d H:i:s');
        $conn = $this->connect();

        // Query to find today's record for the teacher
        $query = "SELECT id, logout_datetime FROM secretary_timesheet 
              WHERE teacher_id = ? 
              AND login_datetime BETWEEN ? AND ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iss", $teacher_id, $todayStart, $todayEnd);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            // If a record exists, check logout_datetime
            $recordId = $row['id'];

            // Update the logout time regardless of whether it's NULL or already set
            $updateQuery = "UPDATE secretary_timesheet SET logout_datetime = ? WHERE id = ?";
            $updateStmt = $conn->prepare($updateQuery);
            $updateStmt->bind_param("si", $currentTime, $recordId);

            if ($updateStmt->execute()) {
                return "تم تحديث وقت تسجيل الخروج بنجاح.";
            } else {
                die("خطأ في تحديث تسجيل الخروج: " . $updateStmt->error);
            }
        } else {
            // No record exists for today, insert a new record
            $insertQuery = "INSERT INTO secretary_timesheet (teacher_id, login_datetime, logout_datetime) 
                        VALUES (?, ?, ?)";
            $insertStmt = $conn->prepare($insertQuery);
            $insertStmt->bind_param("iss", $teacher_id, $currentTime, $currentTime);

            if ($insertStmt->execute()) {
                return "تم إنشاء سجل جديد وتسجيل الخروج بنجاح.";
            } else {
                die("خطأ في إنشاء السجل: " . $insertStmt->error);
            }
        }
    }


    public function getAllStudents() // for admin
    {
        $conn = $this->connect();

        // Use LIKE operator to find students where the teacher ID is within the tec_id string
        $query = "SELECT students.*,
                    GROUP_CONCAT(teacher.name) AS teacher_names
                    FROM students
                    LEFT JOIN teacher ON teacher.id = students.tec_id
                    WHERE students.id != -1
                    GROUP BY students.id;
                ";

        $result = $conn->query($query);
        $rows = array();

        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        //        $conn->close();
        return $rows;
    }

    public function getTeacherPrivateSessions($id, $type)
    {
        // Determine the base query based on the session type
        if ($type == "حقيبة مدرسية") {
            $query = "SELECT 
            s.*, 
            st.teacher_id, 
            ss.*, 
            GROUP_CONCAT(DISTINCT stu.name) AS student_names, 
            t.name AS teacher_name,
            GROUP_CONCAT(DISTINCT spc.name) AS materials,
            COALESCE(attendance_counts.attendance_count, 0) AS meetings_count
        FROM 
            sessions s
        LEFT JOIN 
            session_teachers st ON s.id = st.session_id
        LEFT JOIN 
            session_students ss ON s.id = ss.session_id
        LEFT JOIN 
            students stu ON ss.student_id = stu.id AND stu.archived = 0
        LEFT JOIN 
            teacher t ON st.teacher_id = t.id
        LEFT JOIN 
            spc ON FIND_IN_SET(spc.id, s.material) > 0
        LEFT JOIN (
            SELECT 
                session_id, 
                COUNT(session_id) AS attendance_count
            FROM 
                att
            GROUP BY 
                session_id
        ) attendance_counts ON s.id = attendance_counts.session_id
        WHERE 
            st.teacher_id = ? -- Filter by teacher_id
            AND s.type = 'حقيبة مدرسية' -- Filter by session type
        GROUP BY 
            s.id, st.teacher_id, ss.session_id, t.name;
        ";
        } else {
            $query = "SELECT 
            s.*, 
            st.teacher_id, 
            ss.*, 
            GROUP_CONCAT(DISTINCT stu.name) AS student_names, 
            t.name AS teacher_name,
            GROUP_CONCAT(DISTINCT spc.name) AS materials
        FROM 
            sessions s
        LEFT JOIN 
            session_teachers st ON s.id = st.session_id
        LEFT JOIN 
            session_students ss ON s.id = ss.session_id
        LEFT JOIN 
            students stu ON ss.student_id = stu.id AND stu.archived = 0
        LEFT JOIN 
            teacher t ON st.teacher_id = t.id
        LEFT JOIN 
            spc ON FIND_IN_SET(spc.id, s.material) > 0
        WHERE 
            st.teacher_id = ? -- Filter by teacher_id
            AND s.type = ? -- Filter by session type
        GROUP BY 
            s.id;
        ";
        }

        // Prepare and execute the query
        $conn = $this->connect();
        $stmt = $conn->prepare($query);

        if ($type == "حقيبة مدرسية") {
            $stmt->bind_param("i", $id);
        } else {
            $stmt->bind_param("is", $id, $type);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        $rows = array();
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }


        return $rows;
    }


    public function getTeacherAllStudents($id)
    {
        $conn = $this->connect();

        // Use LIKE operator to find students where the teacher ID is within the tec_id string
        $query = "SELECT * FROM students WHERE tec_id LIKE '%$id%'";

        $result = $conn->query($query);
        $rows = array();

        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        //        $conn->close();

        return $rows;
    }

    function getStudentSessions($studentId)
    {
        $conn = $this->connect();
        $sql = "
            SELECT  s.*
            FROM sessions AS s
            JOIN session_students AS ss ON s.id = ss.session_id
            WHERE ss.student_id =$studentId
        ";

        $result = $conn->query($sql);
        $sessions = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $sessions[] = $row;
            }
            $result->free();
        }
        return $sessions;
    }

    function getEnrolledSessionsForStudent($studentIds)
    {
        $conn = $this->connect();
        $sql = "
            SELECT DISTINCT s.*, ss.total_payments, ss.session_cost
            FROM sessions s
            JOIN session_students ss ON s.id = ss.session_id
            WHERE FIND_IN_SET(ss.student_id, '$studentIds') > 0
            AND ss.payment_status != 'paid';
        ";

        $result = $conn->query($sql);
        $sessions = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $sessions[] = $row;
            }
            $result->free();
        }
        return $sessions;
    }

    function getEnrolledSessionsForTeachers($teacherId)
    {
        $conn = $this->connect();
        $sql = "
            SELECT s.*, st.session_amount, st.paid_amount, st.teacher_id, COUNT(a.session_id) AS meetings_count
            FROM sessions s
            JOIN session_teachers st ON s.id = st.session_id
            LEFT JOIN att a ON s.id = a.session_id  -- Use LEFT JOIN to include sessions without attendance
            WHERE st.teacher_id = '$teacherId' AND st.payment_status != 'paid'
            GROUP BY s.id, st.session_amount, st.paid_amount, st.teacher_id;
        ";

        $result = $conn->query($sql);
        $sessions = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $sessions[] = $row;
            }
            $result->free();
        }
        return $sessions;
    }


    function dailyReport()
    {
        $sql = "
            SELECT att.*, 
                   teacher.name AS tname, 
                   (SELECT GROUP_CONCAT(students.name ORDER BY students.name SEPARATOR ', ') 
                    FROM students 
                    WHERE FIND_IN_SET(students.id, att.st_id)) AS snames, 
                   sessions.session_name,
                   sessions.hours
            FROM att
            INNER JOIN teacher 
                ON teacher.id = att.tec_id
            INNER JOIN sessions
                ON sessions.id = att.session_id
            ORDER BY att.date DESC;
        ";

        $result = $this->connect()->query($sql);

        $attendances = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $attendances[] = $row;
            }
            return $attendances;
        } else {
            echo "0 results";
        }
    }


    public function getApprovedAttendances()
    {

        $query = "SELECT att.* , teacher.name AS tname , students.name as sname , sessions.session_name
            FROM att
            INNER JOIN students 
            ON students.id = att.st_id
            INNER JOIN teacher 
            ON teacher.id = att.tec_id
            INNER JOIN sessions
            ON sessions.id = att.session_id
            WHERE att.aprove = 1
            ORDER BY date DESC";


        $conn = $this->connect();
        $result = $conn->query($query);
        $rows = array();

        while ($row = $result->fetch_assoc()) {

            $rows[] = $row;
        }
        //        $conn->close();

        return $rows;
    }

    public function getAllSessions()
    {

        $query = "
            SELECT att.*, 
            teacher.name AS tname, 
            (SELECT GROUP_CONCAT(students.name ORDER BY students.name SEPARATOR ', ') 
            FROM students 
            WHERE FIND_IN_SET(students.id, att.st_id)) AS snames, sessions.session_name
            FROM att
            INNER JOIN teacher 
            ON teacher.id = att.tec_id
            INNER JOIN sessions
            ON sessions.id = att.session_id
            ORDER BY att.date DESC;
        ";


        $conn = $this->connect();
        $result = $conn->query($query);
        $rows = array();
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        //        $conn->close();

        return $rows;
    }


    public function getSpecializations()
    {
        $query = "SELECT * FROM spc";
        $conn = $this->connect();
        $result = $conn->query($query);
        $rows = array();
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        //        $conn->close();

        return $rows;
    }

    public function getTeacherSpecializationsNames($id)
    {
        $query = "SELECT s.id AS spec_id, s.name AS spec_name FROM teacher_specializations ts
              JOIN spc s ON s.id = ts.spec WHERE ts.teacher_id = ?";
        $conn = $this->connect();

        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        $stmt = $conn->prepare($query);
        if (!$stmt) {
            die("Statement preparation failed: " . $conn->error);
        }

        $stmt->bind_param("i", $id);
        if (!$stmt->execute()) {
            die("Query execution failed: " . $stmt->error);
        }

        $result = $stmt->get_result();
        if (!$result) {
            die("Query failed: " . $stmt->error);
        }

        $rows = array();
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }

        //        $stmt->close();
        //        $conn->close();

        return $rows;
    }

    public function getTeacherSpecializations($id)
    {
        $query = "SELECT * FROM teacher_specializations where teacher_id = $id";
        $conn = $this->connect();
        $result = $conn->query($query);
        $rows = array();
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }

        return $rows;
    }

    public function getSpecializationPriceForTeacher($tid, $sid)
    {
        $query = "SELECT percentage FROM session_teachers where teacher_id = $tid and  session_id=$sid";

        $conn = $this->connect();
        $result = $conn->query($query);
        $row = $result->fetch_assoc();
        //        $conn->close();

        return isset($row['price']) ? $row['price'] : '';
    }

    public function unarchiveTeacher($teacher_id)
    {
        $conn = $this->connect();

        // Begin a transaction
        $conn->begin_transaction();

        try {

            $unarchiveQuery = "UPDATE teacher SET is_archived = 0 WHERE id = ?";
            $stmt = $conn->prepare($unarchiveQuery);
            $stmt->bind_param("i", $teacher_id);
            $result = $stmt->execute();

            // Commit the transaction if the query was successful
            if ($result) {
                $conn->commit();
                return true;
            } else {
                // Rollback the transaction if the query failed
                $conn->rollback();
                return false;
            }
        } catch (Exception $e) {
            // Rollback the transaction on exception
            $conn->rollback();
            return false;
        }
    }

    public function unarchiveStudent($student_id)
    {
        $conn = $this->connect();

        // Begin a transaction
        $conn->begin_transaction();

        try {

            $unarchiveQuery = "UPDATE students SET archived = 0 WHERE id = ?";
            $stmt = $conn->prepare($unarchiveQuery);
            $stmt->bind_param("i", $student_id);
            $result = $stmt->execute();

            // Commit the transaction if the query was successful
            if ($result) {
                $conn->commit();
                return true;
            } else {
                $conn->rollback();
                return false;
            }
        } catch (Exception $e) {
            $conn->rollback();
            return false;
        }
    }

    public function updateSchool($id, $name, $type)
    {
        $query = "UPDATE schools SET name = '$name', type = $type where id = $id";
        $conn = $this->connect();
        $result = $conn->query($query);
        return true;
    }

    public function updateSpecialization(int $id, string $name, string $type, int $active)
    {
        $query = "";
        if (strlen($type) !== 0) {
            $query = ", class_type = $type";
        }

        $query = "UPDATE spc SET name = '$name' " . $query . ", active = $active where id = $id";
        //        var_dump($query);die();
        $conn = $this->connect();
        $result = $conn->query($query);
        return true;
    }

    public function deleteSchool($id)
    {
        $query = "UPDATE schools SET is_archived = 1 WHERE id = $id";
        $conn = $this->connect();
        $result = $conn->query($query);
        return true;
    }

    public function unArchiveSchool($id)
    {
        $query = "UPDATE schools SET is_archived = 0 WHERE id = $id";
        $conn = $this->connect();
        $result = $conn->query($query);
        return true;
    }

    public function getAllTeachers()
    {
        $query = "SELECT t.id, t.`user`, t.is_archived, t.name, t.img, t.att_id 
                    FROM teacher t
                    WHERE t.role <> 1 ORDER BY t.id ASC";


        $conn = $this->connect();
        $result = $conn->query($query);
        $rows = array();

        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        //        $conn->close();

        return $rows;
    }

    public function getAllTeachers2()
    {
        $query = "SELECT t.id, t.`user`, spc.name as specializations, ts.spec, t.is_archived, t.name, t.img, t.att_id 
                    FROM teacher t
                    join teacher_specializations ts on ts.teacher_id = t.id
                    join spc on spc.id = ts.spec 
                    WHERE t.role <> 1 AND t.is_archived = 0  ORDER BY t.id ASC";


        $conn = $this->connect();
        $result = $conn->query($query);
        $rows = array();

        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        //        $conn->close();

        return $rows;
    }

    public function getSessionsCount($id)
    {
        $conn = $this->connect();
        $query = "SELECT COUNT(session_id) FROM att WHERE session_id = $id";
        $conn->query($query);
        $result = $conn->query($query);
        $rows = array();

        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        return $rows;
    }

    public function addSession($session_name, $students, $sessionPackage, $materials, $isGroup, $price, $hours, $teachers)
    {
        $conn = $this->connect();
        $conn->begin_transaction();

        try {
            $materialsStr = implode(",", $materials);
            $currentDateTime = date('Y-m-d H:i:s');

            if ($sessionPackage == "حقيبة مدرسية") {
                $query = "INSERT INTO sessions (session_name, type, material, is_group, hours, meetings, price, created_at) 
                      VALUES ('$session_name', '$sessionPackage', '$materialsStr', $isGroup, '0', $hours, $price, '$currentDateTime')";
            } else {
                $query = "INSERT INTO sessions (session_name, type, material, is_group, hours, price, created_at) 
                      VALUES ('$session_name', '$sessionPackage', '$materialsStr', $isGroup, $hours, $price, '$currentDateTime')";
            }

            if (!$conn->query($query)) {
                throw new Exception("خطأ في إنشاء الدورة: " . $conn->error);
            }

            $sessionId = $conn->insert_id;
            $studentSessionPrice = $price / count($students);

            foreach ($students as $studentId) {
                $query = "INSERT INTO session_students (session_id, student_id, session_cost, hours, added_at) 
                      VALUES ('$sessionId', '$studentId', $studentSessionPrice, $hours, '$currentDateTime')";
                if (!$conn->query($query)) {
                    throw new Exception("خطأ في إضافة الطالب إلى الدورة: " . $conn->error);
                }
            }

            $teacherShare = $price * 0.50;

            foreach ($teachers as $teacherId => $teacherData) {
                $percentage = (float)$teacherData['percentage'] / 100;

                // Get teacher name
                $resultName = $conn->query("SELECT name FROM teacher WHERE id = $teacherId");
                $teacherName = $resultName && $resultName->num_rows > 0 ? $resultName->fetch_assoc()['name'] : "معلم رقم $teacherId";

                // Get teacher's specs
                $result = $conn->query("SELECT spec FROM teacher_specializations WHERE teacher_id = $teacherId");
                $teacherSpecs = $result ? array_column($result->fetch_all(MYSQLI_ASSOC), 'spec') : [];

                $hasMatch = false;
                foreach ($materials as $mat) {
                    if (in_array($mat, $teacherSpecs)) {
                        $hasMatch = true;
                        break;
                    }
                }

                if (!$hasMatch) {
                    throw new Exception("لا يمكن إضافة المعلم '$teacherName' لأنه لا يدرّس أي من المواد المختارة.");
                }

                if ($percentage > 0) {
                    $teacherShareAmount = $teacherShare * $percentage;
                    $query = "INSERT INTO session_teachers (session_id, teacher_id, session_amount, percentage, added_at) 
                          VALUES ('$sessionId', '$teacherId', '$teacherShareAmount', '$percentage', '$currentDateTime')";
                    if (!$conn->query($query)) {
                        throw new Exception("خطأ في إضافة المعلم إلى الدورة: " . $conn->error);
                    }
                }
            }

            $conn->commit();
            return true;

        } catch (Exception $e) {
            $conn->rollback();
            return $e->getMessage();
        }
    }


    public function updateSessions($students, $sessions, $teachers = [], $materials = [], $sessionMeta = [])
    {
        $conn = $this->connect();
        $conn->begin_transaction();

        try {
            $is_group = count($students) > 1 ? 1 : 0;
            $materialStr = implode(',', $materials);
            $price = (float)($sessionMeta['session_price'] ?? 0);
            $hours = (int)($sessionMeta['session_hours'] ?? 0);

            foreach ($sessions as $sessionId) {
                $name = $conn->real_escape_string($sessionMeta['session_name'] ?? '');
                $type = $conn->real_escape_string($sessionMeta['session_type'] ?? '');

                $conn->query("UPDATE sessions 
                          SET session_name = '$name', type = '$type', price = $price, hours = $hours,
                              is_group = $is_group, material = '$materialStr'
                          WHERE id = $sessionId");

                $studentCount = count($students);
                $costPerStudent = $studentCount > 0 ? $price / $studentCount : 0;

                foreach ($students as $studentId) {
                    $studentId = (int)$studentId;
                    $conn->query("INSERT INTO session_students 
                            (session_id, student_id, session_cost, hours) 
                            VALUES ($sessionId, $studentId, $costPerStudent, $hours)
                            ON DUPLICATE KEY UPDATE 
                                session_cost = VALUES(session_cost),
                                hours = VALUES(hours)");
                }

                $conn->query("DELETE FROM session_teachers WHERE session_id = $sessionId");

                $share = $price * 0.5;

                foreach ($teachers as $teacherId => $info) {
                    $teacherId = (int)$teacherId;
                    $percentage = (float)($info['percentage'] ?? 0) / 100;
                    $teacherAmount = $share * $percentage;

                    // Get teacher name
                    $resultName = $conn->query("SELECT name FROM teacher WHERE id = $teacherId");
                    $teacherName = $resultName && $resultName->num_rows > 0 ? $resultName->fetch_assoc()['name'] : "معلم رقم $teacherId";

                    // Get specializations
                    $result = $conn->query("SELECT spec FROM teacher_specializations WHERE teacher_id = $teacherId");
                    $teacherSpecs = $result ? array_column($result->fetch_all(MYSQLI_ASSOC), 'spec') : [];

                    $hasMatch = false;
                    foreach ($materials as $mat) {
                        if (in_array($mat, $teacherSpecs)) {
                            $hasMatch = true;
                            break;
                        }
                    }

                    if (!$hasMatch) {
                        throw new Exception("لا يمكن تحديث الدورة لأن المعلم '$teacherName' لا يدرّس أي من المواد المحددة.");
                    }

                    if ($percentage > 0) {
                        $conn->query("INSERT INTO session_teachers 
                                (session_id, teacher_id, session_amount, percentage) 
                                VALUES ($sessionId, $teacherId, $teacherAmount, $percentage)");
                    }
                }
            }

            $conn->commit();
            return true;

        } catch (Exception $e) {
            $conn->rollback();
            return $e->getMessage();
        }
    }

    public function deleteSession(int $sessionId)
    {
        $conn = $this->connect();
        $conn->begin_transaction();

        try {
            // Delete from session_students
            $conn->query("DELETE FROM session_students WHERE session_id = $sessionId");

            // Delete from session_teachers
            $conn->query("DELETE FROM session_teachers WHERE session_id = $sessionId");

            // Delete from sessions
            $conn->query("DELETE FROM sessions WHERE id = $sessionId");

            $conn->commit();
            return true;

        } catch (Exception $e) {
            $conn->rollback();
            return $e->getMessage();
        }
    }



    public function getClasses()
    {
        $conn = $this->connect();
        $query = "SELECT * FROM classes";
        $result = $conn->query($query);
        $rows = array();

        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        //        $conn->close();

        return $rows;
    }

    public function getRemainsData()
    {
        $conn = $this->connect();
        $query = "
                    SELECT
                        ss.student_id,
                        i.id AS payment_id,
                        s.phone AS student_phone,
                        s.name AS student_name,
                        s.class AS student_class,
                        ss.session_id,
                        se.type as session_type,
                        se.session_name,
                        ss.session_cost,
                        ss.total_payments,
                        ss.session_cost - ss.total_payments AS amount_due,
                        MAX(i.date) AS last_payment_date,
                        n.note AS session_note,
                        ss.added_at AS session_added_at  -- Include the added_at datetime
                    FROM
                        session_students ss
                    JOIN
                        students s ON ss.student_id = s.id
                    JOIN
                        sessions se ON ss.session_id = se.id
                    LEFT JOIN 
                        income i ON ss.session_id = i.session_id AND ss.student_id = i.student_id
                    LEFT JOIN
                        notes n ON ss.student_id = n.student_id AND ss.session_id = n.session_id
                    WHERE
                        ss.total_payments < ss.session_cost
                        AND ss.payment_status != 'paid'
                    GROUP BY
                        ss.student_id, s.phone, s.name, ss.session_id, se.session_name, ss.session_cost, ss.total_payments, n.note, ss.added_at
                    ORDER BY
                        last_payment_date DESC; 
                    ";
        $result = $conn->query($query);
        $rows = array();

        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        return $rows;
    }

    public function getSchools()
    {
        $conn = $this->connect();
        $query = "SELECT * FROM schools";
        $result = $conn->query($query);
        $rows = array();

        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        //        $conn->close();

        return $rows;
    }

    public function getPassword($id, $old)
    {
        $query = "SELECT password from teacher where id = $id and password =$old";

        $conn = $this->connect();
        $result = $conn->query($query);
        //        $conn->close();

        return $result;
    }


    public function changPassword($id, $new)
    {

        $query = "UPDATE teacher SET password = $new  WHERE id = $id";

        $conn = $this->connect();
        $result = $conn->query($query);

        return $result;
    }


    public function changStatus($id, $st)
    {

        $query = "UPDATE att SET aprove = $st  WHERE id = $id";
        $conn = $this->connect();
        $result = $conn->query($query);

        return $result;
    }

    //    duplicated
    public function AddSpc($name)
    {


        $query = "INSERT INTO `spc` (`id`, `name`) VALUES (NULL, '$name'); ";

        $conn = $this->connect();
        $result = $conn->query($query);

        return $result;
    }


    public function endSession($id)
    {
        $conn = $this->connect();

        // Update 'exit' column with current timestamp for all students with the same att_id
        $query = "UPDATE att AS a
                    INNER JOIN students AS s
                    ON s.att_id = a.id
                    SET a.`exit` = current_timestamp() WHERE a.session_id = ?";

        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        //        $stmt->close();

        // Calculate total session time and update 'total' column for each student
        $query2 = "UPDATE att AS a
               INNER JOIN students AS s ON s.att_id = a.id
               SET a.`total` = TIMEDIFF(a.`exit`, a.`enter`)
               WHERE a.session_id = ?";
        $stmt2 = $conn->prepare($query2);
        $stmt2->bind_param("i", $id);
        $result2 = $stmt2->execute();
        //        $stmt2->close();

        // Reset 'InSess' and 'att_id' to 0 in students table for all students with the same att_id
        $query3 = "UPDATE students s
                    INNER JOIN session_students ss ON s.id = ss.student_id
                    SET s.InSess = 0, s.att_id = 0 
                    WHERE ss.session_id = ?";
        // var_dump($query3);die();
        $stmt3 = $conn->prepare($query3);
        $stmt3->bind_param("i", $id);
        $result3 = $stmt3->execute();
        //        $stmt3->close();

        // Reset 'att_id' to 0 in teacher table
        $query4 = "UPDATE teacher t
                    INNER JOIN session_teachers st ON t.id = st.teacher_id
                    SET t.att_id = 0
                    WHERE st.session_id = ?";
        $stmt4 = $conn->prepare($query4);
        $stmt4->bind_param("i", $id);
        $result4 = $stmt4->execute();

        //        $stmt4->close();

        // Close the database connection
        //        $conn->close();

        // Return true if all queries were successful, otherwise false
        return $result && $result2 && $result3 /*&& $result4*/ ;
    }

    public function archiveStudent($id)
    {
        $conn = $this->connect();

        // Update the student record to mark it as archived
        $archiveQuery = "UPDATE students SET archived = 1 WHERE id = ?";
        $stmt = $conn->prepare($archiveQuery);

        if ($stmt === false) {
            die('Prepare failed: ' . $conn->error);
        }

        $stmt->bind_param("i", $id);
        $success = $stmt->execute();

        if (!$success) {
            die('Execute failed: ' . $stmt->error);
        }

        // All updates successful
        return true;
    }


    public function deleteStudent($student_id)
    {
        // Sanitize the input to prevent SQL injection
        $conn = $this->connect();
        $student_id = $conn->real_escape_string($student_id);

        // Query to delete the student
        $deleteQuery = "DELETE FROM students WHERE id = '$student_id'";

        // Execute the query
        $result = $conn->query($deleteQuery);
        if (!$result) {
            echo "Error: " . $conn->error;
        }

        // Return true if deletion was successful, false otherwise
        return $result;
    }

    // duplicated
    public function addSpecialization($new_spec, $class)
    {
        $conn = $this->connect();

        // Sanitize the input to prevent SQL injection
        $new_spec = $conn->real_escape_string($new_spec);

        // Insert the new specialization into the database
        $insertQuery = "INSERT INTO spc (name, class_type) VALUES ('$new_spec',$class)";
        $result = $conn->query($insertQuery);

        return $result;
    }


    public function deleteTeacher($teacher_id)
    {
        $conn = $this->connect();

        // Begin a transaction
        $conn->begin_transaction();

        try {
            // Update the teacher record to archive it
            $archiveTeacherQuery = "UPDATE teacher SET is_archived = TRUE WHERE id = ?";
            $stmt = $conn->prepare($archiveTeacherQuery);
            $stmt->bind_param('i', $teacher_id);
            $resultTeacher = $stmt->execute();

            // Update related records to reflect that the teacher is archived (optional)
            // Example: Set teacher_id to NULL in related tables or handle as needed
            // $updateRelatedQuery = "UPDATE att SET tec_id = NULL WHERE techer_id = ?";
            // $stmt = $conn->prepare($updateRelatedQuery);
            // $stmt->bind_param('i', $teacher_id);
            // $resultRelated = $stmt->execute();

            // Commit the transaction if all queries were successful
            if ($resultTeacher /*&& $resultRelated*/) {
                $conn->commit();
                return true;
            } else {
                // Rollback the transaction if any query failed
                $conn->rollback();
                return false;
            }
        } catch (Exception $e) {
            // Rollback the transaction on exception
            $conn->rollback();
            return false;
        }
    }


    public function updateStudent($id, $name, $phone, $class, $school, $selectedTeachers)
    {
        $conn = $this->connect();

        // Update student basic information
        // Prepare the base query
        $updateQuery = "UPDATE students SET name = ?, phone = ?";
        $params = [$name, $phone];
        $types = "ss"; // assuming both are strings

        // Add class if provided
        if (!is_null($class)) {
            $updateQuery .= ", class = ?";
            $params[] = $class;
            $types .= "s";
        }

        // Add school if provided
        if (!is_null($school)) {
            $updateQuery .= ", school = ?";
            $params[] = $school;
            $types .= "s";
        }

        // Add WHERE condition
        $updateQuery .= " WHERE id = ?";
        $params[] = $id;
        $types .= "i"; // assuming id is integer

        // Prepare and execute the statement
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param($types, ...$params);
        $updateResult = $stmt->execute();
        if (!$updateResult) {
            // Error occurred while updating student basic information
            return false;
        }

        // Initialize an array to store teacher IDs
        $teacherIds = [];

        // Search for teacher IDs based on teacher names
        foreach ($selectedTeachers as $teacherName) {
            // Escape the teacher name to prevent SQL injection
            $escapedTeacherName = $conn->real_escape_string($teacherName);

            // Query to select the teacher ID based on the teacher name
            $selectTeacherQuery = "SELECT id FROM teacher WHERE name = '$escapedTeacherName'";

            $selectTeacherResult = $conn->query($selectTeacherQuery);

            if ($selectTeacherResult && $selectTeacherResult->num_rows > 0) {
                // Fetch the teacher ID and add it to the array
                $teacherData = $selectTeacherResult->fetch_assoc();
                $teacherIds[] = $teacherData['id'];
            } else {
                // If teacher name is not found, skip adding the ID
                continue;
            }
        }

        // Convert the array of teacher IDs to a string
        $teacherIdsString = implode(',', $teacherIds);


        // Insert the student-teacher relationships into the database
        $insertQuery = "UPDATE students SET tec_id ='$teacherIdsString' WHERE id = $id";

        $insertResult = $conn->query($insertQuery);

        if (!$insertResult) {
            // Error occurred while inserting student-teacher relationships
            return false;
        }

        // All updates successful
        return true;
    }


    public function updateTeacher($id, $name, $email, $specs, $password, $id_number, $degree, $phone_number, $address)
    {
        $conn = $this->connect();

        // Update teacher basic information
        $updateQuery = "UPDATE teacher SET name = ?, user = ?, password = ?,id_number= ?, degree= ?, phone_number= ?, address= ? WHERE id = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("sssisisi", $name, $email, $password, $id_number, $degree, $phone_number, $address, $id);
        $updateResult = $stmt->execute();

        if (!$updateResult) {
            // Error occurred while updating teacher basic information
            return false;
        }

        // Clear existing specializations
        $deleteQuery = "DELETE FROM teacher_specializations WHERE teacher_id = ?";
        $stmt = $conn->prepare($deleteQuery);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        // Insert new specializations

        foreach ($specs as $specId => $specData) {
            $price = isset($specData['price']) ? intval($specData['price']) : null;

            // Check if the specialization exists
            $checkQuery = "SELECT * FROM teacher_specializations WHERE teacher_id = ? AND spec = ?";
            $stmt = $conn->prepare($checkQuery);

            if ($stmt === false) {
                die('Prepare failed1: ' . $conn->error);
            }

            $stmt->bind_param("ii", $id, $specId);
            $stmt->execute();
            $checkResult = $stmt->get_result();


            // Insert new specialization
            if (isset($specData['price'])) {
                $insertSpecQuery = "INSERT INTO teacher_specializations (teacher_id, spec) VALUES (?, ?)";
                $stmt = $conn->prepare($insertSpecQuery);

                if ($stmt === false) {
                    die('Prepare failed3: ' . $conn->error);
                }

                $stmt->bind_param("ii", $id, $specId);
                $insertSpecResult = $stmt->execute();

                if (!$insertSpecResult) {
                    // Error occurred while inserting specialization price
                    return false;
                }
            }
        }

        // All updates successful
        return true;
    }


    public function getStudentData($id)
    {
        $query = "SELECT students.*,
       GROUP_CONCAT(teacher.name) AS teacher_names,
       (
           SELECT GROUP_CONCAT(teacher.name)
           FROM teacher
       ) AS all_teacher_names,
       (
           SELECT GROUP_CONCAT(teacher.name)
           FROM teacher
           WHERE FIND_IN_SET(teacher.id, REPLACE(REPLACE(students.tec_id, '[', ''), ']', '')) > 0
       ) AS selected_teacher_names
FROM students
LEFT JOIN teacher ON FIND_IN_SET(teacher.id, REPLACE(REPLACE(students.tec_id, '[', ''), ']', '')) > 0
WHERE students.id = $id
GROUP BY students.id

";
        $conn = $this->connect();
        $result = $conn->query($query);
        if ($result->num_rows > 0) {
            // If the current user has an open session, return their data
            return $result->fetch_assoc();
        } else {
            return 'فشل احضار بيانات الطالب';
        }
    }

    public function getActiveAttendanceStudents($t_id)
    {
        $conn = $this->connect();
        $teacherId = $t_id;
        $query = "
        SELECT DISTINCT 
            students.*, 
            att.enter, 
            att.session_id,
            sessions.session_name,
            sessions.type
        FROM 
            students
        INNER JOIN 
            att ON students.att_id = att.id
        INNER JOIN 
            session_teachers st ON att.session_id = st.session_id
        INNER JOIN 
            teacher t ON t.att_id = att.id
        INNER JOIN 
            sessions ON att.session_id = sessions.id
        WHERE 
            students.InSess = 1 
            AND students.att_id != 0
            AND t.id = ?;
    ";
        $stmt = $conn->prepare($query);
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }
        $stmt->bind_param("i", $teacherId);
        $stmt->execute();
        $result = $stmt->get_result();
        //
        $students = [];
        while ($row = $result->fetch_assoc()) {
            $students[] = $row;
        }

        //        $stmt->close();
        //        $conn->close();

        return $students;
    }


    public function getActiveAttendance($t_id)
    {
        $conn = $this->connect();
        $teacherId = $t_id;
        // Fetch the user with InSess = 1 and att_id != 0 and att_id associated with teacher's tec_id
        $query = "
        SELECT DISTINCT 
            students.*, 
            att.enter, 
            att.session_id
        FROM 
            students
        INNER JOIN 
            att ON students.att_id = att.id
        INNER JOIN 
            session_teachers st ON att.session_id = st.session_id
        INNER JOIN 
            teacher t ON t.att_id = att.id
        WHERE 
            students.InSess = 1 
            AND students.att_id != 0
            AND t.id = ?
    ";

        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $teacherId);
        $stmt->execute();
        $result = $stmt->get_result();
        //
        $students = [];
        while ($row = $result->fetch_assoc()) {
            $students[] = $row;
        }

        //        $stmt->close();
        //        $conn->close();

        return $students;
    }


    public function getTeacherData($id)
    {
        $query = "SELECT t.*
                    FROM teacher AS t
                    WHERE t.id =$id ";

        $conn = $this->connect();
        $result = $conn->query($query);
        $rows = array();

        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }

        return $rows;
    }

    public function getSingleTeacher($id)
    {
        $query = "SELECT * from teacher WHERE id=$id ";

        $conn = $this->connect();
        $result = $conn->query($query);
        $rows = array();

        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }

        return $rows;
    }

    public function getSessionDataDetailed($sessionId)
    {
        $conn->$this->connect();
        $query = "SELECT s.*, st.*, ss.*,s.hours AS session_hours,
                   ss.hours AS student_hours,  GROUP_CONCAT(DISTINCT stu.name) AS student_names, GROUP_CONCAT(DISTINCT t.name) AS teacher_names,
                   GROUP_CONCAT(DISTINCT spc.name) AS materials
                    FROM sessions s
                     LEFT JOIN session_teachers st ON s.id = st.session_id
                     LEFT JOIN session_students ss ON s.id = ss.session_id
                     LEFT JOIN students stu ON ss.student_id = stu.id
                     LEFT JOIN teacher t ON st.teacher_id = t.id
                     LEFT JOIN spc ON FIND_IN_SET(spc.id, s.material) > 0
             where s.id = $sessionId
                     GROUP BY s.id
            ORDER BY s.id desc";
        $result = $conn->query($query);
    }

    public function getSessionsDataDetailed()
    {
        $conn = $this->connect();

        // Fetch all sessions
        $sessionQuery = "SELECT * FROM sessions ORDER BY id DESC";
        $sessionResult = $conn->query($sessionQuery);

        $sessions = [];

        while ($session = $sessionResult->fetch_assoc()) {
            $sessionID = $session['id'];

            // Basic session data
            $sessions[$sessionID] = [
                'id' => $sessionID,
                'session_name' => $session['session_name'],
                'type' => $session['type'],
                'hours' => $session['hours'],
                'price' => $session['price'],
                'materials' => [],
                'teachers' => [],
                'students' => [],
            ];

            // Get materials (assuming comma-separated IDs in `material`)
            if (!empty($session['material'])) {
                $materialIDs = explode(',', $session['material']);
                $idsPlaceholders = implode(',', array_fill(0, count($materialIDs), '?'));
                $stmt = $conn->prepare("SELECT name FROM spc WHERE id IN ($idsPlaceholders)");
                $stmt->bind_param(str_repeat('i', count($materialIDs)), ...$materialIDs);
                $stmt->execute();
                $res = $stmt->get_result();
                while ($mat = $res->fetch_assoc()) {
                    $sessions[$sessionID]['materials'][] = $mat['name'];
                }
            }

            // Fetch teachers
            $teacherQuery = "
            SELECT t.id AS teacher_id, t.name 
            FROM session_teachers st 
            JOIN teacher t ON st.teacher_id = t.id 
            WHERE st.session_id = ?";
            $stmt = $conn->prepare($teacherQuery);
            $stmt->bind_param('i', $sessionID);
            $stmt->execute();
            $teacherResult = $stmt->get_result();
            while ($t = $teacherResult->fetch_assoc()) {
                $sessions[$sessionID]['teachers'][] = [
                    'teacher_id' => $t['teacher_id'],
                    'teacher_names' => $t['name'],
                ];
            }

            // Fetch students
            $studentQuery = "
            SELECT s.id AS student_id, s.name 
            FROM session_students ss 
            JOIN students s ON ss.student_id = s.id 
            WHERE ss.session_id = ?";
            $stmt = $conn->prepare($studentQuery);
            $stmt->bind_param('i', $sessionID);
            $stmt->execute();
            $studentResult = $stmt->get_result();
            while ($s = $studentResult->fetch_assoc()) {
                $sessions[$sessionID]['students'][] = [
                    'student_id' => $s['student_id'],
                    'student_names' => $s['name'],
                ];
            }
        }

        return $sessions;
    }


    public function allStudents()
    {
        $query = "SELECT s.*, sc.name AS school_name FROM students s
                    JOIN schools sc ON s.school = sc.id";
        $conn = $this->connect();
        $result = $conn->query($query);
        $rows = array();
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }

        return $rows;
    }

    public function allStudentsDailyReport($session_id)
    {
        $query = " SELECT DISTINCT s.*, sc.name AS school_name, sessions.session_name, ss.session_id 
        FROM students s
        JOIN schools sc ON s.school = sc.id
        JOIN session_students ss ON s.id = ss.student_id
        JOIN sessions ON sessions.id = ss.session_id
        WHERE ss.session_id = $session_id";
        $conn = $this->connect();
        $result = $conn->query($query);
        $rows = array();
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }

        return $rows;
    }


    public function getTeacherOpenSessions($id)
    {


        $query = "SELECT att.*, teacher.name AS teacher_name,students.name as sname , students.img
      FROM att
      INNER JOIN students ON students.id = att.st_id
      INNER JOIN teacher ON teacher.id = att.tec_id
      WHERE teacher.att_id = att.id AND teacher.id = $id AND students.InSess = 1;
      ";

        $conn = $this->connect();
        $result = $conn->query($query);
        $rows = array();

        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }

        return $rows;
    }

    public function getAllOpenSessions()
    {
        $query = "SELECT 
            att.*, 
            teacher.name AS teacher_name,
            GROUP_CONCAT(DISTINCT students.name SEPARATOR ', ') AS student_names,
            students.img, 
            spc.name AS session_name
        FROM 
            att
        INNER JOIN 
            students ON FIND_IN_SET(students.id, att.st_id)
        INNER JOIN 
            teacher ON teacher.id = att.tec_id
        INNER JOIN 
            spc ON spc.id = att.spc
        WHERE 
            teacher.att_id = att.id  
            AND students.InSess = 1
        GROUP BY 
            att.id, 
            teacher.name, 
            students.img, 
            spc.name
      ";

        $conn = $this->connect();
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();

        $sessions = [];
        while ($row = $result->fetch_assoc()) {
            $sessions[] = $row;
        }

        //        $stmt->close();
        //        $conn->close();

        return $sessions;
    }

    public function getOpenSession($id)
    {
        $query = "
            SELECT 
                att.*, 
                sessions.session_name, sessions.`type`, sessions.price,sessions.is_group,sessions.`status`,
                teacher.name AS teacher_name,
                GROUP_CONCAT(DISTINCT students.name SEPARATOR ', ') AS student_names,
                students.img, 
                spc.name AS material_name
            FROM 
                att
            INNER JOIN 
                students ON FIND_IN_SET(students.id, att.st_id)
            INNER JOIN 
                teacher ON teacher.id = att.tec_id
            INNER JOIN 
                sessions ON sessions.id = att.session_id
            INNER JOIN 
                spc ON spc.id = att.spc
            WHERE 
                teacher.att_id = att.id  
                AND students.InSess = 1
                AND teacher.id = $id
            GROUP BY 
                att.id, 
                sessions.id, 
                teacher.id, 
                students.img, 
                spc.id
        ";

        $conn = $this->connect();
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();

        $sessions = [];
        while ($row = $result->fetch_assoc()) {
            $sessions[] = $row;
        }

        //        $stmt->close();
        //        $conn->close();

        return $sessions;
    }

    public function totalPayments($id)
    {
        $conn = $this->connect();

        $query = "SELECT att.*, students.name AS name
                FROM att
                INNER JOIN students ON students.id = att.st_id
                WHERE att.tec_id = $id AND att.aprove = 1
                ORDER BY att.id DESC";

        $result = $conn->query($query);

        $totalSessions = $result->num_rows; // Total number of approved sessions
        $totalPrice = 0; // Initialize total price

        while ($row = $result->fetch_assoc()) {
            // Calculate the price for each session based on duration
            $duration = $row['total']; // Assuming there's a column named 'total' in 'att' table
            $spec_id = $row['spc'];
            $teacher_id = $row['tec_id'];
            $price = $this->calculatePrice($duration, $spec_id, $teacher_id); // Use $this-> to refer to the method within the same class
            $totalPrice += $price; // Add the calculated price to the total price
        }

        // Return an array containing the total number of sessions and the total price
        return array('total_sessions' => $totalSessions, 'total_price' => $totalPrice);
    }

    public function getTeacherSessions($id)
    {
        $conn = $this->connect();

        $query = "SELECT st.*, t.name, s.session_name, s.type
                  FROM session_teachers st
                  JOIN sessions s ON st.session_id = s.id
                  JOIN teacher t ON st.teacher_id = t.id
                  WHERE t.id =$id ";

        $result = $conn->query($query);
        $rows = array();

        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        return $rows;
    }

    public function getTeacherSessionsAttendances($id)
    {
        $conn = $this->connect();

        $query = "SELECT 
                    att.*, 
                    GROUP_CONCAT(students.name SEPARATOR ', ') AS student_names, 
                    spc.name AS session_name
                FROM 
                    att
                INNER JOIN 
                    students ON FIND_IN_SET(students.id, att.st_id) > 0
                INNER JOIN 
                    spc ON spc.id = att.spc
                WHERE 
                    att.tec_id = $id
                GROUP BY 
                    att.id
                ORDER BY 
                    att.id DESC;";

        $result = $conn->query($query);
        $rows = array();

        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        return $rows;
    }

    public function calculatePrice($time, $spec_id, $teacher_id)
    {


        list($hours, $minutes, $seconds) = explode(':', $time);
        // Calculate the total number of hours
        $totalHours = (int)$hours + ((int)$minutes / 60) + ((int)$seconds / 3600);
        $price = $this->getSpecializationPriceForTeacher($teacher_id, $spec_id);
        // Calculate the total price
        $totalPrice = $totalHours * floatval($price);

        return $totalPrice;
    }

    public function CheckExist($email)
    {

        $query = "SELECT * FROM users WHERE email='$email'";
        $conn = $this->connect();
        $result = $conn->query($query);

        if (mysqli_num_rows($result) > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function OpenATT($sessionId, $teacherId, $sNames, $material)
    {
        $conn = $this->connect();

        // Get material ID
        $materialToIdQuery = "SELECT id FROM spc WHERE name = ?";
        $materialStmt = $conn->prepare($materialToIdQuery);
        $materialStmt->bind_param("s", $material);
        $materialStmt->execute();
        $materialResult = $materialStmt->get_result();
        $materialRow = $materialResult->fetch_assoc();
        $materialId = $materialRow['id'];

        // Get student IDs and filter out archived students
        $studentIds = [];
        $sNames = explode(",", $sNames);

        foreach ($sNames as $sName) {
            $nameToIdQuery = "SELECT id, archived FROM students WHERE name = ?";
            $nameStmt = $conn->prepare($nameToIdQuery);
            $nameStmt->bind_param("s", $sName);
            $nameStmt->execute();
            $nameResult = $nameStmt->get_result();
            $nameRow = $nameResult->fetch_assoc();

            if ($nameRow && !$nameRow['archived']) {
                $studentIds[] = $nameRow['id'];
            }
        }

        if (empty($studentIds)) {
            return false; // No valid students to add
        }

        $studentIdsStr = implode(",", $studentIds);

        // Insert attendance record
        $query = "INSERT INTO att (st_id, tec_id, spc, session_id) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("siii", $studentIdsStr, $teacherId, $materialId, $sessionId);
        $result = $stmt->execute();
        $attId = $conn->insert_id;

        if ($result) {
            $success = true;

            // Update students' attendance status
            foreach ($studentIds as $studentId) {
                $updateQuery = "UPDATE students SET att_id = ?, InSess = 1 WHERE id = ?";

                $updateStmt = $conn->prepare($updateQuery);
                $updateStmt->bind_param("ii", $attId, $studentId);
                $result2 = $updateStmt->execute();
                if (!$result2) {
                    return false; // Fail if any student update fails
                }
            }

            // Update teacher's attendance ID
            $updateTeacherQuery = "UPDATE teacher SET att_id = ? WHERE id = ?";
            $updateTeacherStmt = $conn->prepare($updateTeacherQuery);
            $updateTeacherStmt->bind_param("ii", $attId, $teacherId);
            $result3 = $updateTeacherStmt->execute();
            if (!$result3) {
                $success = false;
            }
        } else {
            $success = false;
        }

        return $success;
    }

    public function getUnArchivedAttendanceStudents($teacherId)
    {
        $conn = $this->connect();
        $query = "SELECT * FROM students WHERE teacher_id = ? AND archived = 0"; // Exclude archived students
        $stmt = $conn->prepare($query);

        if ($stmt === false) {
            die('Prepare failed: ' . $conn->error);
        }

        $stmt->bind_param("i", $teacherId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function addTeacher($user, $password, $name, $specs, $img, $role, $id_number, $degree, $phone_number, $address)
    {
        $conn = $this->connect();

        // Insert teacher basic information
        if ($img == "") {
            $query = "INSERT INTO teacher (user, password, name, role, id_number, degree, phone_number, address)
                  VALUES ('$user', '$password', '$name', '$role', $id_number, '$degree', $phone_number, '$address')";
        } else {
            $query = "INSERT INTO teacher (user, password, name, img, role, id_number, degree, phone_number, address)
                  VALUES ('$user', '$password', '$name', '$img', '$role', $id_number, '$degree', $phone_number, '$address')";
        }
        //
        //        var_dump($specs);die();

        $result = $conn->query($query);

        if (!$result) {
            // Error occurred while inserting teacher basic information
            return false;
        }

        // Get the ID of the newly inserted teacher
        $teacherId = $conn->insert_id;
        // Insert teacher specializations
        foreach ($specs as $specId => $specData) {
            if (isset($specData['id'])) {

                $insertSpecQuery = "INSERT INTO teacher_specializations (teacher_id, spec) 
                                VALUES ('$teacherId', '$specId')";
                //                var_dump($insertSpecQuery);die();
                $insertSpecResult = $conn->query($insertSpecQuery);

                if (!$insertSpecResult) {
                    // Error occurred while inserting specialization price
                    return false;
                }
            }
        }

        return true;
    }

    public function AddST(
        $name,
        $phone,
        $school_name,
        $class
    )
    {
        try {
            $conn = $this->connect();
            $query = "INSERT INTO students (name, phone, tec_id, school, class) VALUES ('$name', '$phone', '0',$school_name, '$class')";
            $conn->query($query);
            return true;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function addSchool(
        $name,
        $type
    )
    {
        try {
            $conn = $this->connect();
            $query = "INSERT INTO schools (name, type) VALUES ('$name', $type)";
            $conn->query($query);
            return true;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Get payment history
     */
    public function getPaymentHistory() {
        $query = "SELECT i.*, s.name AS student_name, ss.session_name 
                 FROM income i
                 LEFT JOIN students s ON i.student_id = s.id
                 LEFT JOIN sessions ss ON i.session_id = ss.id
                 ORDER BY i.date DESC";

        $result = $this->conn->query($query);
        $payments = [];

        while($row = $result->fetch_assoc()) {
            $payments[] = $row;
        }

        return $payments;
    }

    /**
     * Reverse a payment
     */
    public function reversePayment($paymentId, $studentId, $sessionId, $amount) {
        $conn = $this->connect();
        $conn->begin_transaction();
        try {
            // 1. Delete the payment record
            $this->deletePayment($paymentId);

            // 2. If this was a student payment, update their records
            if ($studentId != -1 && $sessionId != -1) {
                $this->updateStudentPayment($studentId, $sessionId, -$amount);
            }

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollback();
            error_log("Payment reversal failed: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete payment record
     */
    private function deletePayment($paymentId) {
        $query = "DELETE FROM income WHERE id = ?";
        $conn = $this->connect();

        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $paymentId);
        return $stmt->execute();
    }

    /**
     * Update student payment status
     */
    private function updateStudentPayment($studentId, $sessionId, $amountChange) {
        $conn = $this->connect();

        // Get current payment status
        $query = "SELECT total_payments, session_cost FROM session_students 
                 WHERE student_id = ? AND session_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('ii', $studentId, $sessionId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        $newTotalPayments = $row['total_payments'] + $amountChange;
        $newPaymentStatus = $this->calculatePaymentStatus($newTotalPayments, $row['session_cost']);

        // Update student's payment record
        $updateQuery = "UPDATE session_students 
                       SET total_payments = ?, payment_status = ?
                       WHERE student_id = ? AND session_id = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param('dsii', $newTotalPayments, $newPaymentStatus, $studentId, $sessionId);
        return $stmt->execute();
    }

    /**
     * Calculate payment status based on amounts
     */
    private function calculatePaymentStatus($totalPaid, $sessionCost) {
        if ($totalPaid >= $sessionCost) {
            return 'مدفوع بالكامل';
        } elseif ($totalPaid > 0) {
            return 'مدفوع جزئياً';
        } else {
            return 'غير مدفوع';
        }
    }


    public function getAllOutcomes()
    {
        $conn = $this->connect();
        $query = "SELECT o.*, t.name
                  FROM outcome o
                  LEFT JOIN teacher t ON o.receiver = t.id
                  ORDER BY id DESC";
        $result = $conn->query($query);

        $outcomes = [];
        while ($row = $result->fetch_assoc()) {
            $dateTime = new DateTime($row['date']);
            $row['date'] = $dateTime->format('Y-m-d'); // Format as YYYY-MM-DD

            $outcomes[] = $row;
        }

        return $outcomes;
    }

    public function getIncomeGraph($year = null, $month = null)
    {
        $conn = $this->connect();

        // Base query
        $query = "SELECT date, amount FROM income";

        // Add filtering if year and month are provided
        if ($year && $month) {
            $query .= " WHERE YEAR(date) = ? AND MONTH(date) = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('ii', $year, $month);
        } else {
            $query .= " ORDER BY date ASC";
            $stmt = $conn->prepare($query);
        }

        // Execute the query and fetch results
        $stmt->execute();
        $result = $stmt->get_result();

        $rows = array();
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }

        return $rows;
    }

    public function getYearlyIncomeGraph($year)
    {
        $conn = $this->connect();
        $query = "SELECT date, amount FROM income WHERE YEAR(date) = ? ORDER BY date ASC";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $year);
        $stmt->execute();
        $result = $stmt->get_result();
        $rows = array();

        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        return $rows;
    }

    public function getOutcomeGraph($year, $month = null)
    {
        $conn = $this->connect();
        if ($month) {
            $query = "SELECT date, amount FROM outcome WHERE YEAR(date) = ? AND MONTH(date) = ? ORDER BY date ASC";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('ii', $year, $month);
        } else {
            $query = "SELECT date, amount FROM outcome WHERE YEAR(date) = ? ORDER BY date ASC";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('i', $year);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $rows = array();

        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        return $rows;
    }

    public function getIncomeStatistics()
    {
        $conn = $this->connect();
        $query = "SELECT COUNT(*) AS count, SUM(amount) AS total_amount FROM income";
        $result = $conn->query($query);

        return $result->fetch_assoc();
    }

    public function getOutcomeStatistics()
    {
        $conn = $this->connect();
        $query = "SELECT COUNT(*) AS count, SUM(amount) AS total_amount FROM outcome";
        $result = $conn->query($query);

        return $result->fetch_assoc();
    }

    public function getTeachersOutcomeStatistics()
    {
        $conn = $this->connect();
        $query = "SELECT COUNT(*) AS count, SUM(amount) AS total_amount FROM outcome WHERE receiver <> 0";
        $result = $conn->query($query);

        return $result->fetch_assoc();
    }

    public function getOthersOutcomeStatistics()
    {
        $conn = $this->connect();
        $query = "SELECT COUNT(*) AS count, SUM(amount) AS total_amount FROM outcome WHERE receiver = 0";
        $result = $conn->query($query);

        return $result->fetch_assoc();
    }
}
