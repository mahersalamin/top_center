<?php
error_reporting(0);
require 'header.php';
require '../dbconnection.php';
$db = new MyDB();
$sessions = $db->getSessionsDataDetailed();
//echo json_encode($sessions);die();
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
        table.dataTable thead th,
        table.dataTable thead td,
        table.dataTable tfoot th,
        table.dataTable tfoot td {
            text-align: center;
        }

        /*#sessionForm {*/
        /*    display: none;*/
        /*}*/
    </style>
</head>

<body>
    <!-- For sessions report-->
    <div class="container text-right">
        <div id="sessionFormDiv">
            <form method="post" action="saveSession.php" id="sessionForm" style="display:none;">
                <input type="hidden" name="session_id" id="session_id">

                <div class="row">
                    <div class="col-md-3 form-group">
                        <label for="sessionName">اسم الدورة:</label>
                        <input type="text" id="sessionName" name="session_name" class="form-control">
                    </div>

                    <div class="col-md-3 form-group">
                        <label for="sessionType">نوع الدورة:</label>
                        <input type="text" id="sessionType" name="session_type" class="form-control">
                    </div>

                    <div class="col-md-3 form-group">
                        <label for="sessionHours">عدد الساعات/اللقاءات:</label>
                        <input type="number" id="sessionHours" name="session_hours" class="form-control">
                    </div>
                    <div class="col-md-3 form-group">
                        <label for="sessionPrice">السعر:</label>
                        <input type="number" id="sessionPrice" name="session_price" class="form-control">
                    </div>
                </div>

                <div class="row">
                    <!-- Teachers -->
                    <div class="col-md-4 form-group">
                        <label>المعلمون:</label>
                        <div style="max-height: 200px; overflow-y: auto; border: 1px solid #ccc; padding: 8px;">
                            <?php $teachers = $db->getAllTeachers(); ?>
                            <?php foreach ($teachers as $teacher) { ?>
                                <div class="form-check d-flex align-items-center mb-2">
                                    <input
                                            type="checkbox"
                                            class="form-check-input mr-2"
                                            id="teacher_<?php echo $teacher['id']; ?>"
                                            name="teachers[<?php echo $teacher['id']; ?>][id]"
                                            value="<?php echo $teacher['id']; ?>"
                                            onchange="togglePercentageInput(this)"
                                    >

                                    <label class="form-check-label mr-2" for="teacher_<?php echo $teacher['id']; ?>">
                                        <?php echo $teacher['name']; ?>
                                    </label>

                                    <input
                                            type="number"
                                            class="form-control ml-2"
                                            name="teachers[<?php echo $teacher['id']; ?>][percentage]"
                                            placeholder="النسبة %"
                                            min="0"
                                            max="100"
                                            step="1"
                                            style="width: 80px;"
                                            disabled
                                    >
                                </div>
                            <?php } ?>

                        </div>
                    </div>



                    <!-- Students -->
                    <div class="col-md-4 form-group">
                        <label>الطلاب:</label>
                        <div style="max-height: 200px; overflow-y: auto; border: 1px solid #ccc; padding: 8px;">
                            <?php foreach ($db->allStudents() as $student) { ?>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="student_<?php echo $student['id']; ?>" name="students[]" value="<?php echo $student['id']; ?>">
                                    <label class="form-check-label mr-4" for="student_<?php echo $student['id']; ?>">
                                        <?php echo $student['name']; ?>
                                    </label>
                                </div>
                            <?php } ?>
                        </div>
                    </div>

                    <!-- Materials -->
                    <div class="col-md-4 form-group">
                        <label>المواد:</label>
                        <div style="max-height: 200px; overflow-y: auto; border: 1px solid #ccc; padding: 8px;">
                            <?php foreach ($db->getSpecializations() as $material) { ?>
                                <div class="form-check">
                                    <input type="checkbox"
                                           class="form-check-input"
                                           id="material_<?php echo $material['id']; ?>"
                                           name="materials[]"
                                           value="<?php echo $material['id']; ?>"
                                           data-material-name="<?php echo $material['name']; ?>">
                                    <label class="form-check-label mr-4" for="material_<?php echo $material['id']; ?>">
                                        <?php echo $material['name']; ?>
                                    </label>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-success">حفظ</button>
            </form>
        </div>
        <?php
        if (isset($_GET['status']) && isset($_GET['msg'])) {
            $status = $_GET['status'];
            $msg = urldecode($_GET['msg']);
            $alertClass = $status === 'success' ? 'alert-success' : 'alert-danger';
            echo "<div class='alert $alertClass' role='alert' style='margin: 20px;'>$msg</div>";
        }
        ?>

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
                        echo "<td><ul>";
                        foreach ($session['materials'] as $material) {
                            echo "<li>" . htmlspecialchars($material) . "</li>";
                        }
                        echo "</ul></td>";
                        echo "<td>{$session['hours']}</td>";
                        echo "<td>{$session['price']}</td>";

                        // Display teacher details
                        echo "<td><ul>";
                        foreach ($session['teachers'] as $teacher) {
                            echo "<li>{$teacher['teacher_names']}</li>"; // Add more teacher details here if needed
                        }
                        echo "</ul></td>";

                        // Display student details
                        echo "<td><ul>";
                        foreach ($session['students'] as $student) {
                            echo "<li>{$student['student_names']}</li>"; // Add more student details here if needed
                        }
                        echo "</ul></td>";
                        echo "<td><button class='btn btn-primary' onclick='showForm(" . $session['id'] . ")'>تحرير</button></td>";
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
        function togglePercentageInput(checkbox) {
            const input = checkbox.closest('.form-check').querySelector('input[type="number"]');
            input.disabled = !checkbox.checked;
            if (!checkbox.checked) input.value = '';
        }
        const sessions = <?php echo json_encode($sessions); ?>;
        function showForm(sessionId) {
            const session = sessions[sessionId];
            if (!session) return;

            document.getElementById('session_id').value = sessionId;
            document.getElementById('sessionName').value = session.session_name;
            document.getElementById('sessionType').value = session.type;
            document.getElementById('sessionHours').value = session.hours;
            document.getElementById('sessionPrice').value = session.price;

            // Uncheck all
            document.querySelectorAll("input[name='teachers[]'], input[name='students[]'], input[name='materials[]']").forEach(input => {
                input.checked = false;
            });

            // Teachers
            session.teachers.forEach(t => {
                const cb = document.getElementById('teacher_' + t.teacher_id);
                if (cb) cb.checked = true;
            });

            // Students
            session.students.forEach(s => {
                const cb = document.getElementById('student_' + s.student_id);
                if (cb) cb.checked = true;
            });

            // Materials (stored as comma-separated names)
            if (Array.isArray(session.materials)) {
                session.materials.forEach(name => {
                    document.querySelectorAll("input[name='materials[]']").forEach(cb => {
                        if (cb.dataset.materialName.trim() === name.trim()) {
                            cb.checked = true;
                        }
                    });
                });
            }


            document.getElementById('sessionForm').style.display = 'block';
        }
    </script>


    <script>
        $(document).ready(function() {
            let table = $('#report_table').DataTable({
                "paging": true, // Enable pagination
                "lengthChange": true, // Show length change options
                "searching": true, // Enable search functionality
                "ordering": true, // Enable column sorting
                "info": true, // Show table information
                "autoWidth": false, // Disable automatic column width adjustment
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
                "order": [
                    [0, 'desc']
                ],
                dom: 'Bfrtip',
                buttons: [
                    'excel', 'print', {
                        text: 'PDF',
                        action: function(e, dt, button, config) {
                            let headers = [];
                            let lastColumnIndex = $('#report_table thead th').length - 1; // Get last column index

                            // Collect table headers, excluding the last column
                            $('#report_table thead th').each(function(index) {
                                if (index < lastColumnIndex) { // Exclude the last column
                                    headers.push($(this).text());
                                }
                            });

                            let data = [];
                            dt.rows({
                                search: 'applied'
                            }).every(function() {
                                let row = [];
                                $(this.node()).find('td').each(function(index) {
                                    if (index < lastColumnIndex) { // Exclude the last column
                                        row.push($(this).text());
                                    }
                                });
                                data.push(row);
                            });

                            // Create and submit the form for PDF generation
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

            // Change page length
            $('#lengthMenu').on('change', function() {
                let length = $(this).val();
                table.page.len(length).draw();
            });
        });
    </script>

</body>

</html>