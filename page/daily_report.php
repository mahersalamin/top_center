<?php require 'header.php'; ?>

<link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.min.css">
<!-- Bootstrap CSS (if you use Bootstrap) -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<!-- jQuery (necessary for DataTables) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- DataTables JS -->

<script src="https://cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>
<!-- Buttons extension JS -->
<script src="https://cdn.datatables.net/buttons/2.3.3/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.3/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.3/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.3/js/buttons.print.min.js"></script>
<style>
    h1 {
        text-align: center;
    }

    button {
        background-color: #04AA6D;
        color: #ffffff;
        border: none;
        padding: 10px 20px;
        font-size: 17px;
        font-family: serif;
        cursor: pointer;
    }

    button:hover {
        opacity: 0.8;
        transition: 0.3s;
    }

    #edit-student-container {
        display: none;
    }

    .scrollable-checkbox-list {
        max-height: 300px;
        /* Adjust as needed */
        overflow-y: auto;
    }

    .form-check {
        margin-bottom: 5px;
    }

    .form-check-label {
        margin-left: 10px;
    }
</style>
<?php

if (!isset($_COOKIE['id'])) {
    header("location:signin.php");
}
$db = new MyDB();

$attendances = $db->dailyReport();
?>
<div class="container text-right" id="edit-student-container">
    <h3>تعديل الحضور</h3>
    <input type="text" id="search-students" class="form-control mb-3" placeholder="بحث...">
    <form id="edit-student-form ">
        <div id="checkbox-list" class="scrollable-checkbox-list">
            <!-- Checkboxes will be populated dynamically here -->
        </div>

        <button type="button" id="update-students-btn" class="btn btn-primary mt-3">حفظ</button>
    </form>
</div>
<div class="container-fluid text-right" style="font-family: 'Cairo' ">
    <h2 class="mb-4">سجل الحضور</h2>
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
    <table class="table table-bordered" id="daily_table">
        <thead>
            <tr>
                <th>التاريخ</th>
                <th>المعلم</th>
                <th>الطلاب</th>
                <th>الحصة</th>
                <th>مدة الحصة</th>
                <th>عدد ساعات الدورة الكلي</th>
                <th></th>
            </tr>
        </thead>
        <tbody>

            <?php if (!empty($attendances)) { ?>
                <?php foreach ($attendances as $attendance) { ?>
                    <tr>
                        <td><?= $attendance['date']; ?></td>
                        <td><?= $attendance['tname']; ?></td>
                        <td>
                            <span class="student-names"><?= htmlspecialchars($attendance['snames']); ?></span>
                            <button class="btn btn-primary btn-edit-students"
                                data-attendance-id="<?= $attendance['id']; ?>"
                                data-student-id="<?= $attendance['st_id']; ?>"
                                data-snames="<?= htmlspecialchars($attendance['snames']); ?>"
                                data-session-id="<?= $attendance['session_id']; ?>">تعديل
                            </button>
                        </td>
                        <td><?= $attendance['session_name']; ?></td>
                        <td><?= $attendance['total']; ?></td>
                        <td><?= $attendance['hours']; ?></td>
                        <td>
                            <?php if ($attendance['processed'] == 1) { ?>
                                <button class="btn btn-danger accept-attendance"
                                    data-accept="0"
                                    data-session-id="<?php echo $attendance['session_id']; ?>"
                                    data-attendance-id="<?php echo $attendance['id']; ?>"
                                    data-student-ids="<?php echo $attendance['st_id']; ?>"
                                    data-hours="<?= $attendance['hours']; ?>">
                                    تراجع عن التأكيد
                                </button>
                            <?php } else { ?>
                                <button class="btn btn-success accept-attendance"
                                    data-accept="1"
                                    data-session-id="<?php echo $attendance['session_id']; ?>"
                                    data-attendance-id="<?php echo $attendance['id']; ?>"
                                    data-student-ids="<?php echo $attendance['st_id']; ?>"
                                    data-hours="<?php echo $attendance['hours']; ?>">
                                    تأكيد الحصة
                                </button>

                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
            <?php } else { ?>
                <tr>
                    <td colspan="5" class="text-center">لا يوجد سجلات</td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

</div>

<script>
    $('.btn-edit-students').on('click', function() {
        let attendanceId = $(this).data('attendance-id');
        let studentsIdsString = $(this).data('student-id') || ''; // Ensure it's a string
        let sessionId = $(this).data('session-id');
        let snames = $(this).data('snames').split(', ');

        // Ensure studentsIdsString is a string and split it into an array
        let studentsIds = [];
        if (typeof studentsIdsString === 'string') {
            studentsIds = studentsIdsString.split(',').map(id => id.trim()); // Convert string to array and trim whitespace
        } else if (typeof studentsIdsString === 'number') {
            studentsIds = [studentsIdsString.toString()]; // Convert number to string and put it in an array
        }


        $.post('../fetch-student-names.php', {
            session_id: sessionId
        }, function(data) {
            // Clear the current checkbox list
            $('#checkbox-list').empty();

            // Populate the checkbox list with the fetched student names
            data.forEach(student => {
                $('#checkbox-list').append(
                    `<div class="form-check">
                    <input class="form-check-input student-checkbox" type="checkbox" value="${student.id}" id="student_${student.id}">
                    <label class="form-check-label mr-4" for="student_${student.id}">
                        ${student.name} (${student.class}, ${student.school_name})
                    </label>
                </div>`
                );
            });

            // Debugging: Log the student IDs and checkbox IDs
            studentsIds = studentsIds.map(id => id.toString().trim());

            $('#checkbox-list .student-checkbox').each(function() {
                let $checkbox = $(this);
                let studentId = $checkbox.val(); // Get the student ID from the checkbox value

                if (studentsIds.includes(studentId)) {
                    $checkbox.prop('checked', true);
                } else {
                    $checkbox.prop('checked', false);
                }
            });


            // Show the edit student section
            $('#edit-student-container').show().data('attendance-id', attendanceId).data('session-id', sessionId);

            // Scroll down to the edit student section
            $('html, body').animate({
                scrollTop: $('#edit-student-container').offset().top
            }, 1000);
        }, 'json');
    });

    $('#update-students-btn').on('click', function() {
        // Get the attendance ID and session ID from the edit student container
        let attendanceId = $('#edit-student-container').data('attendance-id');
        let sessionId = $('#edit-student-container').data('session-id');

        let checkedStudentIds = [];
        $('#checkbox-list .student-checkbox:checked').each(function() {
            checkedStudentIds.push($(this).val());
        });


        $.post('../save-student-names.php', {
            attendance_id: attendanceId,
            session_id: sessionId,
            student_ids: checkedStudentIds.join(',')
        }, function(response) {
            // Handle the response from the server
            if (response.success) {
                alert('تم تعديل الأسماء بنجاح');
                // Optionally, hide the edit student section and reload the page
                $('#edit-student-container').hide();
                location.reload();
            } else {
                alert('خطأ');
            }
        }, 'json');
    });
</script>


<script>
    $(document).ready(function() {
        let table = $('#daily_table').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": true,
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
            "order": [
                [0, 'desc']
            ],
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "الكل"]
            ],
            serverSide: false,
            dom: 'Bfrtip',
            buttons: [
                'excel', 'print', {
                    text: 'PDF',
                    action: function(e, dt, button, config) {
                        // Get the table headers (excluding the last column)
                        let headers = [];
                        $('#daily_table thead th').each(function(index) {
                            if (index < 6) { // Exclude the last header (7th header, index starts at 0)
                                headers.push($(this).text());
                            }
                        });

                        // Get the table data (excluding the last column and ignoring buttons inside the student names column)
                        let data = [];
                        dt.rows({
                            search: 'applied'
                        }).every(function() {
                            let row = [];
                            $(this.node()).find('td').each(function(index) {
                                if (index < 6) { // Exclude the last column (7th column, index starts at 0)
                                    // If it's the student column, exclude the button text
                                    if ($(this).find('.btn-edit-students').length > 0) {
                                        row.push($(this).find('.student-names').text()); // Only push student names, not the button
                                    } else {
                                        row.push($(this).text()); // For other columns, push the cell text
                                    }
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
                            value: 'daily_report'
                        }));

                        form.appendTo('body').submit();
                    }
                }

            ],


            initComplete: function() {
                this.api().columns().every(function() {
                    let column = this;
                    $(`<input class="form-control form-control-sm" type="text" placeholder="بحث">`)
                        .appendTo($(column.footer()).empty())
                        .on('change input', function() {
                            let val = $(this).val()

                            column
                                .search(val ? val : '', true, false)
                                .draw();
                        });

                });
            }
        });

        $('#lengthMenu').on('change', function() {
            let length = $(this).val();
            table.page.len(length).draw();
        });


        $('.accept-attendance').click(function() {
            let sessionId = $(this).data('session-id');
            let attendanceId = $(this).data('attendance-id');
            let studentIds = $(this).data('student-ids');
            let hours = $(this).data('hours');
            let accept = $(this).data('accept');

            $.ajax({
                url: '../accept_attendance.php', // The URL to your PHP script
                type: 'POST',
                data: {
                    session_id: sessionId,
                    attendance_id: attendanceId,
                    student_ids: studentIds,
                    hours: hours,
                    accept: accept
                },
                success: function(response) {
                    // console.log(response)
                    response = JSON.parse(response)
                    alert(response.message);
                    location.reload();
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        });
    });
</script>