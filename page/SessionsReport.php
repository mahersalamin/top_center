<?php //error_reporting(0);
//require 'header.php';
//require '../dbconnection.php';
//$db = new MyDB();
//$sessions = $db->fetchAllSessionsWithDetails();
//
//?>
<!--<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>-->
<!---->
<!--<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>-->
<!--<!-- DataTables Buttons JS -->-->
<!--<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>-->
<!--<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>-->
<!--<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>-->
<!--<!-- JSZip for Excel export -->-->
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>-->
<!---->
<!--<style>-->
<!--    table.dataTable thead th, table.dataTable thead td, table.dataTable tfoot th, table.dataTable tfoot td {-->
<!--        text-align: center;-->
<!--    }-->
<!--</style>-->
<!--<!-- For sessions report-->-->
<!--<div class="container text-center">-->
<!--    <div class="col-md-12 mb-2 font-weight-bold">-->
<!---->
<!---->
<!--        <h1>تقرير الدورات</h1>-->
<!---->
<!--        <table class="table table-striped text-right" id="reports">-->
<!--            <thead>-->
<!--            <tr>-->
<!--                <th>الرقم</th>-->
<!--                <th>الاسم</th>-->
<!--                <th>النوع</th>-->
<!--                <th>المواد</th>-->
<!--                <th>عدد الساعات</th>-->
<!--                <th>السعر</th>-->
<!--                <th>المعلمون</th>-->
<!--                <th>الطلاب</th>-->
<!--            </tr>-->
<!--            </thead>-->
<!--            <tbody>-->
<!--            --><?php
//
//            foreach ($sessions as $session) {
//                echo "<tr>";
//                echo "<td>{$session['id']}</td>";
//                echo "<td>{$session['session_name']}</td>";
//                echo "<td>{$session['type']}</td>";
//                echo "<td>{$session['materials']}</td>";
//                echo "<td>{$session['hours']}</td>";
//                echo "<td>{$session['price']}</td>";
//
//                // Display teacher details
//                echo "<td>";
//                foreach ($session['teachers'] as $teacher) {
//                    echo "{$teacher['teacher_names']}"; // Add more teacher details here if needed
//                }
//                echo "</td>";
//
//                // Display student details
//                echo "<td>";
//                foreach ($session['students'] as $student) {
//                    echo "{$student['student_names']}<br>"; // Add more student details here if needed
//                }
//                echo "</td>";
//
//                echo "</tr>";
//            }
//            ?>
<!--            </tbody>-->
<!--        </table>-->
<!---->
<!---->
<!--    </div>-->
<!--</div>-->
<!---->
<!---->
<!---->
<!---->
<!---->
<!---->
<!--<script src="https://code.jquery.com/jquery-3.3.1.js"></script>-->
<!--<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>-->
<!--<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>-->
<!--<script src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>-->
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>-->
<!---->
<!--<script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>-->
<!--<script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.print.min.js"></script>-->
<!--<script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>-->
<!--<script src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap4.min.js"></script>-->
<!---->
<!---->
<!---->
<!---->
<!--<script>-->
<!---->
<!---->
<!--    $(document).ready(function () {-->
<!--        let table = $('#report_table').DataTable({-->
<!--            "paging": true,          // Enable pagination-->
<!--            "lengthChange": true,    // Show length change options-->
<!--            "searching": true,       // Enable search functionality-->
<!--            "ordering": true,        // Enable column sorting-->
<!--            "info": true,            // Show table information-->
<!--            "autoWidth": false,      // Disable automatic column width adjustment-->
<!--            "language": {-->
<!--                "paginate": {-->
<!--                    "previous": "السابق",-->
<!--                    "next": "التالي",-->
<!--                    "first": "الأول",-->
<!--                    "last": "الأخير"-->
<!--                },-->
<!--                "lengthMenu": "عرض _MENU_ سجلات",-->
<!--                "info": "عرض _START_ إلى _END_ من _TOTAL_ سجلات",-->
<!--                "infoEmpty": "لا توجد سجلات متاحة",-->
<!--                "infoFiltered": "(تمت تصفيته من _MAX_ إجمالي السجلات)",-->
<!--                "search": "بحث:",-->
<!--                "zeroRecords": "لم يتم العثور على تطابقات"-->
<!--            },-->
<!--            "lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "الكل"] ],-->
<!--            "order": [[0, 'desc']],-->
<!--            serverSide: false,-->
<!--            dom: 'Bfrtip',-->
<!--            buttons: [-->
<!--                'excel', 'print', {-->
<!--                    text: 'PDF',-->
<!--                    action: function (e, dt, button, config) {-->
<!--                        // Get the table headers, skipping the last two-->
<!--                        let headers = [];-->
<!--                        $('#report_table thead th').each(function(index) {-->
<!--                            if (index < 9) { // Assuming there are 7 columns, change this if different-->
<!--                                headers.push($(this).text());-->
<!--                            }-->
<!--                        });-->
<!---->
<!--                        // Get the table data, skipping the last two columns-->
<!--                        let data = [];-->
<!--                        dt.rows({ search: 'applied' }).every(function() {-->
<!--                            let row = [];-->
<!--                            $(this.node()).find('td').each(function(index) {-->
<!--                                if (index < 9) { // Assuming there are 7 columns, change this if different-->
<!--                                    row.push($(this).text());-->
<!--                                }-->
<!--                            });-->
<!--                            data.push(row);-->
<!--                        });-->
<!---->
<!--                        // Create a form and submit it-->
<!--                        let form = $('<form>', {-->
<!--                            action: '../mpdf-generator.php',-->
<!--                            method: 'POST'-->
<!--                        }).append($('<input>', {-->
<!--                            type: 'hidden',-->
<!--                            name: 'headers',-->
<!--                            value: JSON.stringify(headers)-->
<!--                        })).append($('<input>', {-->
<!--                            type: 'hidden',-->
<!--                            name: 'tableData',-->
<!--                            value: JSON.stringify(data)-->
<!--                        })).append($('<input>', {-->
<!--                            type: 'hidden',-->
<!--                            name: 'reportType',-->
<!--                            value: 'public_report'-->
<!--                        }));-->
<!---->
<!--                        form.appendTo('body').submit();-->
<!--                    }-->
<!--                }-->
<!--            ],-->
<!---->
<!--            initComplete: function () {-->
<!--                this.api().columns().every(function () {-->
<!--                    let column = this;-->
<!--                    let search = $(`<input class="form-control form-control-sm" type="text" placeholder="بحث">`)-->
<!--                        .appendTo($(column.footer()).empty())-->
<!--                        .on('change input', function () {-->
<!--                            let val = $(this).val()-->
<!---->
<!--                            column-->
<!--                                .search(val ? val : '', true, false)-->
<!--                                .draw();-->
<!--                        });-->
<!---->
<!--                });-->
<!--            }-->
<!--        });-->
<!---->
<!--        $('#lengthMenu').on('change', function() {-->
<!--            let length = $(this).val();-->
<!--            table.page.len(length).draw();-->
<!--        });-->
<!--    });-->
<!--</script>-->
<?php
error_reporting(0);
require 'header.php';
require '../dbconnection.php';
$db = new MyDB();
$sessions = $db->fetchAllSessionsWithDetails();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Session Report</title>
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
    </style>
</head>
<body>
<!-- For sessions report-->
<div class="container text-center">
    <div class="col-md-12 mb-2 font-weight-bold">
        <div class="row">
            <div class="col-md-6">
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
        <h1>تقرير الدورات</h1>
        <table class="table table-striped text-right" id="report_table">
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
