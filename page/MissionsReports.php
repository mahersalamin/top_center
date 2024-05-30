<?php error_reporting(0);
require 'header.php';
require '../dbconnection.php';
?>
<div class="b-example-divider">
    <br>
    <br>
    <div class="container-fluid" style="font-family: 'Cairo' ">
        <div class="card shadow mb-4 mt-3 rounded">
            <div class="card-header">
                <h6 class="text-primary">Report All Attendance</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="example" class="table table-bordered text-right" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th>الحالة</th>
                            <th>الطالب</th>
                            <th>المعلم</th>
                            <th>التاريخ</th>
                            <th>وقت البدء</th>
                            <th>وقت الإنتهاء</th>
                            <th>مدة الحصة</th>
                            <th>السعر</th>
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
                                    <form action="../changStatus.php" method="post">
                                        <!-- Hidden input field to send the row ID to the processing script -->
                                        <input type="hidden" name="row_id" value="<?php echo $row['id']; ?>">
                                        <!-- Display the current approve status -->
                                        <?php $approveStatus = $row['aprove'] == 1 ? 'موافق' : 'غير موافق'; ?>
                                        <p>الحالة: <?php echo $approveStatus; ?></p>
                                        <!-- Display the button to toggle the approve status -->
                                        <button type="submit" value="1" name="status" class="btn btn-outline-success">
                                            موافقة ✓
                                        </button>
                                        ||
                                        <button type="submit" value="0" name="status" class="btn btn-outline-danger"> ✕
                                            عدم الموافقة
                                        </button>
                                    </form>
                                </td>
                                <td>

                                    <?php echo "  ", $row['sname']; ?>
                                </td>
                                <td><?php echo $row['tname']; ?></td>
                                <td><?php echo $row['date']; ?></td>
                                <td><?php echo $row['enter']; ?></td>
                                <td><?php echo $row['exit']; ?></td>
                                <td><?php echo $row['total']; ?></td>
                                <td><?php echo $row['price']; ?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>





<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.bootstrap4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.colVis.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap4.min.js"></script>
<script>
    $(document).ready(function () {
        var table = $('#example').DataTable({
            lengthChange: false,
            buttons: [
                'copy', 'excel', 'csv', {
                    extend: 'pdfHtml5',
                    text: 'PDF',
                    filename: 'Attendance_Report',
                    title: 'Attendance Report',
                    exportOptions: {
                        columns: ':visible'
                    },
                    customize: function (doc) {
                        // Specify the font paths for all styles
                        var cairoFonts = {
                            normal: '../Cairo/static/Cairo-Regular.ttf',
                            bold: '../Cairo/static/Cairo-Bold.ttf',
                            italic: '../Cairo/static/Cairo-Regular.ttf', // You can change this if there's a separate italic font file
                            bolditalic: '../Cairo/static/Cairo-Bold.ttf',
                            light: '../Cairo/static/Cairo-Light.ttf',
                            extralight: '../Cairo/static/Cairo-ExtraLight.ttf',
                            medium: '../Cairo/static/Cairo-Medium.ttf',
                            semibold: '../Cairo/static/Cairo-SemiBold.ttf',
                            extrabold: '../Cairo/static/Cairo-ExtraBold.ttf',
                            black: '../Cairo/static/Cairo-Black.ttf'
                        };
                        doc.defaultStyle.font = 'Cairo';

                        // Add Cairo font to the font dictionary
                        doc.fonts = {
                            Cairo: cairoFonts
                        };
                        // Update font in styles
                        doc.content[1].table.body.forEach(function (row) {
                            row.forEach(function (cell) {
                                cell.font = 'Cairo';
                            });
                        });
                    }
                },
                'colvis'
            ]
        });

        table.buttons().container()
            .appendTo('#example_wrapper .col-md-6:eq(0)');
    });
</script>
