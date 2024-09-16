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


    public function getAllStudents() // for admin
    {
        $conn = $this->connect();

        // Use LIKE operator to find students where the teacher ID is within the tec_id string
        $query = "SELECT students.*,
            GROUP_CONCAT(teacher.name) AS teacher_names
            FROM students
            LEFT JOIN teacher ON teacher.id=students.tec_id
            GROUP BY students.id ";

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
        $query = "SELECT * FROM spc WHERE active <> 1";
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
        $query = "SELECT t.id, t.`user`, spc.name as specializations, ts.spec, t.is_archived, t.name, t.img, t.att_id 
                    FROM teacher t
                    join teacher_specializations ts on ts.teacher_id = t.id
                    join spc on spc.id = ts.spec 
                    WHERE t.role <> 1  ORDER BY t.id ASC";


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
        // var_dump($session_name, $students, $sessionPackage, $materials, $isGroup, $price, $hours, $teachers);die();
        $conn = $this->connect();
        $materialsArray = $materials;
        $materials = implode(",", $materials);
        $currentDateTime = date('Y-m-d H:i:s'); // Get the current datetime

        // Insert into sessions table with created_at
        if ($sessionPackage == "حقيبة مدرسية") {
            $query = "INSERT INTO sessions (session_name, type, material, is_group, hours, meetings, price, created_at) 
                  VALUES ('$session_name', '$sessionPackage', '$materials', $isGroup, '0', $hours, $price, '$currentDateTime')";
        } else {
            $query = "INSERT INTO sessions (session_name, type, material, is_group, hours, price, created_at) 
                  VALUES ('$session_name', '$sessionPackage', '$materials', $isGroup, $hours, $price, '$currentDateTime')";
        }

        if (!$conn->query($query)) {
            return "Error inserting into sessions: " . $conn->error; // Return error message if query fails
        }

        $sessionId = $conn->insert_id;
        $studentSessionPrice = $price/ count($students);
        // Insert into session_students table with added_at
        foreach ($students as $studentId) {
            $query = "INSERT INTO session_students (session_id, student_id, session_cost, added_at) 
                  VALUES ('$sessionId', '$studentId', $studentSessionPrice, '$currentDateTime')";
            if (!$conn->query($query)) {
                return "Error inserting into session_students: " . $conn->error; // Return error message if query fails
            }
        }

        // Calculate amounts
        $totalPrice = $price;
        // $centerShare = $totalPrice * 0.50; // 50% to the center
        $teacherShare = $totalPrice * 0.50; // 50% to be distributed among teachers

        // Determine the total number of specializations
        $totalSpecializations = 0;
        foreach ($teachers as $teacherId => $teacherData) {
            $totalSpecializations += count($teacherData['specializations']);
        }

        // Insert into session_teachers table with added_at
        foreach ($teachers as $teacherId => $teacherData) {
            $percentage = $teacherData['percentage'] / 100;
            $teacherSpecializationsCount = count($teacherData['specializations']);
            $teacherShareAmount = ($teacherSpecializationsCount / $totalSpecializations) * $teacherShare;

            $query = "INSERT INTO session_teachers (session_id, teacher_id, session_amount, percentage, added_at) 
                  VALUES ('$sessionId', '$teacherId', '$teacherShareAmount', '$percentage', '$currentDateTime')";
            if (!$conn->query($query)) {
                return "Error inserting into session_teachers: " . $conn->error; // Return error message if query fails
            }
        }

        return true; // Return true if all queries succeed
    }






    public function updateSessions($students, $sessions)
    {
        $conn = $this->connect();
    
        // Start transaction to ensure atomicity
        $conn->begin_transaction();
    
        try {
            // Step 1: Update is_group status based on the number of students
            $is_group = count($students) > 1 ? 1 : 0;
    
            // Prepare to fetch session details
            $sessionIds = implode(',', array_map('intval', $sessions));
    
            // Get existing session details (price and materials)
            $query = "SELECT id, price, material FROM sessions WHERE id IN ($sessionIds)";
            $result = $conn->query($query);
    
            $sessionsData = [];
            while ($row = $result->fetch_assoc()) {
                $sessionsData[$row['id']] = [
                    'price' => $row['price'],
                    'students_count' => 0, // Will be updated later
                    'materials' => explode(',', $row['material']) // List of materials for the session
                ];
            }
    
            // Count the current number of students in each session
            $query = "SELECT session_id, COUNT(student_id) as student_count FROM session_students WHERE session_id IN ($sessionIds) GROUP BY session_id";
            $result = $conn->query($query);
    
            while ($row = $result->fetch_assoc()) {
                $sessionId = $row['session_id'];
                if (isset($sessionsData[$sessionId])) {
                    $sessionsData[$sessionId]['students_count'] = $row['student_count'];
                }
            }
    
            // Step 2: Update session_students table with new students
            foreach ($students as $studentId) {
                foreach ($sessions as $sessionId) {
                    // Check if the student is already in the session
                    $query = "INSERT IGNORE INTO session_students (session_id, student_id) VALUES ($sessionId, $studentId)";
                    $conn->query($query);
                }
            }
    
            // Step 3: Update session prices based on the new student count and update student costs
            foreach ($sessions as $sessionId) {
                if (isset($sessionsData[$sessionId])) {
                    $oldStudentCount = $sessionsData[$sessionId]['students_count'];
                    $newStudentCount = $oldStudentCount + count($students);
    
                    // Calculate the new price
                    $oldPrice = $sessionsData[$sessionId]['price'];
                    $pricePerStudent = $oldPrice / $oldStudentCount;
                    $newPrice = $pricePerStudent * $newStudentCount;
    
                    // Update the session price
                    $query = "UPDATE sessions SET price = $newPrice, is_group = $is_group WHERE id = $sessionId";
                    $conn->query($query);
    
                    // Recalculate the cost for each student in the session
                    $newPricePerStudent = $newPrice / $newStudentCount;
    
                    // Update the session cost for all students in session_students table
                    $query = "UPDATE session_students SET session_cost = $newPricePerStudent WHERE session_id = $sessionId";
                    $conn->query($query);
    
                    // Step 4: Calculate and update the teacher's session amount based on specializations
                    $materials = $sessionsData[$sessionId]['materials']; // Materials for the session
                    $totalSpecializations = count($materials); // Total number of specializations in the session
                    $sessionShare = $newPrice / 2; // 50% of the session price goes to the teachers
    
                    // Get teachers teaching in this session
                    $query = "SELECT teacher_id FROM session_teachers WHERE session_id = $sessionId";
                    $teachersResult = $conn->query($query);
    
                    while ($teacherRow = $teachersResult->fetch_assoc()) {
                        $teacherId = $teacherRow['teacher_id'];
    
                        // Get the teacher's specializations
                        $query = "SELECT ts.spec FROM teacher_specializations ts WHERE ts.teacher_id = $teacherId";
                        $teacherSpecsResult = $conn->query($query);
    
                        $teacherSpecializations = [];
                        while ($specRow = $teacherSpecsResult->fetch_assoc()) {
                            $teacherSpecializations[] = $specRow['spec'];
                        }
    
                        // Count how many specializations the teacher has in this session
                        $linkedSpecializations = 0;
                        foreach ($materials as $material) {
                            if (in_array($material, $teacherSpecializations)) {
                                $linkedSpecializations++;
                            }
                        }
    
                        // Calculate the teacher's share
                        $teacherShare = ($sessionShare * $linkedSpecializations) / $totalSpecializations;
    
                        // Update session_teachers table with the new session amount for the teacher
                        $query = "UPDATE session_teachers 
                                  SET session_amount = $teacherShare 
                                  WHERE session_id = $sessionId AND teacher_id = $teacherId";
                        $conn->query($query);
                    }
                }
            }
    
            // Commit transaction
            $conn->commit();
    
            return true;
        } catch (Exception $e) {
            $conn->rollback();
            return false;
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
                        ss.session_id,
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
        return $result && $result2 && $result3 /*&& $result4*/;
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


    public function updateStudent($id, $name, $phone, $selectedTeachers)
    {
        $conn = $this->connect();

        // Update student basic information
        $updateQuery = "UPDATE students SET name = '$name', phone = '$phone' WHERE id = '$id'";
        $updateResult = $conn->query($updateQuery);

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
        $conn = $this->connect(); // Assuming you have a method to establish a database connection

        // Fetch all sessions with their related session_teachers, session_students, and materials
        $query = "SELECT s.*, st.*, ss.*,s.hours AS session_hours,
                   ss.hours AS student_hours,  GROUP_CONCAT(DISTINCT stu.name) AS student_names, GROUP_CONCAT(DISTINCT t.name) AS teacher_names,
                   GROUP_CONCAT(DISTINCT spc.name) AS materials
                    FROM sessions s
                     LEFT JOIN session_teachers st ON s.id = st.session_id
                     LEFT JOIN session_students ss ON s.id = ss.session_id
                     LEFT JOIN students stu ON ss.student_id = stu.id
                     LEFT JOIN teacher t ON st.teacher_id = t.id
                     LEFT JOIN spc ON FIND_IN_SET(spc.id, s.material) > 0
            GROUP BY s.id
            ORDER BY s.id DESC
        ";
        $result = $conn->query($query);

        // Store the fetched data in an associative array
        $sessions = [];

        while ($row = $result->fetch_assoc()) {
            $sessionID = $row['id'];
            if (!isset($sessions[$sessionID])) {
                // Initialize session details if not already set
                $sessions[$sessionID] = [
                    'id' => $row['id'],
                    'session_name' => $row['session_name'],
                    'type' => $row['type'],
                    'materials' => $row['materials'],
                    'hours' => $row['session_hours'],
                    'price' => $row['price'],
                    'teachers' => [],
                    'students' => []
                ];
            }

            // Add session teacher details
            if ($row['teacher_id']) {
                $sessions[$sessionID]['teachers'][] = [
                    'teacher_id' => $row['teacher_id'],
                    'teacher_names' => $row['teacher_names'],
                    // Add more teacher details here if needed
                ];
            }

            // Add session student details
            if ($row['student_id']) {
                $sessions[$sessionID]['students'][] = [
                    'student_id' => $row['student_id'],
                    'student_names' => $row['student_names'],
                    // Add more student details here if needed
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
        //        var_dump($query);die();

        $result = $conn->query($query);

        if (!$result) {
            // Error occurred while inserting teacher basic information
            return false;
        }

        // Get the ID of the newly inserted teacher
        $teacherId = $conn->insert_id;

        // Insert teacher specializations
        foreach ($specs as $specId => $specData) {
            if (isset($specData['id']) && isset($specData['price'])) {
                $price = $specData['id'];
                $insertSpecQuery = "INSERT INTO teacher_specializations (teacher_id, spec, price) 
                                VALUES ('$teacherId', '$specId', '$price')";
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
    ) {
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
    ) {
        try {
            $conn = $this->connect();
            $query = "INSERT INTO schools (name, type) VALUES ('$name', $type)";
            $conn->query($query);
            return true;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    // Method to get all incomes
    public function getAllIncomes()
    {
        $conn = $this->connect();
        $query = "SELECT i.*, s.name AS student
                    FROM students s
                    JOIN income i ON i.student_id = s.id
                    ORDER BY date DESC";
        $result = $conn->query($query);

        $incomes = [];
        while ($row = $result->fetch_assoc()) {
            $incomes[] = $row;
        }

        return $incomes;
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
