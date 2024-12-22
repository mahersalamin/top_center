<?php
require_once "MyDB.php";
require_once __DIR__ . '/vendor/autoload.php';
$companyName = 'مركز القمة التعليمي';
$customerName = 'تقرير الدورات'; // Example customer name, you can dynamically set this
$companyPhone1 = "0569204082";
$companyPhone2 = "02-2750628";
$logoPath = './upload/top_logo.jpg';
$companyAddress = 'بيت لحم / شارع القدس الخليل / مجمع ابو سرور ط2';
$date = date('d/m/Y');

use Mpdf\Mpdf;

function generate_income_report($headers, $tableData): string
{
    global $companyName, $companyAddress, $companyPhone1, $companyPhone2, $logoPath;
    $htmlTable = '<table border="1" style="width: 100%; border-collapse: collapse;">';
    $htmlTable .= '<thead><tr>';
    foreach ($headers as $header) {
        $htmlTable .= '<th>' . htmlspecialchars($header, ENT_QUOTES, 'UTF-8') . '</th>';
    }
    $htmlTable .= '</tr></thead><tbody>';
    foreach ($tableData as $row) {
        $htmlTable .= '<tr>';
        foreach ($row as $cell) {
            $htmlTable .= '<td>' . htmlspecialchars($cell, ENT_QUOTES, 'UTF-8') . '</td>';
        }
        $htmlTable .= '</tr>';
    }
    $htmlTable .= '</tbody></table>';

    // Define the header HTML
    $date = date('Y-m-d');

    $issuer = 'السكرتيرة'; // Example issuer, you can dynamically set this

    $customerName = 'اسم الطالب'; // Example customer name, you can dynamically set this




    $html = '
        <div style="direction: rtl; text-align: center; padding: 20px;">
        <img src="' . $logoPath . '" style="width: 200px; height: auto; display: block; margin: 0 auto;">
            <div class="receipt-header">
                <div style="float: left; width: 50%;">
                    <h5>' . $customerName . '</h5>

                </div>
                <div style="float: right; width: 50%; text-align: right;">
                    <h5>' . $companyName . '</h5>
                    <p style="direction: ltr">' . $companyPhone1 . '<i class="fa fa-phone"></i></p>
                    <p style="direction: ltr">' . $companyPhone2 . '<i class="fa fa-phone"></i></p>
                    <p> ' . $companyAddress . '<i class="fa fa-location-arrow"></i></p>
                </div>
                <div style="clear: both;"></div>
            </div>



            ' . $htmlTable . '

            <div class="receipt-footer" style="padding: 20px 0;">
                <div style="float: right; width: 20%;">
                    <p><b>التاريخ: </b> ' . $date . '</p>

                </div>
                <div style="float: left; width: 20%;">
                    <p><b>' . $issuer . '</b> </p>

                </div>


            </div>
        </div>
    ';

    return $html;
}

function generate_outcome_report($headers, $tableData): string
{
    global $companyName, $companyAddress, $companyPhone1, $companyPhone2, $logoPath;
    $htmlTable = '<table border="1" style="width: 100%; border-collapse: collapse;">';
    $htmlTable .= '<thead><tr>';
    foreach ($headers as $header) {
        $htmlTable .= '<th>' . htmlspecialchars($header, ENT_QUOTES, 'UTF-8') . '</th>';
    }
    $htmlTable .= '</tr></thead><tbody>';
    foreach ($tableData as $row) {
        $htmlTable .= '<tr>';
        foreach ($row as $cell) {
            $htmlTable .= '<td>' . htmlspecialchars($cell, ENT_QUOTES, 'UTF-8') . '</td>';
        }
        $htmlTable .= '</tr>';
    }
    $htmlTable .= '</tbody></table>';

    // Define the header HTML
    $date = date('Y-m-d');

    $issuer = 'السكرتيرة';





    $html = '
        <div style="direction: rtl; text-align: center; padding: 20px;">
        <img src="' . $logoPath . '" style="width: 200px; height: auto; display: block; margin: 0 auto;">
            <div class="receipt-header">
            
                
                <div style="float: right; width: 50%; text-align: right;">
                    <h5>' . $companyName . '</h5>
                    <p style="direction: ltr">' . $companyPhone1 . '<i class="fa fa-phone"></i></p>
                    <p style="direction: ltr">' . $companyPhone2 . '<i class="fa fa-phone"></i></p>
                    <p> ' . $companyAddress . '<i class="fa fa-location-arrow"></i></p>
                </div>
                <div style="clear: both;"></div>
            </div>



            ' . $htmlTable . '

            <div class="receipt-footer" style="padding: 20px 0;">
                <div style="float: right; width: 20%;">
                    <p><b>التاريخ: </b> ' . $date . '</p>

                </div>
                <div style="float: left; width: 20%;">
                    <p><b>' . $issuer . '</b> </p>

                </div>


            </div>
        </div>
    ';

    return $html;
}

function generate_daily_report($headers, $tableData): string{
    global $companyName, $companyPhone1, $companyPhone2, $companyAddress, $logoPath;
    $htmlTable = '<table border="1" style="width: 100%; border-collapse: collapse;">';
    $htmlTable .= '<thead><tr>';
    foreach ($headers as $header) {
        $htmlTable .= '<th>' . htmlspecialchars($header, ENT_QUOTES, 'UTF-8') . '</th>';
    }
    $htmlTable .= '</tr></thead><tbody>';
    foreach ($tableData as $row) {
        $htmlTable .= '<tr>';
        foreach ($row as $cell) {
            $htmlTable .= '<td>' . htmlspecialchars($cell, ENT_QUOTES, 'UTF-8') . '</td>';
        }
        $htmlTable .= '</tr>';
    }
    $htmlTable .= '</tbody></table>';

    // Define the header HTML
    $date = date('Y-m-d');

    $issuer = 'السكرتيرة'; // Example issuer, you can dynamically set this
    $customerName = 'اسم الطالب'; // Example customer name, you can dynamically set this


    $html = '
        <div style="direction: rtl; text-align: center; padding: 20px;">
        <img src="' . $logoPath . '" style="width: 200px; height: auto; display: block; margin: 0 auto;">
            <div class="receipt-header">

                
                <div style="float: right; width: 50%; text-align: right;">
                    <h5>' . $companyName . '</h5>
                    <p style="direction: ltr">' . $companyPhone1 . '<i class="fa fa-phone"></i></p>
                    <p style="direction: ltr">' . $companyPhone2 . '<i class="fa fa-phone"></i></p>
                    <p> ' . $companyAddress . '<i class="fa fa-location-arrow"></i></p>
                </div>
                <div style="clear: both;"></div>
            </div>



            ' . $htmlTable . '

            <div class="receipt-footer" style="padding: 20px 0;">
                <div style="float: right; width: 20%;">
                    <p><b>التاريخ: </b> ' . $date . '</p>

                </div>
                <div style="float: left; width: 20%;">
                    <p><b>' . $issuer . '</b> </p>

                </div>


            </div>
        </div>
    ';

    return $html;
}

function generate_public_report($headers, $tableData): string {
    global $companyName, $companyAddress, $logoPath, $companyPhone1, $companyPhone2;
    $htmlTable = '<table border="1" style="width: 100%; border-collapse: collapse;">';
    $htmlTable .= '<thead><tr>';
    foreach ($headers as $header) {
        $htmlTable .= '<th>' . htmlspecialchars($header, ENT_QUOTES, 'UTF-8') . '</th>';
    }
    $htmlTable .= '</tr></thead><tbody>';
    foreach ($tableData as $row) {
        $htmlTable .= '<tr>';
        foreach ($row as $cell) {
            $htmlTable .= '<td>' . htmlspecialchars($cell, ENT_QUOTES, 'UTF-8') . '</td>';
        }
        $htmlTable .= '</tr>';
    }
    $htmlTable .= '</tbody></table>';

    // Define the header HTML
    $date = date('Y-m-d');

    $issuer = 'السكرتيرة'; // Example issuer, you can dynamically set this
    $customerName = 'التقرير الشامل'; // Example customer name, you can dynamically set this


    $html = '
        <div style="direction: rtl; text-align: center; padding: 20px;">
        <img src="' . $logoPath . '" style="width: 200px; height: auto; display: block; margin: 0 auto;">
            <div class="receipt-header">
                <div style="float: left; width: 50%;">
                    <h5>' . $customerName . '</h5>

                </div>
                <div style="float: right; width: 50%; text-align: right;">
                    <h5>' . $companyName . '</h5>
                    <p style="direction: ltr">'.$companyPhone1.'<i class="fa fa-phone"></i></p>
                    <p style="direction: ltr">'.$companyPhone2.'<i class="fa fa-phone"></i></p>
                    <p> ' . $companyAddress . '<i class="fa fa-location-arrow"></i></p>
                </div>
                <div style="clear: both;"></div>
            </div>



            ' . $htmlTable . '

            <div class="receipt-footer" style="padding: 20px 0;">
                <div style="float: right; width: 20%;">
                    <p><b>التاريخ: </b> ' . $date . '</p>

                </div>
                <div style="float: left; width: 20%;">
                    <p><b>' . $issuer . '</b> </p>

                </div>


            </div>
        </div>
    ';

    return $html;
}
function generate_sessions_report($headers, $tableData): string {
    global $customerName, $companyName, $companyPhone1, $companyPhone2, $companyAddress, $companyAddress, $logoPath;
    $htmlTable = '<table border="1" style="width: 100%; border-collapse: collapse;">';
    $htmlTable .= '<thead><tr>';
    foreach ($headers as $header) {
        $htmlTable .= '<th>' . htmlspecialchars($header, ENT_QUOTES, 'UTF-8') . '</th>';
    }
    $htmlTable .= '</tr></thead><tbody>';
    foreach ($tableData as $row) {
        $htmlTable .= '<tr>';
        foreach ($row as $cell) {
            $htmlTable .= '<td>' . htmlspecialchars($cell, ENT_QUOTES, 'UTF-8') . '</td>';
        }
        $htmlTable .= '</tr>';
    }
    $htmlTable .= '</tbody></table>';

    // Define the header HTML
    $date = date('Y-m-d');

    $issuer = 'السكرتيرة'; // Example issuer, you can dynamically set this


    $html = '
        <div style="direction: rtl; text-align: center; padding: 20px;">
        <img src="' . $logoPath . '" style="width: 200px; height: auto; display: block; margin: 0 auto;">
            <div class="receipt-header">
                <div style="float: left; width: 50%;">
                    <h5>' . $customerName . '</h5>
                    <p>التاريخ: ' . $date . '</p>

                </div>
                <div style="float: right; width: 50%; text-align: right;">
                    <h5>' . $companyName . '</h5>
                    <p style="direction: ltr">' . $companyPhone1 . '<i class="fa fa-phone"></i></p>
                    <p style="direction: ltr">' . $companyPhone2 . '<i class="fa fa-phone"></i></p>
                    <p> ' . $companyAddress . '<i class="fa fa-location-arrow"></i></p>
                </div>
                <div style="clear: both;"></div>
            </div>



            ' . $htmlTable . '

            <div class="receipt-footer" style="padding: 20px 0;">
              
                <div style="float: left; width: 20%;">
                    <p><b>' . $issuer . '</b> </p>

                </div>


            </div>
        </div>
    ';

    return $html;
}

function generate_receipt($tableData): string {

    global $companyName, $companyAddress, $companyPhone1, $companyPhone2, $logoPath;
    return "
    <html>
        <head>
            <style>
                body {
                    direction: rtl;
                    text-align: right;
                    font-family: 'Cairo', sans-serif;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                }
                th, td {
                    border: 1px solid #ddd;
                    padding: 8px;
                }
                th {
                    background-color: #f2f2f2;
                }
                h1 {
                    text-align: center;
                }
                .header {
                    text-align: center;
                    margin-bottom: 20px;
                }
                .signature {
                    margin-top: 30px;
                    border-top: 1px solid #000;
                    text-align: right;
                    padding-top: 10px;
                }
            </style>
        </head>
        <body>
            <div class='header'>
            <img src='{$logoPath}' style='width: 150px; height: auto; display: block; margin: 0 auto;'>
                <h1>وصل دفع</h1>
                <p><strong>اسم الشركة:</strong> {$companyName}</p>
                <p><strong>عنوان الشركة:</strong> {$companyAddress}</p>
                <p><strong>رقم هاتف الشركة:</strong> {$companyPhone1}</p>
                <p><strong>رقم هاتف الشركة:</strong> {$companyPhone2}</p>

            </div>
            <table>
                <tr>
                    <td>التاريخ</td>
                    <td>{$tableData['date']}</td>
                </tr>
                <tr>
                    <td>المستلم</td>
                    <td>{$tableData['cashier']}</td>
                </tr>
                <tr>
                    <td>الدافع</td>
                    <td>{$tableData['payer']}</td>
                </tr>
                <tr>
                    <td>الطالب</td>
                    <td>{$tableData['student_name']}</td>
                </tr>
                <tr>
                    <td>الدورة</td>
                    <td>{$tableData['session_name']}</td>
                </tr>
                <tr>
                    <td>المبلغ المدفوع</td>
                    <td>{$tableData['amount']}</td>
                </tr>
                <tr>
                    <td>مجموع الدفعات</td>
                    <td>{$tableData['total_payments']}</td>
                </tr>
                <tr>
                    <td>المبلغ الأصلي</td>
                    <td>{$tableData['session_cost']}</td>
                </tr>
                <tr>
                    <td>حالة الدفع</td>
                    <td>{$tableData['payment_status']}</td>
                </tr>
                <tr>
                    <td>ملاحظات</td>
                    <td>{$tableData['notes']}</td>
                </tr>
            </table>
            <div class='signature'>
                <p>التوقيع: ...........................................</p>
                <p>السكرتيرة: ...........................................</p>
            </div>
        </body>
    </html>
    ";
}
function generate_outcome_receipt($tableData): string {

    global $companyPhone1, $companyAddress, $companyPhone2, $companyName, $logoPath;
    return "
    <html>
        <head>
            <style>
                body {
                    direction: rtl;
                    text-align: right;
                    font-family: 'Cairo', sans-serif;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                }
                th, td {
                    border: 1px solid #ddd;
                    padding: 8px;
                }
                th {
                    background-color: #f2f2f2;
                }
                h1 {
                    text-align: center;
                }
                .header {
                    text-align: center;
                    margin-bottom: 20px;
                }
                .signature {
                    margin-top: 30px;
                    border-top: 1px solid #000;
                    text-align: right;
                    padding-top: 10px;
                }
            </style>
        </head>
        <body>
            <div class='header'>
            <img src='  $logoPath  ' style='width: 100px; height: auto; display: block; margin: 0 auto;'>
                <h1>وصل دفع</h1>
                <p><strong>اسم الشركة:</strong> {$companyName}</p>
                <p><strong>عنوان الشركة:</strong> {$companyAddress}</p>
                <p><strong>رقم هاتف الشركة:</strong> {$companyPhone1}</p>
                <p><strong>رقم هاتف الشركة:</strong> {$companyPhone2}</p>
            </div>
            <table>
                <tr>
                    <td>التاريخ</td>
                    <td>{$tableData['date']}</td>
                </tr>
                <tr>
                    <td>المستلم</td>
                    <td>{$tableData['cashier']}</td>
                </tr>
                <tr>
                    <td>الدافع</td>
                    <td>{$tableData['payer']}</td>
                </tr>
                <tr>
                    <td>الطالب</td>
                    <td>{$tableData['student_name']}</td>
                </tr>
                <tr>
                    <td>الدورة</td>
                    <td>{$tableData['session_name']}</td>
                </tr>
                <tr>
                    <td>المبلغ المدفوع</td>
                    <td>{$tableData['amount']}</td>
                </tr>
                <tr>
                    <td>مجموع الدفعات</td>
                    <td>{$tableData['total_payments']}</td>
                </tr>
                <tr>
                    <td>المبلغ المطلوب</td>
                    <td>{$tableData['session_cost']}</td>
                </tr>
                <tr>
                    <td>حالة الدفع</td>
                    <td>{$tableData['payment_status']}</td>
                </tr>
                <tr>
                    <td>ملاحظات</td>
                    <td>{$tableData['notes']}</td>
                </tr>
            </table>
            <div class='signature'>
                <p>التوقيع: ...........................................</p>
                <p>السكرتيرة: ...........................................</p>
            </div>
        </body>
    </html>
    ";
}
function generate_remains_report($headers, $tableData): string {
    global $companyPhone1, $companyAddress, $companyPhone2, $companyName, $logoPath;



    $htmlTable = '<table border="1" style="width: 100%; border-collapse: collapse;">';
    $htmlTable .= '<thead><tr>';
    foreach ($headers as $header) {
        $htmlTable .= '<th>' . htmlspecialchars($header, ENT_QUOTES, 'UTF-8') . '</th>';
    }
    $htmlTable .= '</tr></thead><tbody>';
    foreach ($tableData as $row) {
        $htmlTable .= '<tr>';
        foreach ($row as $cell) {
            $htmlTable .= '<td>' . htmlspecialchars($cell, ENT_QUOTES, 'UTF-8') . '</td>';
        }
        $htmlTable .= '</tr>';
    }
    $htmlTable .= '</tbody></table>';

    // Define the header HTML
    $date = date('Y-m-d');

    $issuer = 'السكرتيرة'; // Example issuer, you can dynamically set this





    $html = '
        <div style="direction: rtl; text-align: center; padding: 20px;">
        <img src="' . $logoPath . '" style="width: 200px; height: auto; display: block; margin: 0 auto;">
            <div class="receipt-header">
            
                
                <div style="float: right; width: 50%; text-align: right;">
                    <h5>' . $companyName . '</h5>
                    <p style="direction: ltr">' . $companyPhone1 . '<i class="fa fa-phone"></i></p>
                    <p style="direction: ltr">' . $companyPhone2 . '<i class="fa fa-phone"></i></p>
                    <p> ' . $companyAddress . '<i class="fa fa-location-arrow"></i></p>
                </div>
                <div style="clear: both;"></div>
            </div>



            ' . $htmlTable . '

            <div class="receipt-footer" style="padding: 20px 0;">
                <div style="float: right; width: 20%;">
                    <p><b>التاريخ: </b> ' . $date . '</p>

                </div>
                <div style="float: left; width: 20%;">
                    <p><b>' . $issuer . '</b> </p>

                </div>


            </div>
        </div>
    ';

    return $html;
}

function generateSecretaryTimesheetReport($month, $year) {
    // Assuming you have a database connection $db already
    $db = new MyDB();
    $conn = $db->connect();
    global $companyPhone1, $companyAddress, $companyPhone2, $companyName, $logoPath;

    // Step 1: Fetch data from secretary_timesheet based on the selected month and year
    $query = "SELECT * FROM secretary_timesheet WHERE MONTH(login_datetime) = ? AND YEAR(login_datetime) = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $month, $year);
    $stmt->execute();
    $result = $stmt->get_result();




    // Step 3: Prepare the HTML content for the PDF
    $html = '<html>';
    $html .= '<head>';
    $html .= '<style>';
    $html .= 'body { direction: rtl; text-align: right; font-family: "Cairo", sans-serif; }';
    $html .= 'table { width: 100%; border-collapse: collapse; }';
    $html .= 'th, td { border: 1px solid #ddd; padding: 8px; }';
    $html .= 'th { background-color: #f2f2f2; }';
    $html .= 'h1 { text-align: center; }';
    $html .= '.header { text-align: center; margin-bottom: 20px; }';
    $html .= '.signature { margin-top: 30px; border-top: 1px solid #000; text-align: right; padding-top: 10px; }';
    $html .= '</style>';
    $html .= '</head>';
    $html .= '<body>';

    // Header with company details and logo
    $html .= '<div class="header">';
    $html .= "<img src='{$logoPath}' style='width: 150px; height: auto; display: block; margin: 0 auto;'>";
    $html .= '<h1>تقرير دوام السكرتيرة</h1>';
    $html .= "<p><strong>اسم الشركة:</strong> {$companyName}</p>";
    $html .= "<p><strong>عنوان الشركة:</strong> {$companyAddress}</p>";
    $html .= "<p><strong>رقم هاتف الشركة:</strong> {$companyPhone1}</p>";
    $html .= "<p><strong>رقم هاتف الشركة:</strong> {$companyPhone2}</p>";
    $html .= '</div>';

    // Report header
    $html .= '<p><strong>الشهر:</strong> ' . $month . ' <strong>السنة:</strong> ' . $year . '</p>';

    // Step 5: Table with timesheet data
    $html .= '<table>';
    $html .= '<thead>';
    $html .= '<tr>';
    $html .= '<th style="text-align: center;">#</th>';
    $html .= '<th style="text-align: center;">تاريخ الدخول</th>';
    $html .= '<th style="text-align: center;">تاريخ الخروج</th>';
    $html .= '</tr>';
    $html .= '</thead>';
    $html .= '<tbody>';

    // Loop through the timesheet data and generate the table rows
    $counter = 1;
    while ($row = $result->fetch_assoc()) {
        $loginTime = date("d-m-Y H:i", strtotime($row['login_datetime']));
        $logoutTime = date("d-m-Y H:i", strtotime($row['logout_datetime']));

        $html .= '<tr>';
        $html .= '<td style="text-align: center;">' . $counter++ . '</td>';
        $html .= '<td style="text-align: center;">' . $loginTime . '</td>';
        $html .= '<td style="text-align: center;">' . $logoutTime . '</td>';
        $html .= '</tr>';
    }

    $html .= '</tbody>';
    $html .= '</table>';

    // Close the HTML content
    $html .= '</div>';

    return $html;
}

try {

    $type = $_POST['reportType'];
    $htmlContent = $_POST['htmlContent'] ?? '';
    $headers = '';
    $tableData = '';
    if (isset($_POST['headers'])) {
        $headers = json_decode($_POST['headers'], true);
    }
    if (isset($_POST['tableData'])) {
        $tableData = json_decode($_POST['tableData'], true);
    }

    $date = date('d-m-Y');

    switch ($type) {
        case 'income_report':
            if (empty($headers) || empty($tableData)) {
                throw new Exception('No data received or data is invalid.');
            }
            $inc_report = generate_income_report($headers, $tableData);
            // Initialize mPDF and set the HTML content

            $mpdf = new Mpdf(['default_font' => 'Cairo']);
            $mpdf->WriteHTML($inc_report);

            $mpdf->Output('income_report_' . $date . '_.pdf', 'D');
            break;
        case 'outcome_report':
            if (empty($headers) || empty($tableData)) {
                throw new Exception('لم يتم ارسال بيانات او البيانات غير صالحة');
            }

            $outcome_report = generate_outcome_report($headers, $tableData);

            $mpdf = new Mpdf(['default_font' => 'Cairo']);
            $mpdf->WriteHTML($outcome_report);

            $mpdf->Output('outcome_report_' . $date . '_.pdf', 'D');
            break;
        case 'all_stats_report':

            if (empty($htmlContent)) {
                throw new Exception("No HTML content provided.");
            }

            $mpdf = new \Mpdf\Mpdf([
                'default_font' => 'Cairo',
                'mode' => 'utf-8',
                'format' => 'A4',
                'margin_left' => 15,
                'margin_right' => 15,
                'margin_top' => 16,
                'margin_bottom' => 16,
                'margin_header' => 9,
                'margin_footer' => 9
            ]);
            $htmlContent = '
                <div style="direction: rtl; text-align: center; padding: 20px;">
                        <div class="receipt-header">

                            <div style="float: right; width: 50%; text-align: right;">
                                <h5>' . $companyName . '</h5>

                           <p> ' . $companyAddress . '<i class="fa fa-location-arrow"></i></p>
                            </div>
                            <div style="float: left; width: 50%; text-align: right;">
                                                         <p style="direction: ltr">'.$companyPhone1.'<i class="fa fa-phone"></i></p>
                    <p style="direction: ltr">'.$companyPhone2.'<i class="fa fa-phone"></i></p>
                            </div>
                            <div style="clear: both;"></div>
                        </div>' . $htmlContent . '<div class="receipt-footer" style="padding: 20px 0;">
                            <div style="float: right; width: 20%;">
                                <p><b>التاريخ: </b> ' . $date . '</p>

                            </div>
                            <div style="float: left; width: 20%;">
                                <p><b> الإدارة </b> </p>

                            </div>


                        </div>
                 </div>'
            ;


            $mpdf->WriteHTML($htmlContent);
            // Output PDF directly to browser for download
            $mpdf->Output('all_stat_report_' . $date . '_.pdf', 'D');
            break;
        case 'daily_report':
            if (empty($headers) || empty($tableData)) {
                throw new Exception('لم يتم ارسال بيانات او البيانات غير صالحة');
            }
            $daily_report = generate_daily_report($headers, $tableData);
            $mpdf = new Mpdf(['default_font' => 'Cairo']);
            $mpdf->WriteHTML($daily_report);
            $mpdf->Output('daily_report_' . $date . '_.pdf', 'D');
            break;
        case 'public_report':
            if (empty($headers) || empty($tableData)) {
                throw new Exception('لم يتم ارسال بيانات او البيانات غير صالحة');
            }
            $public_report = generate_public_report($headers, $tableData);
            $mpdf = new Mpdf(['default_font' => 'Cairo']);
            $mpdf->WriteHTML($public_report);
            $mpdf->Output('public_report_' . $date . '_.pdf', 'D');
            break;
        case 'sessions_report':
            if (empty($headers) || empty($tableData)) {
                throw new Exception('لم يتم ارسال بيانات او البيانات غير صالحة');
            }
            $sessions_report = generate_sessions_report($headers, $tableData);
            $mpdf = new Mpdf(['default_font' => 'Cairo']);
            $mpdf->WriteHTML($sessions_report);
            $mpdf->Output('sessions_report_' . $date . '_.pdf', 'D');
            break;
        case 'receipt_report':
            if (empty($tableData)) {
                throw new Exception('لم يتم ارسال بيانات او البيانات غير صالحة');
            }

            $receipt_report = generate_receipt($tableData);

            $mpdf = new Mpdf(['default_font' => 'Cairo']);
            $mpdf->WriteHTML($receipt_report);
            // Output PDF directly to the browser
            $pdfContent = $mpdf->Output('', 'S');
            if ($pdfContent === false) {
                http_response_code(500);
                echo 'فشل في إنشاء PDF';
                exit();
            }

            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="receipt.pdf"');
            header('Content-Length: ' . strlen($pdfContent)); // Set the correct content length
            echo $pdfContent;
            exit();
            break;
        case 'outcome_receipt_report':
            if (empty($tableData)) {
                throw new Exception('لم يتم ارسال بيانات او البيانات غير صالحة');
            }

            $receipt_report = generate_outcome_receipt($tableData);

            $mpdf = new Mpdf(['default_font' => 'Cairo']);
            $mpdf->WriteHTML($receipt_report);
            // Output PDF directly to the browser
            $pdfContent = $mpdf->Output('', 'S');
            if ($pdfContent === false) {
                http_response_code(500);
                echo 'فشل في إنشاء PDF';
                exit();
            }

            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="outcome_receipt.pdf"');
            header('Content-Length: ' . strlen($pdfContent)); // Set the correct content length
            echo $pdfContent;
            exit();
            break;
        case 'remains_report':
            if (empty($headers) || empty($tableData)) {
                throw new Exception('لم يتم ارسال بيانات او البيانات غير صالحة');
            }
            $remains_report = generate_remains_report($headers, $tableData);

            $mpdf = new Mpdf(['default_font' => 'Cairo']);
            $mpdf->WriteHTML($remains_report);


            $mpdf->Output('remains_report_' . $date . '_.pdf', 'D');
            break;
        case 'secretary_timesheet_report':
            if (empty($headers) || empty($tableData)) {
                throw new Exception('لم يتم ارسال بيانات او البيانات غير صالحة');
            }
            $secretary_report = generateSecretaryTimesheetReport($headers, $tableData);

            $mpdf = new Mpdf(['default_font' => 'Cairo']);
            $mpdf->WriteHTML($secretary_report);


            $mpdf->Output('secretary_report_' . $date . '_.pdf', 'D');
            break;
        default:
            throw new Exception('No data received or data is invalid.');

    }


} catch (Exception $e) {
    echo $e->getMessage();
}
