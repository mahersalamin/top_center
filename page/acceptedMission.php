<?php require 'header.php';
$db = new MyDB();
?>


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
                        عليها: <?php echo $totalPayments['total_sessions']; ?> || مجموع المبلغ
                        المدفوع: <?php echo number_format($totalPayments['total_price'], 2); ?></p></td>


            </tr>

        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>حالة الحصة</th>
                        <th>اسم الطالب</th>
                        <th>المادة</th>
                        <th>التاريخ</th>
                        <th>وقت البدء</th>
                        <th>وقت الإنتهاء</th>
                        <th>وقت الحصة</th>
                        <th>السعر</th>

                    </tr>
                    </thead>


                    <tbody>


                    <?php
                    $d = date('d/m/Y');
                    $acmi = $db->getTeacherSessions($_COOKIE['id']);
                    ?>

                    <?php
                    foreach ($acmi

                    as $row) {
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
                        <td><?php echo $row['name']; ?></td>
                        <!-- name -->
                        <td><?php echo $row['session_name']; ?></td>
                        <!-- date -->
                        <td>
                            <p style="
                                                    
                                                    display: -webkit-box;
                                                 -webkit-line-clamp: 1; /* number of lines to show */
                                                  line-clamp: 1; 
                                                 -webkit-box-orient: vertical;   
                                                  max-width: 30em; ">

                                <?php echo $row['date']; ?>
                            </p>


                        </td>
                        <!-- starting time -->
                        <td>
                            <?php echo $row['enter']; ?>
                        </td>
                        <!-- ending time -->
                        <td><?php echo $row['exit']; ?></td>
                        <!-- total -->
                        <td>
                            <?php
                            echo $row['total']
                            ?>
                        </td>
                        <td><?php echo number_format($row['price'], 2); ?></td>
                        <?php } ?>

                    </tr>


                    </tbody>
                </table>
            </div>
        </div>

    </div>

</div>
<!-- /.container-fluid -->

</div>

</div>
<!-- End of Page Wrapper -->