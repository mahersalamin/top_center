<?php require 'header.php'; // Include the header
//require '../MyDB.php'; // Include the database connection class
$db = new MyDB();

$students = $db->allStudents();
//$studentSessions = $db->getEnrolledSessionsForStudent(1);
$approvedAttendances = $db->getApprovedAttendances();
// Fetch summary statistics
$incomeStats = $db->getIncomeStatistics();
$outcomeStats = $db->getOutcomeStatistics();
$teachersStats = $db->getTeachersOutcomeStatistics();
$othersStats = $db->getOthersOutcomeStatistics();
$totalBalance = $incomeStats['total_amount'] - $outcomeStats['total_amount'];


?>

<div class="container mt-5 text-right">
    <h2 class="text-center mb-3">المالية</h2>
    <!-- Nav tabs -->
    <ul class="nav nav-tabs nav-pills nav-fill justify-content-center mt-4" id="financeTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link active" id="income-tab" data-toggle="tab" href="#income" role="tab"
               aria-controls="income" aria-selected="true">الوارد</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="outcome-tab" data-toggle="tab" href="#outcome" role="tab" aria-controls="outcome"
               aria-selected="false">المصاريف</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="summary-tab" data-toggle="tab" href="#summary" role="tab" aria-controls="summary"
               aria-selected="false">الملخص</a>
        </li>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content mt-4">
        <!-- Income Tab -->
        <div class="tab-pane fade show active" id="income" role="tabpanel" aria-labelledby="income-tab">
            <div class="row">
                <div class="col-md-4">
                    <h3>وارد جديد</h3>
                    <form method="POST" action="../finance_endpoint.php">

                        <div class="form-group">
                            <label for="income_date">التاريخ</label>
                            <input type="date" class="form-control" id="income_date" name="income_date" required>
                        </div>
                        <div class="form-group">
                            <label for="income_cashier">المستلم</label>
                            <select type="text" class="form-control" id="income_cashier" name="income_cashier" required>
                                <option value="" selected disabled>اختر المستلم</option>
                                <option value="السكرتيرة">السكرتيرة</option>
                                <option value="الإدارة">الإدارة</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="income_payer">الدافع</label>
                            <input type="text" class="form-control" id="income_payer" name="income_payer" required>
                        </div>
                        <div class="form-group">
                            <label for="income_student">الطالب</label>
                            <select class="form-control" id="income_student" name="income_student" required>
                                <option selected disabled value="">اختر طالب</option>

                                <?php foreach ($students as $student): ?>
                                    <option value="<?php echo $student['id']; ?>"><?php echo $student['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="income_session">الدورة</label>
                            <select class="form-control" id="income_session" name="income_session" required>
                                <!-- Options will be populated dynamically via AJAX -->
                                <option disabled selected>اختر طالب لعرض الدورات</option>
                            </select>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="session_cost">المبلغ المطلوب</label>
                                    <input class="form-control" id="session_cost" type="number" readonly>

                                </div>
                                <div class="col-md-6">
                                    <label for="total_payments">المبلغ المدفوع</label>
                                    <input class="form-control" id="total_payments" type="number" readonly>

                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="income_amount">القيمة</label>
                            <input type="number" min="0" class="form-control" id="income_amount" name="income_amount"
                                   required>
                        </div>
                        <div class="form-group">
                            <label for="income_notes">ملاحظات</label>
                            <textarea class="form-control" id="income_notes" name="income_notes"></textarea>
                        </div>
                        <button type="submit" name="income_submit" class="btn btn-success mt-3">حفظ</button>
                    </form>
                </div>
                <div class="col-md-8">
                    <h3>تقرير الواردات</h3>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="lengthMenuIncome">عرض</label>
                            <select id="lengthMenuIncome" class="form-control form-control-sm" style="width: auto; display: inline-block;">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="-1">الكل</option>
                            </select>
                            <label>سجلات</label>
                        </div>
                    </div>
                    <table class="table table-bordered" id="dt-filter-search-income" >
                        <thead>
                        <tr>
                            <th>الرقم</th>
                            <th>التاريخ</th>
                            <th>المستلم</th>
                            <th>الدافع</th>
                            <th>الطالب</th>
                            <th>القيمة</th>
                            <th>ملاحظات</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $incomes = $db->getAllIncomes();
                        foreach ($incomes as $income) {
                            echo
                            "<tr>";
                            echo "<td>{$income['id']}</td>";
                            echo "<td>{$income['date']}</td>";
                            echo "<td>{$income['cashier']}</td>";
                            echo "<td>{$income['payer']}</td>";
                            echo "<td>{$income['student']}</td>";

                            echo "<td>{$income['amount']}</td>";
                            echo "<td>{$income['notes']}</td>";
                            echo "</tr>";
                        }
                        ?>
                        </tbody>
                        <tfoot>
                        <tr>
                            <th>الرقم</th>
                            <th>التاريخ</th>
                            <th>المستلم</th>
                            <th>الدافع</th>
                            <th>الطالب</th>
                            <th>القيمة</th>
                            <th>ملاحظات</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Outcome Tab -->
        <div class="tab-pane fade" id="outcome" role="tabpanel" aria-labelledby="outcome-tab">
            <div class="row">
                <div class="col-md-4">
                    <h3>مصاريف جديدة</h3>
                    <form method="POST" action="../finance_endpoint.php">
                        <div class="form-group">
                            <label for="outcome_date">التاريخ</label>
                            <input type="date" class="form-control" id="outcome_date" name="outcome_date" required>
                        </div>
                        <div class="form-group">
                            <label for="outcome_type">النوع</label>
                            <select type="text" class="form-control" id="outcome_type" name="outcome_type" required>
                                <option value="" selected disabled>اختر النوع</option>
                                <option value="مستلزمات مكتب">مستلزمات مكتب</option>
                                <option value="أجور">أجور</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="outcome_receiver">المستلم</label>
                            <select class="form-control" id="outcome_receiver" name="outcome_receiver" required>
                                <option value="" selected disabled>اختر المستلم</option>
                                <option value="0">مستفيد خارجي</option>
                                <?php
                                $teachers = $db->getAllTeachers();
                                foreach ($teachers as $teacher) {
                                    echo "<option value='{$teacher['id']}'>{$teacher['name']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div id="teacher-sessions-div" class="form-group">
                            <label for="teacher-sessions">الدورة</label>
                            <select class="form-control" id="teacher-sessions" name="teacher_sessions" required>
                                <!-- Options will be populated dynamically via AJAX -->
                                <option disabled selected>اختر معلم لعرض الدورات</option>
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="teacher_session_cost">المبلغ المطلوب</label>
                                <input class="form-control" id="teacher_session_cost" type="number" readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="teacher_total_payments">المبلغ المدفوع</label>
                                <input class="form-control" id="teacher_total_payments" type="number" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="teacher_meetings">عدد اللقاءات الكلي</label>
                                <input class="form-control" id="teacher_meetings" type="number" readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="teacher_finished_meetings">عدد اللقاءات</label>
                                <input class="form-control" id="teacher_finished_meetings" type="number" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="outcome_amount">القيمة</label>
                            <input type="number" min="0" class="form-control" id="outcome_amount" name="outcome_amount" required>
                        </div>
                        <div class="form-group">
                            <label for="outcome_notes">ملاحظات</label>
                            <textarea class="form-control" id="outcome_notes" name="outcome_notes"></textarea>
                        </div>

                        <!-- Hidden inputs for teacher_id and session_id -->
                        <input type="hidden" id="teacher_id" name="teacher_id">
                        <input type="hidden" id="session_id" name="session_id">

                        <button type="submit" name="outcome_submit" class="btn btn-success mt-3">حفظ</button>
                    </form>

                </div>
                <div class="col-md-8">
                    <h3>تقرير المصاريف</h3>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="lengthMenuOutcome">عرض</label>
                            <select id="lengthMenuOutcome" class="form-control form-control-sm" style="width: auto; display: inline-block;">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="-1">الكل</option>
                            </select>
                            <label>سجلات</label>
                        </div>
                    </div>
                    <table class="table table-bordered" id="dt-filter-search-outcome">
                        <thead>
                        <tr>
                            <th>الرقم</th>
                            <th>التاريخ</th>
                            <th>النوع</th>
                            <th>المستلم</th>
                            <th>القيمة</th>
                            <th>ملاحظات</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $outcomes = $db->getAllOutcomes();
                        foreach ($outcomes as $outcome) {
                            echo "<tr>";
                            echo "<td>{$outcome['id']}</td>";
                            echo "<td>{$outcome['date']}</td>";
                            echo "<td>{$outcome['type']}</td>";
                            if ($outcome['receiver'] == 0) {
                                echo "<td>مستفيد خارجي</td>";
                            } else {
                                echo "<td>{$outcome['name']}</td>";
                            }

                            echo "<td>{$outcome['amount']}</td>";
                            echo "<td>{$outcome['notes']}</td>";
                            echo "</tr>";
                        }
                        ?>
                        </tbody>
                        <tfoot>
                        <tr>
                            <th>الرقم</th>
                            <th>التاريخ</th>
                            <th>النوع</th>
                            <th>المستلم</th>
                            <th>القيمة</th>
                            <th>ملاحظات</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Summary Tab -->
        <button id="generatePdf" class="btn btn-primary mt-4">PDF</button>

        <div class="tab-pane fade" id="summary" role="tabpanel" aria-labelledby="summary-tab" style="direction: rtl; text-align: right; font-family: 'Open Sans', sans-serif;">

            <div class="row">
                <div class="col-md-12" style="direction: rtl; text-align: right;">
                    <h3>الملخص</h3>
                    <div class="card" style="border: 1px solid #ddd; border-radius: 4px; padding: 20px;">
                        <div class="card-body">
                            <h5 class="card-title">الواردات</h5>
                            <p>عدد الدفعات المستلمة: <?php echo $incomeStats['count']; ?></p>
                            <p>المبلغ الكلي: <?php echo $incomeStats['total_amount']; ?></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="card col-md-4 mt-4" style="border: 1px solid #ddd; border-radius: 4px; padding: 20px;">
                            <div class="card-body">
                                <h5 class="card-title">كل المصاريف</h5>
                                <p>عدد الدفعات الصادرة: <?php echo $outcomeStats['count']; ?></p>
                                <p>المبلغ الكلي: <?php echo $outcomeStats['total_amount']; ?></p>
                            </div>
                        </div>
                        <div class="card col-md-4 mt-4" style="border: 1px solid #ddd; border-radius: 4px; padding: 20px;">
                            <div class="card-body">
                                <h5 class="card-title">مصاريف المعلمين</h5>
                                <p>عدد الدفعات الصادرة للمعلمين: <?php echo $teachersStats['count']; ?></p>
                                <p>المبلغ الكلي: <?php echo $teachersStats['total_amount']; ?></p>
                            </div>
                        </div>
                        <div class="card col-md-4 mt-4" style="border: 1px solid #ddd; border-radius: 4px; padding: 20px;">
                            <div class="card-body">
                                <h5 class="card-title">مصاريف خارجية</h5>
                                <p>عدد الدفعات الصادرة الى جهات خارجية: <?php echo $othersStats['count']; ?></p>
                                <p>المبلغ الكلي: <?php echo $othersStats['total_amount']; ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="card mt-4" style="border: 1px solid #ddd; border-radius: 4px; padding: 20px;">
                        <div class="card-body">
                            <h5 class="card-title">الرصيد الكلي</h5>
                            <p><?php echo $totalBalance; ?></p>
                        </div>
                    </div>
                    <!-- Button to generate PDF -->
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<!-- DataTables Buttons JS -->
<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>
<!-- JSZip for Excel export -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<!-- pdfmake for PDF export -->
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.6/pdfmake.min.js"></script>-->
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.6/vfs_fonts.js"></script>-->

<script>
    $(document).ready(function () {
        var sessionData = {}; // Define sessionData in the outer scope
        $('#teacher-sessions-div').css("visibility", "hidden");
        $('#income_student').change(function () {
            var studentId = $(this).val();
            $.ajax({
                url: '../get_enrolled_sessions.php', // Endpoint URL
                type: 'POST',
                data: {student_id: studentId},
                dataType: 'json',
                success: function (response) {
                    $('#income_session').empty();
                    sessionData = {}; // Re-initialize sessionData for the new student

                    $.each(response, function (index, session) {
                        $('#income_session').append('<option value="' + session.id + '">' + session.session_name + '</option>');
                        sessionData[session.id] = {
                            price: session.session_cost,
                            total_payments: session.total_payments
                        };
                    });

                    // Unbind the previous change event handler to avoid stacking
                    $('#income_session').off('change').change(function () {
                        var selectedSessionId = $(this).val();
                        if (selectedSessionId) {
                            var selectedSession = sessionData[selectedSessionId];
                            $('#session_cost').val(selectedSession.price);
                            $('#total_payments').val(selectedSession.total_payments);
                        } else {
                            $('#session_cost').val('');
                            $('#total_payments').val('');
                        }
                    });

                    // Trigger change to update fields for the initially selected option
                    $('#income_session').trigger('change');
                },
                error: function (xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        });

        $('#outcome_type').change(function () {
            let outComeType = $(this).val()
            if (outComeType !== 'أجور') {
                $('#teacher-sessions-div').css("visibility", "hidden");
                $('#teacher-sessions').prop("disabled", true);
            } else {
                $('#teacher-sessions-div').css("visibility", "visible");
                $('#teacher-sessions').prop("disabled", false);
            }

        });


        $('#outcome_receiver').change(function () {
            let teacherID = $(this).val();

            $.ajax({
                url: '../get_enrolled_sessions.php', // Endpoint URL
                type: 'POST',
                data: {teacher_id: teacherID},
                dataType: 'json',
                success: function (response) {
                    $('#teacher-sessions').empty();
                    teacherSessionData = {}; // Re-initialize teacherSessionData for the new student

                    $.each(response, function (index, session) {

                        $('#teacher-sessions').append('<option value="' + session.id + '">' + session.session_name + '</option>');
                        teacherSessionData[session.id] = {
                            price: session.session_amount,
                            total_payments: session.paid_amount,
                            meetings: session.meetings,
                            meetings_count: session.meetings_count,
                            teacher_id: session.teacher_id,
                            session_id: session.id
                        };
                    });

                    // Unbind the previous change event handler to avoid stacking
                    $('#teacher-sessions').off('change').change(function () {
                        let selectedSessionId = $(this).val();
                        console.log(teacherSessionData)
                        if (selectedSessionId) {
                            let selectedSession = teacherSessionData[selectedSessionId];
                            $('#teacher_session_cost').val(selectedSession.price);
                            $('#teacher_meetings').val(selectedSession.meetings);
                            $('#teacher_finished_meetings').val(selectedSession.meetings_count);
                            $('#teacher_total_payments').val(selectedSession.total_payments);
                            $('#teacher_id').val(selectedSession.teacher_id);
                            $('#session_id').val(selectedSession.session_id);
                        } else {
                            $('#teacher_session_cost').val('');
                            $('#teacher_total_payments').val('');
                            $('#teacher_meetings').val('');
                            $('#teacher_finished_meetings').val('');
                        }
                    });

                    // Trigger change to update fields for the initially selected option
                    $('#teacher-sessions').trigger('change');
                },
                error: function (xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        });

        // Handle change event for teacher-sessions to update cost and paid amounts
        $('#teacher-sessions').change(function() {
            var selectedOption = $(this).find('option:selected');
            var sessionId = selectedOption.val();
            var sessionCost = selectedOption.data('cost');
            var paidAmount = selectedOption.data('paid');

            // Set the session_id hidden input
            $('#session_id').val(sessionId);

            // Update the cost and paid amounts
            $('#teacher_session_cost').val(sessionCost);
            $('#teacher_total_payments').val(paidAmount);
        });

    });

</script>


<script>
    $(document).ready(function () {
        let income_table = $('#dt-filter-search-income').DataTable({
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
                "lengthMenu": "عرض _MENU_ سجلات",
                "info": "عرض _START_ إلى _END_ من _TOTAL_ سجلات",
                "infoEmpty": "لا توجد سجلات متاحة",
                "infoFiltered": "(تمت تصفيته من _MAX_ إجمالي السجلات)",
                "search": "بحث:",
                "zeroRecords": "لم يتم العثور على تطابقات"
            },
            dom: 'Bfrtip',
            "order": [[0, 'desc']],
            buttons: [
                 'excel', 'print', {
                    text: 'PDF',
                    action: function (e, dt, button, config) {
                        // Get the table headers
                        var headers = [];
                        $('#dt-filter-search-income thead th').each(function() {
                            headers.push($(this).text());
                        });

                        // Get the table data
                        var data = [];
                        dt.rows({ search: 'applied' }).every(function() {
                            let row = [];
                            $(this.node()).find('td').each(function() {
                                row.push($(this).text());
                            });
                            data.push(row);
                        });

                        // Create a form and submit it
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
                            value: 'income_report'
                        }));

                        form.appendTo('body').submit();
                    }
                }
            ],


            initComplete: function () {
                this.api().columns().every(function () {
                    let column = this;
                    let search = $(`<input class="form-control form-control-sm" type="text" placeholder="بحث">`)
                        .appendTo($(column.footer()).empty())
                        .on('change input', function () {
                            let val = $(this).val()

                            column
                                .search(val ? val : '', true, false)
                                .draw();
                        });

                });
            }
        });

        $('#lengthMenuIncome').on('change', function() {
            var length = $(this).val();
            income_table.page.len(length).draw();
        });
        let outcome_table = $('#dt-filter-search-outcome').DataTable({
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
                "lengthMenu": "عرض _MENU_ سجلات",
                "info": "عرض _START_ إلى _END_ من _TOTAL_ سجلات",
                "infoEmpty": "لا توجد سجلات متاحة",
                "infoFiltered": "(تمت تصفيته من _MAX_ إجمالي السجلات)",
                "search": "بحث:",
                "zeroRecords": "لم يتم العثور على تطابقات"
            },
            dom: 'Bfrtip',
            "order": [[0, 'desc']],
            buttons: [
                'excel', 'print', {
                    text: 'PDF',
                    action: function (e, dt, button, config) {
                        // Get the table headers
                        var headers = [];
                        $('#dt-filter-search-income thead th').each(function() {
                            headers.push($(this).text());
                        });

                        // Get the table data
                        var data = [];
                        dt.rows({ search: 'applied' }).every(function() {
                            let row = [];
                            $(this.node()).find('td').each(function() {
                                row.push($(this).text());
                            });
                            data.push(row);
                        });

                        // Create a form and submit it
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
                            value: 'outcome_report'
                        }));

                        form.appendTo('body').submit();
                    }
                }
            ],


            initComplete: function () {
                this.api().columns().every(function () {
                    let column = this;
                    let search = $(`<input class="form-control form-control-sm" type="text" placeholder="بحث">`)
                        .appendTo($(column.footer()).empty())
                        .on('change input', function () {
                            let val = $(this).val()

                            column
                                .search(val ? val : '', true, false)
                                .draw();
                        });

                });
            }
        });
        $('#lengthMenuOutcome').on('change', function() {
            var length = $(this).val();
            outcome_table.page.len(length).draw();
        });
    });
</script>


<script>
    $('#generatePdf').on('click', function() {
        let htmlContent = $('#summary').html(); // Get the HTML content of the div

        let form = $('<form>', {
            action: '../mpdf-generator.php',
            method: 'POST'
        }).append($('<input>', {
            type: 'hidden',
            name: 'reportType',
            value: 'all_stats_report'
        })).append($('<input>', {
            type: 'hidden',
            name: 'htmlContent',
            value: htmlContent
        }));

        form.appendTo('body').submit();
    });

</script>
<?php require 'footer.php'; // Include the footer ?>
