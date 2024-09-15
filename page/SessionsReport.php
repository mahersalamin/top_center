<?php
error_reporting(0);
require 'header.php';
require '../dbconnection.php';
$db = new MyDB();
$sessions = $db->getSessionsDataDetailed();
?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تقرير الدورات</title>
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <!-- DataTables Buttons CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css">
    <!-- Bootstrap CSS (Optional) -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        table.dataTable thead th, table.dataTable thead td, table.dataTable tfoot th, table.dataTable tfoot td {
            text-align: center;
        }
        #sessionForm {
            display: none;
        }

    </style>
</head>
<body>
<!-- For sessions report-->
<div class="container text-right">
    <div id="sessionForm" style="display:none;">
        <form method="post" action="saveSession.php">
            <div class="row">
                <!-- Session Name -->
                <div class="col-md-4 form-group">
                    <label for="sessionName">Session Name:</label>
                    <input type="text" id="sessionName" name="session_name" class="form-control">
                </div>

                <!-- Session Type -->
                <div class="col-md-4 form-group">
                    <label for="sessionType">Type:</label>
                    <input type="text" id="sessionType" name="session_type" class="form-control">
                </div>

                <!-- Number of Hours -->
                <div class="col-md-4 form-group">
                    <label for="sessionHours">Hours:</label>
                    <input type="number" id="sessionHours" name="session_hours" class="form-control">
                </div>
            </div>

            <div class="row">
                <!-- Teachers -->
                <div class="col-md-4 form-group">
                    <label>Teachers:</label>
                    <?php $teachers = $db->getAllTeachers(); ?>
                    <?php foreach ($teachers as $teacher) { ?>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="teacher_<?php echo $teacher['id']; ?>" name="teachers[]" value="<?php echo $teacher['id']; ?>">
                            <label class="form-check-label" for="teacher_<?php echo $teacher['id']; ?>">
                                <?php echo $teacher['name']; ?>
                            </label>
                        </div>
                    <?php } ?>
                </div>

                <!-- Students -->
                <div class="col-md-4 form-group">
                    <label>Students:</label>
                    <?php $students = $db->allStudents(); ?>
                    <?php foreach ($students as $student) { ?>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="student_<?php echo $student['id']; ?>" name="students[]" value="<?php echo $student['id']; ?>">
                            <label class="form-check-label" for="student_<?php echo $student['id']; ?>">
                                <?php echo $student['name']; ?>
                            </label>
                        </div>
                    <?php } ?>
                </div>

                <!-- Specializations -->
                <div class="col-md-4 form-group">
                    <label>Specializations:</label>
                    <?php $materials = $db->getSpecializations(); ?>
                    <?php foreach ($materials as $material) { ?>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="material_<?php echo $material['id']; ?>" name="materials[]" value="<?php echo $material['id']; ?>">
                            <label class="form-check-label" for="material_<?php echo $material['id']; ?>">
                                <?php echo $material['name']; ?>
                            </label>
                        </div>
                    <?php } ?>
                </div>
            </div>


            <button type="submit" class="btn btn-success">Save</button>
        </form>
    </div>

    <div class="col-md-12 mb-2 font-weight-bold">
        <h1>تقرير الدورات</h1>
        <div class="row">
            <div class="col-md-2">
                <label for="lengthMenu">عرض</label>
                <select id="lengthMenu" class="form-control form-control-sm" style="width: auto; display: inline-block;">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="-1">الكل</option>
                </select>
                <label>سجلات</label>
            </div>
        </div>
        <table class="table table-bordered text-right" id="report_table">
            <thead>
            <tr>
                <th>الرقم</th>
                <th>الاسم</th>
                <th>النوع</th>
                <th>المواد</th>
                <th>عدد الساعات</th>
                <th>السعر</th>
                <th>المعلمون</th>
                <th>الطلاب</th>
                <th>إجراء</th>

            </tr>
            </thead>
            <tbody>
            <?php
            foreach ($sessions as $session) {
                echo "<tr>";
                echo "<td>{$session['id']}</td>";
                echo "<td>{$session['session_name']}</td>";
                echo "<td>{$session['type']}</td>";
                echo "<td>{$session['materials']}</td>";
                echo "<td>{$session['hours']}</td>";
                echo "<td>{$session['price']}</td>";

                // Display teacher details
                echo "<td>";
                foreach ($session['teachers'] as $teacher) {
                    echo "{$teacher['teacher_names']}"; // Add more teacher details here if needed
                }
                echo "</td>";

                // Display student details
                echo "<td>";
                foreach ($session['students'] as $student) {
                    echo "{$student['student_names']}<br>"; // Add more student details here if needed
                }
                echo "</td>";
                echo "<td><button class='btn btn-primary' onclick='showForm(".$session['id'].")'>تحرير</button></td>";
                echo "</tr>";
            }
            ?>
            </tbody>
        </table>
    </div>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<!-- DataTables Buttons JS -->
<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>
<!-- JSZip for Excel export -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

<script>
    // Array to hold session data
    const sessions = <?php echo json_encode($sessions); ?>;

    function showForm(sessionId) {
        // Find the session by ID
        const session = sessions[sessionId];

        // Populate the form fields
        document.getElementById('sessionName').value = session.session_name;
        document.getElementById('sessionType').value = session.type;
        document.getElementById('sessionHours').value = session.hours;

        // Uncheck all teachers and students first
        document.querySelectorAll("input[name='teachers[]']").forEach(input => input.checked = false);
        document.querySelectorAll("input[name='students[]']").forEach(input => input.checked = false);
        document.querySelectorAll("input[name='materials[]']").forEach(input => input.checked = false);

        // Check teachers
        session.teachers.forEach(function (teacher) {
            const teacherId = teacher.teacher_id;
            const checkbox = document.getElementById('teacher_' + teacherId);
            if (checkbox) {
                checkbox.checked = true;
            }
        });

        // Check students
        session.students.forEach(function (student) {
            const studentId = student.student_id;
            const checkbox = document.getElementById('student_' + studentId);
            if (checkbox) {
                checkbox.checked = true;
            }
        });

        // Check materials (specializations)
        const materialList = session.materials.split(',');
        materialList.forEach(function (material) {
            document.querySelectorAll("input[name='materials[]']").forEach(function (checkbox) {
                if (checkbox.getAttribute('data-material-name') === material) {
                    checkbox.checked = true;
                }
            });
        });

        // Show the form
        document.getElementById('sessionForm').style.display = 'block';
    }
</script>



<script>
    function editSession(sessionId) {
        // Get the row data
        var row = document.querySelector(`tr td:first-child:contains('${sessionId}')`).parentNode;
        var sessionName = row.cells[1].innerText;
        var sessionType = row.cells[2].innerText;
        var sessionMaterials = row.cells[3].innerText;
        var sessionHours = row.cells[4].innerText;
        var sessionPrice = row.cells[5].innerText;

        // Fill the form with data
        document.getElementById('sessionName').value = sessionName;
        document.getElementById('sessionType').value = sessionType;
        document.getElementById('sessionMaterials').value = sessionMaterials;
        document.getElementById('sessionHours').value = sessionHours;
        document.getElementById('sessionPrice').value = sessionPrice;

        // Show the form
        document.getElementById('editForm').style.display = 'block';
    }

    function hideForm() {
        document.getElementById('editForm').style.display = 'none';
    }

</script>
<script>
    $(document).ready(function() {
        let table = $('#report_table').DataTable({
            "paging": true,          // Enable pagination
            "lengthChange": true,    // Show length change options
            "searching": true,       // Enable search functionality
            "ordering": true,        // Enable column sorting
            "info": true,            // Show table information
            "autoWidth": false,      // Disable automatic column width adjustment
            "language": {
                "paginate": {
                    "previous": "السابق",
                    "next": "التالي",
                    "first": "الأول",
                    "last": "الأخير"
                },

                "info": "عرض _START_ إلى _END_ من _TOTAL_ سجلات",
                "infoEmpty": "لا توجد سجلات متاحة",
                "infoFiltered": "(تمت تصفيته من _MAX_ إجمالي السجلات)",
                "search": "بحث:",
                "zeroRecords": "لم يتم العثور على تطابقات"
            },

            "order": [[0, 'desc']],
            dom: 'Bfrtip',
            buttons: [
                'excel', 'print', {
                    text: 'PDF',
                    action: function (e, dt, button, config) {
                        let headers = [];
                        $('#report_table thead th').each(function(index) {
                            if (index < 9) { // Adjust if you have more or fewer columns
                                headers.push($(this).text());
                            }
                        });

                        let data = [];
                        dt.rows({ search: 'applied' }).every(function() {
                            let row = [];
                            $(this.node()).find('td').each(function(index) {
                                if (index < 9) { // Adjust if you have more or fewer columns
                                    row.push($(this).text());
                                }
                            });
                            data.push(row);
                        });

                        let form = $('<form>', {
                            action: '../mpdf-generator.php',
                            method: 'POST'
                        }).append($('<input>', {
                            type: 'hidden',
                            name: 'headers',
                            value: JSON.stringify(headers)
                        })).append($('<input>', {
                            type: 'hidden',
                            name: 'tableData',
                            value: JSON.stringify(data)
                        })).append($('<input>', {
                            type: 'hidden',
                            name: 'reportType',
                            value: 'sessions_report'
                        }));

                        form.appendTo('body').submit();
                    }
                }
            ]
        });
        $('#lengthMenu').on('change', function() {
            let length = $(this).val();
            table.page.len(length).draw();
        });
    });

</script>
</body>
</html>
