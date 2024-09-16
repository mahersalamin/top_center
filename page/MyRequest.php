<?php require 'header.php';
require '../dbconnection.php';


?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<!-- DataTables Buttons JS -->
<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>
<!-- JSZip for Excel export -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<style>
    table.dataTable thead th,
    table.dataTable thead td,
    table.dataTable tfoot th,
    table.dataTable tfoot td {
        text-align: center;
    }
</style>

<div class="">

    <div class="container-fluid text-right" style="font-family: 'Cairo' ">
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

        <table id="dt-filter-search" class="table" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th class="th-sm">الرقم</th>
                    <th class="th-sm">اسم الطالب</th>
                    <th class="th-sm">اسم المعلم</th>
                    <th class="th-sm">الدورة</th>
                    <th class="th-sm">التاريخ</th>
                    <th class="th-sm">وقت البدء</th>
                    <th class="th-sm">وقت الإنتهاء</th>
                    <th class="th-sm">مدة الحصة</th>
                </tr>
            </thead>
            <tbody>

                <?php
                $db = new MyDB();
                $d = date('d/m/Y');
                $approvedSessions = $db->getApprovedAttendances();
                foreach ($approvedSessions as $row) {
                ?>
                    <tr>

                        <td>
                            <?php echo $row['id']; ?>
                        </td>
                        <td>
                            <?php echo $row['sname']; ?>
                        </td>
                        <td>
                            <?php echo $row['tname']; ?>
                        </td>
                        <td>
                            <?php echo $row['session_name']; ?>
                        </td>
                        <td>
                            <?php echo $row['date']; ?>
                        </td>
                        <td>
                            <?php echo $row['enter']; ?>
                        </td>

                        <td>
                            <?php echo $row['exit']; ?>
                        </td>
                        <td>
                            <?php echo $row['total']; ?>
                        </td>


                    <?php } ?>

                    </tr>

            </tbody>
            <tfoot>
                <tr>
                    <th>الرقم</th>
                    <th>اسم الطالب
                    </th>
                    <th>اسم المعلم
                    </th>
                    <th>الدورة
                    </th>
                    <th>تاريخ الحصة
                    </th>
                    <th>وقت البدء
                    </th>
                    <th>وقت الإنتهاء
                    </th>
                    <th>مدة الحصة
                    </th>
                </tr>
            </tfoot>
        </table>

    </div>


    <script>
        $(document).ready(function() {
            let table = $('#dt-filter-search').DataTable({
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
                "lengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, "الكل"]
                ],
                "order": [
                    [0, 'desc']
                ],
                serverSide: false,
                dom: 'Bfrtip',
                buttons: [
                    'excel', 'print', {
                        text: 'PDF',
                        action: function(e, dt, button, config) {
                            // Get the table headers, skipping the last two
                            let headers = [];
                            $('#dt-filter-search thead th').each(function(index) {
                                if (index < 9) { // Assuming there are 7 columns, change this if different
                                    headers.push($(this).text());
                                }
                            });
                            console.log(headers);
                            
                            // Get the table data, skipping the last two columns
                            let data = [];
                            dt.rows({
                                search: 'applied'
                            }).every(function() {
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

                initComplete: function() {
                    this.api().columns().every(function() {
                        let column = this;
                        let search = $(`<input class="form-control form-control-sm" type="text" placeholder="بحث">`)
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
        });
    </script>