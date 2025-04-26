<?php require 'header.php'; // Include the header
//require '../MyDB.php'; // Include the database connection class
$db = new MyDB();

$students = $db->allStudents();
$approvedAttendances = $db->getApprovedAttendances();
$incomeStats = $db->getIncomeStatistics();
$outcomeStats = $db->getOutcomeStatistics();
$teachersStats = $db->getTeachersOutcomeStatistics();
$othersStats = $db->getOthersOutcomeStatistics();
$totalBalance = $incomeStats['total_amount'] - $outcomeStats['total_amount'];
$remainsData = $db->getRemainsData();
$payments = $db->getPaymentHistory();
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
                <a class="nav-link" id="outcome-tab" data-toggle="tab" href="#outcome" role="tab"
                   aria-controls="outcome"
                   aria-selected="false">المصاريف</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="summary-tab" data-toggle="tab" href="#summary" role="tab"
                   aria-controls="summary"
                   aria-selected="false">الملخص</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="remains-summary" data-toggle="tab" href="#remains" role="tab"
                   aria-controls="remains-summary"
                   aria-selected="false">المتبقي</a>
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
                                <select type="text" class="form-control" id="income_cashier" name="income_cashier"
                                        required>
                                    <option value="" selected disabled>اختر المستلم</option>
                                    <option value="السكرتيرة">السكرتيرة</option>
                                    <option value="الإدارة">الإدارة</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="income_payer">الدافع</label>
                                <div class="input-group">
                                    <select class="form-control" id="income_payer_select"
                                            onchange="toggleIncomePayerInput()">
                                        <option value="" selected>اختر</option>
                                        <option value="وارد خارجي">وارد خارجي</option>
                                        <option value="other">أخرى (إدخال نص)</option>
                                    </select>
                                    <input type="text" class="form-control" id="income_payer_input" name="income_payer"
                                           style="display: none;" placeholder="أدخل النص هنا">
                                </div>
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
                                <label for="session_type_filter">نوع الدورة</label>
                                <select class="form-control" id="session_type_filter">
                                    <option value="">الكل</option>
                                    <option value="دورة خاصة">دورة خاصة</option>
                                    <option value="حقيبة مدرسية">حقيبة مدرسية</option>
                                    <option value="اشتراك شهري">اشتراك شهري</option>
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
                                <input type="number" min="0" class="form-control" id="income_amount"
                                       name="income_amount"
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
                        <div class="table-responsive">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="lengthMenuIncome">عرض</label>
                                    <select id="lengthMenuIncome" class="form-control form-control-sm"
                                            style="width: auto; display: inline-block;">
                                        <option value="10">10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="-1">الكل</option>
                                    </select>
                                    <label>سجلات</label>
                                </div>
                            </div>
                            <table class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>الرقم</th>
                                    <th>التاريخ</th>
                                    <th>المستلم</th>
                                    <th>المبلغ</th>
                                    <th>الدافع</th>
                                    <th>الطالب</th>
                                    <th>الدورة</th>
                                    <th>ملاحظات</th>
                                    <th>إجراءات</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($payments as $payment): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($payment['id']) ?></td>
                                        <td><?= htmlspecialchars($payment['date']) ?></td>
                                        <td><?= htmlspecialchars($payment['cashier']) ?></td>
                                        <td><?= htmlspecialchars($payment['amount']) ?></td>
                                        <td><?= htmlspecialchars($payment['payer']) ?></td>
                                        <td><?= isset($payment['student_name']) ? htmlspecialchars($payment['student_name']) : 'وارد خارجي' ?></td>
                                        <td><?= isset($payment['session_name']) ? htmlspecialchars($payment['session_name']) : 'N/A' ?></td>
                                        <td><?= htmlspecialchars($payment['notes']) ?></td>
                                        <td>
                                            <button class="btn btn-danger btn-sm reverse-payment"
                                                    data-payment-id="<?= $payment['id'] ?>"
                                                    data-student-id="<?= $payment['student_id'] ?>"
                                                    data-session-id="<?= $payment['session_id'] ?>"
                                                    data-amount="<?= $payment['amount'] ?>">
                                                تراجع
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
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
                                <select type="text" class="form-select" id="outcome_type" name="outcome_type" required>
                                    <option value="" selected disabled>اختر النوع</option>
                                    <option value="مستلزمات مكتب">مستلزمات مكتب</option>
                                    <option value="أجور">أجور</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="outcome_receiver">المستلم</label>
                                <select class="form-select" id="outcome_receiver" name="outcome_receiver" required>
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
                                <select class="form-select" id="teacher-sessions" name="teacher_sessions" required>
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
                                <input type="number" min="0" class="form-control" id="outcome_amount"
                                       name="outcome_amount" required>
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
                                <select id="lengthMenuOutcome" class="form-select form-control-sm"
                                        style="width: auto; display: inline-block;">
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

            <div class="tab-pane fade" id="summary" role="tabpanel" aria-labelledby="summary-tab"
                 style="direction: rtl; text-align: right; font-family: 'Open Sans', sans-serif;">
                <?php if ($_COOKIE['role'] == 1): ?>

                    <button id="generatePdf" class="btn btn-primary mt-4">PDF</button>

                    <!-- Month and Year Selector for Secretary Report -->
                    <div id="secretary-report" class="row mt-4">
                        <div class="col-md-12">
                            <h3>تقرير السكرتارية</h3>
                            <div class="form-group">
                                <label for="reportMonth">اختر الشهر</label>
                                <select id="reportMonth" class="form-control"
                                        style="width: 150px; display: inline-block;">
                                    <?php for ($i = 1; $i <= 12; $i++): ?>
                                        <option value="<?php echo $i; ?>">
                                            <?php echo $i; ?>
                                        </option>
                                    <?php endfor; ?>
                                </select>

                                <label for="reportYear" class="ml-3">اختر السنة</label>
                                <select id="reportYear" class="form-control"
                                        style="width: 150px; display: inline-block;">
                                    <?php for ($year = date('Y'); $year >= 2000; $year--): ?>
                                        <option value="<?php echo $year; ?>">
                                            <?php echo $year; ?>
                                        </option>
                                    <?php endfor; ?>
                                </select>
                                <button id="generateSecretaryPdf" class="btn btn-primary">تقرير دوام السكرتيرة</button>

                            </div>
                            <div id="secretaryTimesheetReport" class="container mt-4"
                                 style="border: 1px solid #ddd; padding: 20px; border-radius: 4px;">
                                <p>تقرير أوقات تسجيل الدخول والخروج للسكرتيرة حسب الشهر والسنة المختارة.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Summary Information -->
                    <div class="row">
                        <div class="col-md-12" style="direction: rtl; text-align: right;">
                            <h3>الملخص</h3>
                            <div class="row">
                                <div class="card col-md-6"
                                     style="border: 1px solid #ddd; border-radius: 4px; padding: 20px;">
                                    <div class="card-body">
                                        <h5 class="card-title">الواردات</h5>
                                        <p>عدد الدفعات المستلمة: <?php echo $incomeStats['count']; ?></p>
                                        <p>المبلغ الكلي: <?php echo $incomeStats['total_amount']; ?></p>
                                    </div>
                                </div>
                                <div class="card col-md-6"
                                     style="border: 1px solid #ddd; border-radius: 4px; padding: 20px;">
                                    <div class="card-body">
                                        <h5 class="card-title">الرصيد الكلي</h5>
                                        <h6 class="text-success"><?php echo $totalBalance; ?></h6>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="card col-md-4 mt-4"
                                     style="border: 1px solid #ddd; border-radius: 4px; padding: 20px;">
                                    <div class="card-body">
                                        <h5 class="card-title">كل المصاريف</h5>
                                        <p>عدد الدفعات الصادرة: <?php echo $outcomeStats['count']; ?></p>
                                        <p>المبلغ الكلي: <?php echo $outcomeStats['total_amount']; ?></p>
                                    </div>
                                </div>
                                <div class="card col-md-4 mt-4"
                                     style="border: 1px solid #ddd; border-radius: 4px; padding: 20px;">
                                    <div class="card-body">
                                        <h5 class="card-title">مصاريف المعلمين</h5>
                                        <p>عدد الدفعات الصادرة للمعلمين: <?php echo $teachersStats['count']; ?></p>
                                        <p>المبلغ الكلي: <?php echo $teachersStats['total_amount']; ?></p>
                                    </div>
                                </div>
                                <div class="card col-md-4 mt-4"
                                     style="border: 1px solid #ddd; border-radius: 4px; padding: 20px;">
                                    <div class="card-body">
                                        <h5 class="card-title">مصاريف خارجية</h5>
                                        <p>عدد الدفعات الصادرة الى جهات خارجية: <?php echo $othersStats['count']; ?></p>
                                        <p>المبلغ الكلي: <?php echo $othersStats['total_amount']; ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <script>
                $('#generateSecretaryPdf').on('click', function () {
                    let selectedMonth = $('#reportMonth').val(); // Get selected month
                    let selectedYear = $('#reportYear').val(); // Get selected year

                    let form = $('<form>', {
                        action: '../mpdf-generator.php',
                        method: 'POST'
                    });

                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'reportType',
                        value: 'secretary_timesheet_report'
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'headers',
                        value: selectedMonth
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'tableData',
                        value: selectedYear
                    }));

                    form.appendTo('body').submit();
                });
            </script>

            <!-- Remains Tab -->

            <div class="tab-pane fade" id="remains" role="tabpanel" aria-labelledby="remains-tab"
                 style="direction: rtl; text-align: right; font-family: 'Open Sans', sans-serif;">


                <div class="row">
                    <div class="col-md-4">
                        <label for="lengthMenuRemains">عرض</label>
                        <select id="lengthMenuRemains" class="form-control form-control-sm"
                                style="width: auto; display: inline-block;">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="-1">الكل</option>
                        </select>
                        <label>سجلات</label>
                    </div>
                    <div class="col-md-4">
                        <label for="filterSessionType">تصفية حسب نوع الجلسة</label>
                        <select id="filterSessionType" class="form-control form-control-sm"
                                style="width: auto; display: inline-block;">
                            <option value="">الكل</option>
                            <?php
                            // Populate session types dynamically
                            $sessionTypes = array_unique(array_column($remainsData, 'session_type'));
                            foreach ($sessionTypes as $type) {
                                echo "<option value='$type'>$type</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="classFilter">تصفية حسب المرحلة الدراسية</label>
                        <select id="classFilter" class="form-control form-control-sm"
                                style="width: auto; display: inline-block;">
                            <?php
                            // Populate session types dynamically
                            $sessionTypes = array_unique(array_column($remainsData, 'student_class'));

                            // Group classes into categories
                            $classCategories = [
                                'elementary' => [],
                                'middle' => [],
                                'high' => []
                            ];

                            foreach ($sessionTypes as $class) {
                                $classNum = intval($class);
                                if ($classNum >= 1 && $classNum <= 6) {
                                    $classCategories['elementary'][] = $class;
                                } elseif ($classNum >= 7 && $classNum <= 9) {
                                    $classCategories['middle'][] = $class;
                                } elseif ($classNum >= 10 && $classNum <= 12) {
                                    $classCategories['high'][] = $class;
                                }
                            }

                            // Output the dropdown options
                            echo "<option value=''>جميع المراحل</option>";
                            echo "<option value='elementary'>الابتدائي (1-6)</option>";
                            echo "<option value='middle'>المتوسط (7-9)</option>";
                            echo "<option value='high'>الثانوي (10-12)</option>";
                            ?>
                        </select>

                    </div>
                </div>

                <table class="table table-bordered" id="dt-filter-search-remains">
                    <thead>
                    <tr>
                        <th>اسم الطالب</th>
                        <th>اسم الدورة</th>
                        <th>نوع الدورة</th>
                        <th>المرحلة الدراسية</th>
                        <th>رقم الهاتف</th>
                        <th>السعر الأصلي</th>
                        <th>مجموع الدفعات</th>
                        <th>المبلغ المتبقي</th>
                        <th>تاريخ آخر دفعة</th>
                        <th>تاريخ الإضافة</th>
                        <th>ملاحظات</th>
                        <th></th>

                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($remainsData as $remains) {
                        // Format last payment date
                        $strip = "لا توجد دفعات";
                        $date = $remains['last_payment_date'] ?? null;
                        if ($date) {
                            $createDate = new DateTime($date);
                            $strip = $createDate->format('Y-m-d');
                        }

                        // Format session added date
                        $sessionAddedAt = $remains['session_added_at'] ?? null;
                        $addedDate = "لم يتم تسجيل التاريخ";
                        if ($sessionAddedAt) {
                            $createDateAdded = new DateTime($sessionAddedAt);
                            $addedDate = $createDateAdded->format('Y-m-d H:i:s');
                        }

                        echo "<tr>";
                        echo "<td>{$remains['student_name']}</td>";
                        echo "<td>{$remains['session_name']}</td>";
                        echo "<td>{$remains['session_type']}</td>";
                        echo "<td>{$remains['student_class']}</td>";
                        echo "<td>{$remains['student_phone']}</td>";
                        echo "<td>{$remains['session_cost']}</td>";
                        echo "<td>{$remains['total_payments']}</td>";
                        echo "<td>{$remains['amount_due']}</td>";
                        echo "<td>{$strip}</td>";
                        echo "<td>{$addedDate}</td>";

                        // Display the note with an id for updating it later
                        $note = !empty($remains['session_note']) ? $remains['session_note'] : "لا توجد ملاحظات";
                        echo "<td id='note-cell-{$remains['student_id']}-{$remains['session_id']}'>{$note}</td>";

                        // Button and field for adding a new note
                        echo "<td>";
                        echo "<button type='button' class='btn btn-sm btn-primary' onclick='addNoteField({$remains['student_id']}, {$remains['session_id']})'>إضافة ملاحظة</button>";
                        echo "<div id='note-field-{$remains['student_id']}-{$remains['session_id']}'></div>";
                        echo "</td>";

                        echo "</tr>";
                    }
                    ?>
                    </tbody>
                </table>
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

    <script>
        function toggleIncomePayerInput() {
            const selectElement = document.getElementById('income_payer_select');
            const inputElement = document.getElementById('income_payer_input');

            if (selectElement.value === "other") {
                inputElement.style.display = "block";
                inputElement.required = true;
            } else {
                inputElement.style.display = "none";
                inputElement.required = false;
                inputElement.value = selectElement.value; // Update input value with selected option
            }
        }

        $(document).ready(function () {


            $('#income_payer_select').change(function () {
                if ($(this).val() === "وارد خارجي") {
                    // Hide the required divs when "وارد خارجي" is selected
                    $('#income_student').closest('.form-group').hide();
                    $('#income_student').val(0).removeAttr('required'); // Set value to 0 and remove required

                    $('#session_type_filter').closest('.form-group').hide();
                    $('#income_session').closest('.form-group').hide();
                } else {
                    // Show the divs again when something else is selected
                    $('#income_student').closest('.form-group').show();
                    $('#income_student').attr('required', true); // Re-add required attribute
                    $('#income_session').closest('.form-group').show();
                    $('#session_type_filter').closest('.form-group').show();
                }

                // Also handle the input visibility toggle
                toggleIncomePayerInput();
            });

            // Trigger change event on page load to set initial visibility
            $('#income_payer_select').trigger('change');
        });
    </script>


    <script>
        $(document).ready(function () {
            $('.reverse-payment').click(function () {
                if (confirm('هل أنت متأكد من تراجع عن هذه الدفعة؟ هذا الإجراء لا يمكن التراجع عنه.')) {
                    const paymentId = $(this).data('payment-id');
                    const studentId = $(this).data('student-id');
                    const sessionId = $(this).data('session-id');
                    const amount = $(this).data('amount');

                    $.ajax({
                        url: '../finance_endpoint.php',
                        type: 'POST',
                        data: {
                            action: 'reverse_payment',
                            payment_id: paymentId,
                            student_id: studentId,
                            session_id: sessionId,
                            amount: amount
                        },
                        success: function (response) {
                            alert('تم التراجع عن الدفعة بنجاح');
                            console.log(response)
                            location.reload();
                        },
                        error: function () {
                            alert('حدث خطأ أثناء التراجع عن الدفعة');
                        }
                    });
                }
            });
        });
    </script>
    <script>
        function addNoteField(studentId, sessionId) {
            var fieldId = 'note-field-' + studentId + '-' + sessionId;
            var noteDiv = document.getElementById(fieldId);

            if (!noteDiv.innerHTML) {
                noteDiv.innerHTML = `
            <input type="text" id="note-${studentId}-${sessionId}" placeholder="أدخل ملاحظة" />
            <button type="button" class="btn btn-sm btn-success" onclick="saveNote(${studentId}, ${sessionId})">حفظ الملاحظة</button>
        `;
            }
        }

        function saveNote(studentId, sessionId) {
            var note = document.getElementById('note-' + studentId + '-' + sessionId).value;

            if (note) {
                // Send the note to your PHP script to update the notes table using AJAX
                var xhr = new XMLHttpRequest();
                xhr.open('POST', '../save_note.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.send('student_id=' + studentId + '&session_id=' + sessionId + '&note=' + encodeURIComponent(note));

                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        // Update the note in the corresponding <td>
                        var noteCell = document.querySelector(`#note-cell-${studentId}-${sessionId}`);
                        if (noteCell) {
                            noteCell.textContent = note;
                        }
                        alert('تم حفظ الملاحظة');

                        // Optionally, clear the input field after saving the note
                        var noteDiv = document.getElementById('note-field-' + studentId + '-' + sessionId);
                        noteDiv.innerHTML = '';
                    }
                };
            } else {
                alert('أدخل ملاحظة رجاءاً');
            }
        }
    </script>
    <script>
        $(document).ready(function () {
            var sessionData = {}; // Define sessionData in the outer scope
            $('#teacher-sessions-div').css("visibility", "hidden");
            $('#income_student').change(function () {
                var studentId = $(this).val();
                $.ajax({
                    url: '../get_enrolled_sessions.php', // Endpoint URL
                    type: 'POST',
                    data: {
                        student_id: studentId
                    },
                    dataType: 'json',
                    success: function (response) {
                        $('#income_session').empty();
                        sessionData = {}; // Re-initialize sessionData for the new student

                        // Function to populate sessions
                        function populateSessions(sessions) {
                            $.each(sessions, function (index, session) {
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
                        }

                        // Initial population without filter
                        populateSessions(response);

                        // Add filtering logic
                        $('#session_type_filter').change(function () {
                            var selectedType = $(this).val();
                            var filteredSessions = selectedType ? response.filter(function (session) {
                                return session.type === selectedType;
                            }) : response; // Show all sessions if no type is selected

                            $('#income_session').empty(); // Clear current options
                            populateSessions(filteredSessions); // Re-populate with filtered sessions
                        });
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
                    data: {
                        teacher_id: teacherID
                    },
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
            $('#teacher-sessions').change(function () {
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
        // Ensure the DOM is fully loaded before executing the script
        document.addEventListener('DOMContentLoaded', function () {
            // Function to activate the tab based on the hash in the URL
            function activateTabFromHash() {
                var hash = window.location.hash;
                if (hash) {
                    var targetTab = document.querySelector('a[href="' + hash + '"]');
                    if (targetTab) {
                        var tab = new bootstrap.Tab(targetTab);
                        tab.show();
                    }
                }
            }

            // Activate the tab based on the hash in the URL
            activateTabFromHash();

            // Update the URL hash when a tab is shown
            document.querySelectorAll('#financeTabs a').forEach(function (tab) {
                tab.addEventListener('shown.bs.tab', function (event) {
                    var href = event.target.getAttribute('href');
                    // Update the URL hash without reloading the page
                    history.replaceState(null, null, href);
                });
            });
        });
    </script>


    <script>
        $(document).ready(function () {
            let income_table = $('#dt-filter-search-income').DataTable({
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
                    "lengthMenu": "عرض _MENU_ سجلات",
                    "info": "عرض _START_ إلى _END_ من _TOTAL_ سجلات",
                    "infoEmpty": "لا توجد سجلات متاحة",
                    "infoFiltered": "(تمت تصفيته من _MAX_ إجمالي السجلات)",
                    "search": "بحث:",
                    "zeroRecords": "لم يتم العثور على تطابقات"
                },
                dom: 'Bfrtip',
                "order": [
                    [0, 'desc']
                ],
                buttons: [
                    'excel', 'print', {
                        text: 'PDF',
                        action: function (e, dt, button, config) {
                            // Get the table headers
                            var headers = [];
                            $('#dt-filter-search-income thead th').each(function () {
                                headers.push($(this).text());
                            });

                            // Get the table data
                            var data = [];
                            dt.rows({
                                search: 'applied'
                            }).every(function () {
                                let row = [];
                                $(this.node()).find('td').each(function () {
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

            $('#lengthMenuIncome').on('change', function () {
                var length = $(this).val();
                income_table.page.len(length).draw();
            });

            let outcome_table = $('#dt-filter-search-outcome').DataTable({
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
                    "lengthMenu": "عرض _MENU_ سجلات",
                    "info": "عرض _START_ إلى _END_ من _TOTAL_ سجلات",
                    "infoEmpty": "لا توجد سجلات متاحة",
                    "infoFiltered": "(تمت تصفيته من _MAX_ إجمالي السجلات)",
                    "search": "بحث:",
                    "zeroRecords": "لم يتم العثور على تطابقات"
                },
                dom: 'Bfrtip',
                "order": [
                    [0, 'desc']
                ],
                buttons: [
                    'excel', 'print', {
                        text: 'PDF',
                        action: function (e, dt, button, config) {
                            // Get the table headers
                            var headers = [];
                            $('#dt-filter-search-outcome thead th').each(function () {
                                headers.push($(this).text());
                            });

                            // Get the table data
                            var data = [];
                            dt.rows({
                                search: 'applied'
                            }).every(function () {
                                let row = [];
                                $(this.node()).find('td').each(function () {
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

            $('#lengthMenuOutcome').on('change', function () {
                var length = $(this).val();
                outcome_table.page.len(length).draw();
            });

            let remains_table = $('#dt-filter-search-remains').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
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
                "order": [
                    [0, 'desc']
                ],
                buttons: [
                    'excel', 'print', {
                        text: 'PDF',
                        action: function (e, dt, button, config) {
                            // Get the table headers
                            var headers = [];
                            $('#dt-filter-search-remains thead th').each(function (index) {
                                // Exclude last header
                                if (index < $('#dt-filter-search-remains thead th').length - 1) {
                                    headers.push($(this).text());
                                }
                            });

                            // Get the table data
                            var data = [];
                            dt.rows({
                                search: 'applied'
                            }).every(function () {
                                let row = [];
                                $(this.node()).find('td').each(function (index) {
                                    // Exclude last column
                                    if (index < $(this).siblings().length) {
                                        row.push($(this).text());
                                    }
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
                                value: 'remains_report'
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

            $('#filterSessionType').on('change', function () {
                let sessionType = $(this).val();
                remains_table.column(2).search(sessionType).draw(); // Assumes "session_type" is in the 2nd column
            });
            $('#classFilter').on('change', function() {
                let selectedCategory = $(this).val();
                let searchPattern = '';

                // Create regex pattern based on selected category
                switch(selectedCategory) {
                    case 'elementary':
                        searchPattern = '^[1-6]$'; // Matches 1-6 exactly
                        break;
                    case 'middle':
                        searchPattern = '^[7-9]$'; // Matches 7-9 exactly
                        break;
                    case 'high':
                        searchPattern = '^(10|11|12)$'; // Matches 10-12 exactly
                        break;
                    default:
                        searchPattern = ''; // Show all
                }

                // Apply the filter to the appropriate column (change 3 to your actual column index)
                remains_table.column(3).search(searchPattern, true, false).draw();
            });
            $('#lengthMenuRemains').on('change', function () {
                var length = $(this).val();
                remains_table.page.len(length).draw();
            });
        });
    </script>


    <script>
        $('#generatePdf').on('click', function () {
            let htmlContent = $('#summary').clone();

            // Remove the div with id="secretary-report" from the copied content
            htmlContent.find('#secretary-report').remove();

            // Get the HTML string of the modified content
            let htmlString = htmlContent.html();

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
                value: htmlString
            }));

            form.appendTo('body').submit();
        });
    </script>
<?php require 'footer.php'; ?>