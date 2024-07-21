<?php require 'header.php';
$db = new MyDB();
?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<!-- DataTables Buttons JS -->
<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>
<!-- JSZip for Excel export -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

<!-- Begin Page Content -->
<div class="container-fluid ">


    <!-- DataTales Example -->
    <div class="card shadow mb-4 mt-3 p-3 rounded  text-right">
        <div class="card-header py-3">
            <h4 class="m-0 font-weight-bold text-primary">تقرير الحصص</h4>
            <?php $totalPayments = $db->totalPayments($_COOKIE['id']);
            ?>
            <tr>
                <td><p class="alert alert-info">عدد الحصص الموافق
                        عليها: <?= $totalPayments['total_sessions']; ?></td>

            </tr>

        </div>
        <div class="card-body">
            <div class="table-responsive">
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
                <table class="table table-bordered" id="report_table" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>حالة الحصة</th>
                            <th>اسم الطالب</th>
                            <th>المادة</th>
                            <th>التاريخ</th>
                            <th>وقت البدء</th>
                            <th>وقت الإنتهاء</th>
                            <th>وقت الحصة</th>
                        </tr>
                    </thead>

                    <tbody>


                    <?php
                    $d = date('d/m/Y');
                    $acmi = $db->getTeacherSessionsAttendances($_COOKIE['id']);

                     foreach ($acmi as $row) {
                    ?>
                    <tr>
                        <!-- approved -->
                        <td class="text text-<?php $row['aprove'] == 0 ? print "danger" : print "success" ?>">

                            <?php
                            $row['aprove'] == 0
                                ? print "غير موافق عليها"
                                : print "موافق عليها"

                            ?>

                        </td>
                        <!-- name -->
                        <td><?= $row['name']; ?></td>
                        <!-- name -->
                        <td><?= $row['session_name']; ?></td>
                        <!-- date -->
                        <td><?= $row['date']; ?></td>
                        <!-- starting time -->
                        <td>
                            <?= $row['enter']; ?>
                        </td>
                        <!-- ending time -->
                        <td><?= $row['exit']; ?></td>
                        <!-- total -->
                        <td><?= $row['total'] ?></td>


                    </tr>
                    <?php } ?>

                    </tbody>
                </table>
            </div>
        </div>

    </div>

</div>
<!-- /.container-fluid -->


<script>
    $(document).ready(function () {
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
                "lengthMenu": "عرض _MENU_ سجلات",
                "info": "عرض _START_ إلى _END_ من _TOTAL_ سجلات",
                "infoEmpty": "لا توجد سجلات متاحة",
                "infoFiltered": "(تمت تصفيته من _MAX_ إجمالي السجلات)",
                "search": "بحث:",
                "zeroRecords": "لم يتم العثور على تطابقات"
            },
            "lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "الكل"] ],
            "order": [[0, 'desc']],
            serverSide: false,
            dom: 'Bfrtip',
            buttons: [
                'excel', 'print', {
                    text: 'PDF',
                    action: function (e, dt, button, config) {
                        // Get the table headers, skipping the last two
                        let headers = [];
                        $('#report_table thead th').each(function(index) {
                            if (index < 9) { // Assuming there are 7 columns, change this if different
                                headers.push($(this).text());
                            }
                        });

                        // Get the table data, skipping the last two columns
                        let data = [];
                        dt.rows({ search: 'applied' }).every(function() {
                            let row = [];
                            $(this.node()).find('td').each(function(index) {
                                if (index < 9) { // Assuming there are 7 columns, change this if different
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
                            value: 'public_report'
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

        $('#lengthMenu').on('change', function() {
            let length = $(this).val();
            table.page.len(length).draw();
        });
    });
</script>
<!-- End of Page Wrapper -->