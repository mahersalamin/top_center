<?php require 'header.php';
$db = new MyDB();
?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>


<div class="container-fluid ">


    <div class="card shadow mb-4 mt-3 p-3 rounded  text-right">
        <div class="card-header py-3">
            <h4 class="m-0 font-weight-bold text-primary">تقرير الدورات</h4>


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
                            <th>اسم الدورة</th>
                            <th>تصنيف الدورة</th>
                            <th>الحالة</th>
                            <th>المبلغ المدفوع</th>
                            <th>المستحقات</th>
                            <th>النسبة</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $d = date('d/m/Y');
                    $data = $db->getTeacherSessions($_COOKIE['id']);

                     foreach ($data as $row) {
                    ?>
                    <tr>
                        <td><?= $row['session_name'] ?></td>
                        <td><?= $row['type']; ?></td>
                        <td><?php
                            switch($row['payment_status']){
                                case "not paid":
                                    print "غير مدفوعة";
                                    break;
                                case "paid":
                                    print "مدفوعة";
                                    break;
                                case "partially paid":
                                    print "مدفوعة جزئياً";
                                    break;
                                default:
                                    print "شيءُ ما خاطئ";
                            }
                            ?>
                        </td>
                        <td><?= $row['paid_amount']; ?></td>
                        <td>
                            <?= $row['session_amount']; ?>
                        </td>
                        <td><?= $row['percentage']; ?></td>



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
            serverSide: false,
            dom: 'Bfrtip',
            "order": [[0, 'desc']],
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