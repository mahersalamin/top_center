<?php require 'header.php'; // Include the header

$db = new MyDB();

$attendances = $db->dailyReport();


?>

<div class="container m-5 text-right">

    <div class="row">
        <div class="col-md-12" style="direction: rtl; text-align: right;">
            <h1>رسومات بيانية للواردات</h1>


            <div class="col-md-12" style="direction: rtl; text-align: right;">
                <form method="GET" target="_blank" action="../generated_graphs/monthly.php">
                    <div class="row align-items-end">
                        <div class="col-md-8">
                            <label class="form-label" for="month">الشهر:</label>
                            <select class="form-control form-control-sm" id="month" name="month">
                                <?php
                                $months = [
                                    '01' => 'January',
                                    '02' => 'February',
                                    '03' => 'March',
                                    '04' => 'April',
                                    '05' => 'May',
                                    '06' => 'June',
                                    '07' => 'July',
                                    '08' => 'August',
                                    '09' => 'September',
                                    '10' => 'October',
                                    '11' => 'November',
                                    '12' => 'December'
                                ];
                                foreach ($months as $key => $value) {
                                    echo "<option value=\"$key\">$value</option>";
                                }
                                ?>
                            </select>

                            <label class="form-label" for="year">السنة:</label>
                            <input type="number" class="form-control mb-4" id="year" name="year"
                                   value="<?php echo date('Y'); ?>"/>
                        </div>
                        <div class="col-md-4">
                            <input type="submit" class="btn btn-primary" value="إنشاء رسم بياني شهري"/>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-12" style="direction: rtl; text-align: right;">
                <form method="GET" target="_blank" action="../generated_graphs/yearly.php">
                    <div class="row align-items-end">
                        <div class="col-md-8">
                            <label class="form-label" for="year">السنة:</label>
                            <input type="number" class="form-control mb-4" id="year" name="year"
                                   value="<?php echo date('Y'); ?>"/>
                        </div>
                        <div class="col-md-4">
                            <input type="submit" class="btn btn-primary" value="إنشاء رسم بياني سنوي"/>
                        </div>
                    </div>
                </form>
            </div>

        </div>
        <hr class="mt-5">
        <div class="col-md-12" style="direction: rtl; text-align: right;">
            <h1>رسومات بيانية للصادرات</h1>

            <div class="col-md-12" style="direction: rtl; text-align: right;">
                <form method="GET" target="_blank" action="../generated_graphs/monthly_outcome.php">
                    <div class="row align-items-end">
                        <div class="col-md-8">
                            <label class="form-label" for="month">الشهر:</label>
                            <select class="form-control form-control-sm" id="month" name="month">
                                <?php
                                $months = [
                                    '01' => 'January',
                                    '02' => 'February',
                                    '03' => 'March',
                                    '04' => 'April',
                                    '05' => 'May',
                                    '06' => 'June',
                                    '07' => 'July',
                                    '08' => 'August',
                                    '09' => 'September',
                                    '10' => 'October',
                                    '11' => 'November',
                                    '12' => 'December'
                                ];
                                foreach ($months as $key => $value) {
                                    echo "<option value=\"$key\">$value</option>";
                                }
                                ?>
                            </select>

                            <label class="form-label" for="year">السنة:</label>
                            <input type="number" class="form-control mb-4" id="year" name="year"
                                   value="<?php echo date('Y'); ?>"/>
                        </div>
                        <div class="col-md-4">
                            <input type="submit" class="btn btn-primary" value="إنشاء رسم بياني شهري"/>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-12" style="direction: rtl; text-align: right;">
                <form method="GET" target="_blank" action="../generated_graphs/yearly_outcome.php">
                    <div class="row align-items-end">
                        <div class="col-md-8">
                            <label class="form-label" for="year">السنة:</label>
                            <input type="number" class="form-control mb-4" id="year" name="year"
                                   value="<?php echo date('Y'); ?>"/>
                        </div>
                        <div class="col-md-4">
                            <input type="submit" class="btn btn-primary" value="إنشاء رسم بياني سنوي"/>
                        </div>
                    </div>
                </form>
            </div>
        </div>


    </div>
</div>


<?php require 'footer.php'; // Include the footer ?>
