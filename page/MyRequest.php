<?php require 'header.php';
require '../dbconnection.php';


?>


<div class="">

    <div class="container-fluid text-right" style="font-family: 'Cairo' ">


        <table id="dt-filter-search" class="table" cellspacing="0" width="100%">
            <thead>
            <tr>
                <th class="th-sm">اسم الطالب</th>
                <th class="th-sm">اسم المعلم</th>
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
            foreach ($approvedSessions as $row){
            ?>
            <tr>

                <td>
                    <?php echo $row['sname']; ?>
                </td>
                <td>
                    <?php echo $row['tname']; ?>
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
                <th>اسم الطالب
                </th>
                <th>اسم المعلم
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


        $(document).ready(function () {
            $('#dt-filter-search').dataTable({

                initComplete: function () {
                    this.api().columns().every(function () {
                        var column = this;
                        var search = $(`<input class="form-control form-control-sm" type="text" placeholder="بحث">`)
                            .appendTo($(column.footer()).empty())
                            .on('change input', function () {
                                var val = $(this).val()

                                column
                                    .search(val ? val : '', true, false)
                                    .draw();
                            });

                    });
                }
            });
        });


    </script>





