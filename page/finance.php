<?php require 'header.php'; // Include the header
//require '../MyDB.php'; // Include the database connection class
$db = new MyDB();

$students = $db->allStudents();
//$studentSessions = $db->getEnrolledSessionsForStudent(1);
$approvedAttendances = $db->getApprovedAttendances();
// Fetch summary statistics
$incomeStats = $db->getIncomeStatistics();
$outcomeStats = $db->getOutcomeStatistics();
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
                            </select>
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
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>الرقم</th>
                            <th>التاريخ</th>
                            <th>المستلم</th>
                            <th>الدافع</th>
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

                            echo "<td>{$income['amount']}</td>";
                            echo "<td>{$income['notes']}</td>";
                            echo "</tr>";
                        }
                        ?>
                        </tbody>
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
                                <option value="0" >مستفيد خارجي</option>
                                <?php
                                // Fetch teachers from the database
                                $teachers = $db->getAllTeachers();

                                // Loop through the teachers and populate the options
                                foreach ($teachers as $teacher) {
                                    echo "<option value='{$teacher['id']}'>{$teacher['name']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="outcome_amount">القيمة</label>
                            <input type="number" min="0" class="form-control" id="outcome_amount" name="outcome_amount" required>
                        </div>
                        <div class="form-group">
                            <label for="outcome_notes">ملاحظات</label>
                            <textarea class="form-control" id="outcome_notes" name="outcome_notes"></textarea>
                        </div>
                        <button type="submit" name="outcome_submit" class="btn btn-success mt-3">حفظ</button>
                    </form>
                </div>
                <div class="col-md-8">
                    <h3>تقرير المصاريف</h3>
                    <table class="table table-bordered">
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
                            if($outcome['receiver'] == 0){
                                echo "<td>مستفيد خارجي</td>";
                            } else {
                                echo "<td>{$outcome['receiver']}</td>";
                            }

                            echo "<td>{$outcome['amount']}</td>";
                            echo "<td>{$outcome['notes']}</td>";
                            echo "</tr>";
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Summary Tab -->
        <div class="tab-pane fade" id="summary" role="tabpanel" aria-labelledby="summary-tab">
            <div class="row">
                <div class="col-md-12">
                    <h3>الملخص</h3>
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">الواردات</h5>
                            <p>عدد الدفعات المستلمة: <?php echo $incomeStats['count']; ?></p>
                            <p>المبلغ الكلي: <?php echo $incomeStats['total_amount']; ?></p>
                        </div>
                    </div>
                    <div class="card mt-4">
                        <div class="card-body">
                            <h5 class="card-title">المصاريف</h5>
                            <p>عدد الدفعات الصادرة: <?php echo $outcomeStats['count']; ?></p>
                            <p>المبلغ الكلي: <?php echo $outcomeStats['total_amount']; ?></p>
                        </div>
                    </div>
                    <div class="card mt-4">
                        <div class="card-body">
                            <h5 class="card-title">الرصيد الكلي</h5>
                            <p><?php echo $totalBalance; ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#income_student').change(function() {
            var studentId = $(this).val();
            $.ajax({
                url: '../get_enrolled_sessions.php', // Endpoint URL
                type: 'POST',
                data: { student_id: studentId },
                dataType: 'json',
                success: function(response) {
                    $('#income_session').empty();
                    $.each(response, function(index, session) {
                        $('#income_session').append('<option value="' + session.id + '">' + session.session_name +
                            '</option>');
                    });
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        });
    });

</script>
<?php require 'footer.php'; // Include the footer ?>
