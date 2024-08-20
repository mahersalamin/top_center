<?php error_reporting(0);
require 'header.php';
require '../dbconnection.php';
?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<!-- DataTables Buttons JS -->
<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>

<!-- JSZip for Excel export -->

<style>
    table.dataTable thead th, table.dataTable thead td, table.dataTable tfoot th, table.dataTable tfoot td {
        text-align: center;
    }
</style>

<div class="container-fluid" style="font-family: 'Cairo' ">

    <div class="row">
        <div class="col-md-1">
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
    <table id="report_table" style="text-align: right" class="table table-bordered text-right" width="100%"
           cellspacing="0">
        <thead>
        <tr>
            <th>الحالة</th>
            <th>بحاجة للتأكيد</th>
            <th>الطالب</th>
            <th>المعلم</th>
            <th>اسم الدورة</th>
            <th>التاريخ</th>
            <th>وقت البدء</th>
            <th>وقت الإنتهاء</th>
            <th>مدة الحصة</th>
            <th></th>
            <th></th>

        </tr>
        </thead>
        <tbody>
        <?php
        $d = date('d/m/Y');
        $db = new MyDB();
        $Students = $db->getAllSessions();

        foreach ($Students as $row) {
            ?>
            <tr>
                <td>
                    <?php $approveStatus = $row['aprove'] == 1 ? 'موافق' : 'غير موافق'; ?>

                    <p>الحالة: <?= $approveStatus; ?></p>
                    <!-- Display the button to toggle the approve status -->

                </td>
                <td>
                    <?php
                    $processed = $row['processed'] == 1
                        ? 'لا'
                        : 'نعم';

                    ?>
                    <p>الحالة: <?= $processed; ?></p>

                </td>
                <td>
                    <?php $snames = explode(',', $row['snames']);
                    echo '<ul  class="list-group">';
                    foreach ($snames as $sname) {
                        echo '<li>' . $sname . '</li>';

                    }
                    echo '</ul>'
                    ?>

                </td>
                <td><?php echo $row['tname']; ?></td>
                <td><?php echo $row['session_name']; ?></td>
                <td><?php echo $row['date']; ?></td>
                <td><?php echo $row['enter']; ?></td>
                <td><?php echo $row['exit']; ?></td>
                <td><?php echo $row['total']; ?></td>
                <td>

                    <form action="../changStatus.php" method="post">
                        <input type="hidden" name="row_id" value="<?php echo $row['id']; ?>">
                        <button type="submit" value="1" name="status" class="btn btn-sm btn-outline-success">
                            موافقة ✓
                        </button>

                    </form>

                </td>
                <td>

                    <form action="../changStatus.php" method="post">
                        <input type="hidden" name="row_id" value="<?php echo $row['id']; ?>">

                        <button type="submit" value="0" name="status" class="btn btn-sm btn-outline-danger"> ✕
                            عدم الموافقة
                        </button>
                    </form>

                </td>
            </tr>
        <?php } ?>
        </tbody>
        <tfoot>
        <tr>
            <th>الحالة</th>
            <th>بحاجة للتأكيد</th>
            <th>الطالب</th>
            <th>المعلم</th>
            <th>اسم الدورة</th>
            <th>التاريخ</th>
            <th>وقت البدء</th>
            <th>وقت الإنتهاء</th>
            <th>مدة الحصة</th>
        </tr>
        </tfoot>
    </table>

</div>


<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

<script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap4.min.js"></script>


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
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "الكل"]],
            "order": [[0, 'desc']],
            serverSide: false,
            dom: 'Bfrtip',
            buttons: [
                'excel', 'print', {
                    text: 'PDF',
                    action: function (e, dt, button, config) {
                        // Get the table headers, skipping the last two
                        let headers = [];
                        $('#report_table thead th').each(function (index) {
                            if (index < 9) { // Assuming there are 7 columns, change this if different
                                headers.push($(this).text());
                            }
                        });

                        // Get the table data, skipping the last two columns
                        let data = [];
                        dt.rows({search: 'applied'}).every(function () {
                            let row = [];
                            $(this.node()).find('td').each(function (index) {
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

        $('#lengthMenu').on('change', function () {
            let length = $(this).val();
            table.page.len(length).draw();
        });
    });
</script>
