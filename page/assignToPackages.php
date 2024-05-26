<?php require 'header.php';


?>
<style>
    .dropdown {
        position: relative;
        display: inline-block;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        background-color: #f9f9f9;
        min-width: 250px;
        overflow-y: auto;
        border: 1px solid #ddd;
        z-index: 1;
    }

    .dropdown-content label {
        display: block;
        margin: 3px 0;
    }

    .dropdown-content input[type="checkbox"] {
        margin: 5px;
    }

    .show {
        display: block;
    }

</style>
<?php

if (!isset($_COOKIE['id'])) {
    header("location:signin.php");
}
$db = new MyDB();
$sessions = $db->fetchAllSessionsWithDetails();
$materials = $db->getSpecializations();
$students = $db->allStudents();
$teachers = $db->getAllTeachers();
?>

<div class="container  text-right">
    <?php
    if (isset($_GET['message']) && isset($_GET['status'])) {
        // Get the message and status from the query parameters
        $message = $_GET['message'];
        $status = $_GET['status'];

        // Display the message based on the status
        if ($status === "success") {
            echo '<div class="alert alert-success alert-dismissible" role="alert">' . $message . '<span type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></span>' . '</div>';
        } elseif ($status === "error") {
            echo '<div class="alert alert-danger alert-dismissible" role="alert">' . $message . '<span type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></span>' . '</div>';
        }
    }
    ?>

    <ul class="nav nav-tabs nav-pills nav-fill justify-content-center mb-4">

        <li class="nav-item">
            <a class="nav-link active" id="new-package-tab" data-toggle="tab" href="#new-package" role="tab"
               aria-controls="new-package"
               aria-selected="true">إضافة دورات جديدة</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="student-to-package-tab" data-toggle="tab" href="#student-to-package"
               role="tab"
               aria-controls="student-to-package"
               aria-selected="false">إضافة طلاب الى دورات موجودة</a>
        </li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane fade show active" id="new-package" role="tabpanel" aria-labelledby="new-package-tab">
            <form method="post" enctype="application/x-www-form-urlencoded" action="../assignPackages.php">
                <input hidden name="pkg" value="package">

                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="session_name">معرف الدورة</span>
                    </div>
                    <input required name="session_name" id="session_name" type="text" class="form-control">
                </div>

                <br>
                <div class="row">

                    <!-- Students list -->
                    <div class="col-md-6 font-weight-bold" style="height: 350px; overflow-y: auto;">
                        <h4>الطلاب</h4>
                        <div class="mb-3">
                            <select id="classDropdown" class="form-control mb-2" onchange="filterStudents()">
                                <option value="">اختر الصف</option>
                                <?php
                                // Get unique classes
                                $classes = array_unique(array_column($students, 'class'));
                                sort($classes);
                                foreach ($classes as $class) { ?>
                                    <option value="<?php echo $class; ?>"><?php echo 'الصف: ' . $class; ?></option>
                                <?php } ?>
                            </select>
                            <input type="text" id="searchInput" class="form-control" onkeyup="filterStudents()"
                                   placeholder="ابحث عن طالب...">
                        </div>
                        <div id="studentCheckboxes">
                            <?php foreach ($students as $student) { ?>
                                <div class="mr-2 mb-2 form-check row justify-content-center student-row" data-class="<?php echo $student['class']; ?>">
                                    <input class="form-check-input" type="checkbox" name="students[]"
                                           id="session_<?php echo $student['id']; ?>"
                                           value="<?php echo $student['id']; ?>"
                                           oninput="getClassValue(<?= $student['class'] ?>, this)">
                                    <label class="mr-2 form-check-label" for="session_<?php echo $student['id']; ?>">
                                        <?php echo $student['name'] . ' - ' . $student['school_name'] . ' - '
                                            . $student['class'] ?>
                                    </label>
                                </div>
                            <?php } ?>
                        </div>
                    </div>


                    <!-- Sessions list -->
                    <div class="col-md-2 font-weight-bold">
                        <h4>الدورات</h4>
                        <div class="m-auto form-check col session_package">
                            <div class="row justify-content-between">
                                <label class="mr-2" for="1">دورة خاصة</label>
                                <input class="form-check-input" type="radio" name="session_package" id="1"
                                       value="دورة خاصة" oninput="getPrivateValue()">
                            </div>
                            <div id="school_backpack" class="row justify-content-between">
                                <label class="mr-2" for="2">حقيبة مدرسية</label>
                                <input class="form-check-input" type="radio" name="session_package" id="2"
                                       value="حقيبة مدرسية" oninput="getPrivateValue()">
                            </div>
                            <div class="row justify-content-between">
                                <label class="mr-2" for="3">اشتراك شهري</label>
                                <input class="form-check-input" type="radio" name="session_package" id="3"
                                       value="اشتراك شهري" oninput="getPrivateValue()">
                            </div>
                        </div>
                    </div>
                    <!-- price and subs -->
                    <div class="col-md-4 font-weight-bold">

                        <h4>اختر فئة الإشتراك والسعر</h4>
                        <div class="mr-2 form-check row justify-content-center" id="individual-div">
                            <input class="form-check-input" type="radio" name="is_group"
                                   id="individual"
                                   value="0">
                            <label class="mr-2 form-check-label" for="individual">
                                فردي
                            </label>
                        </div>
                        <div class="mr-2 form-check row justify-content-center" id="group-div">
                            <input class="form-check-input" type="radio" name="is_group"
                                   id="group" value="1">
                            <label class="mr-2 form-check-label"
                                   for="group">
                                جماعي
                            </label>
                        </div>
                        <div class="form-group row justify-content-center mt-3" id="hours-div">
                            <label for="hours" class="col-form-label">عدد الساعات:</label>
                            <div class="col-md-12">
                                <input type="number" min="0" value="0" class="form-control" id="hours" name="hours"
                                       placeholder="عدد الساعات">
                            </div>
                        </div>
                        <div class="form-group row justify-content-center mt-3" id="price-div">
                            <label for="price" class="col-form-label">السعر لكل طالب:</label>
                            <div class="col-md-12">
                                <input type="number" class="form-control" min="0" id="price" name="price"
                                       placeholder="السعر لكل طالب">
                            </div>
                        </div>
                    </div>
                    <!-- Materials list -->
                    <div class="col-md-5 mt-3 font-weight-bold" style="height: 350px; overflow-y: auto;">
                        <h4>قائمة المواد</h4>
                        <div class="mr-2 form-check row justify-content-center">
                            <input class="form-check-input" type="checkbox" id="all_materials_2"
                                   onclick="toggleAllMaterials2()">
                            <label class="mr-2 form-check-label" for="all_materials_2">كل المواد</label>
                        </div>
                        <?php foreach ($materials as $material) { ?>
                            <div class="mr-2 form-check row justify-content-center">
                                <input class="form-check-input" type="checkbox" name="materials2[]"
                                       id="material2_<?php echo $material['id']; ?>"
                                       value="<?php echo $material['id']; ?>">
                                <label class="mr-2 form-check-label" for="material2_<?php echo $material['id']; ?>">
                                    <?php echo $material['name']; ?>
                                </label>
                            </div>
                        <?php } ?>
                    </div>
                    <!--                    Teachers list-->
                    <div class="col-md-5 mt-3  font-weight-bold" style="height: 150px; overflow-y: auto;">
                        <div>
                            <p><strong>المعلمين</strong></p>
                            <?php
                            foreach ($teachers as $teacher) { ?>
                                <div class="mr-2 form-check row justify-content-center">
                                    <input class="form-check-input" type="checkbox" name="teachers[]"
                                           id="teacher_<?php echo $teacher['id']; ?>"
                                           value="<?php echo $teacher['id']; ?>">
                                    <label class="form-check-label"
                                           for="teacher_<?php echo $teacher['id']; ?>">
                                        <?php echo $teacher['name']; ?>
                                    </label>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="col-md-2 mt-3">
                        <button type="submit" style="height: 5rem" class="m-3 btn btn-success btn-block">حفظ</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="tab-pane fade show" id="student-to-package" role="tabpanel"
             aria-labelledby="student-to-package-tab">
            <form method="post" enctype="application/x-www-form-urlencoded" action="../assignPackages.php">

                <br>
                <input hidden name="pkg" value="student-to-package">
                <div class="row">
                    <!-- Students list -->
                    <div class="col-md-4 font-weight-bold" style="height: 350px; overflow-y: auto;">
                        <h4>الطلاب</h4>
                        <div class="mb-3">
                            <select id="classDropdown" class="form-control mb-2" onchange="filterStudents()">
                                <option value="">اختر الصف</option>
                                <?php
                                // Get unique classes
                                $classes = array_unique(array_column($students, 'class'));
                                sort($classes);
                                foreach ($classes as $class) { ?>
                                    <option value="<?php echo $class; ?>"><?php echo 'الصف: ' . $class; ?></option>
                                <?php } ?>
                            </select>
                            <input type="text" id="searchInput" class="form-control" onkeyup="filterStudents()"
                                   placeholder="ابحث عن طالب...">
                        </div>
                        <div id="studentCheckboxes">
                            <?php foreach ($students as $student) { ?>
                                <div class="mr-2 mb-2 form-check row justify-content-center student-row" data-class="<?php echo $student['class']; ?>">
                                    <input class="form-check-input" type="checkbox" name="students[]"
                                           id="session_<?php echo $student['id']; ?>"
                                           value="<?php echo $student['id']; ?>"
                                           oninput="getClassValue(<?= $student['class'] ?>, this)">
                                    <label class="mr-2 form-check-label" for="session_<?php echo $student['id']; ?>">
                                        <?php echo $student['name'] . ' - ' . $student['school_name'] . ' - '
                                            . $student['class'] ?>
                                    </label>
                                </div>
                            <?php } ?>
                        </div>
                    </div>

                    <!-- Sessions list -->
                    <div class="col-md-4 font-weight-bold" style="height: 350px; overflow-y: auto;">
                        <p><strong>الدورات</strong></p>
                        <?php foreach ($sessions as $session) { ?>
                            <div class="mr-2 form-check row justify-content-center">
                                <input class="form-check-input" type="checkbox" name="sessions[]"
                                       id="session_<?php echo $session['id']; ?>"
                                       value="<?php echo $session['id']; ?>">
                                <label class="mr-2 form-check-label" for="session_<?php echo $session['id']; ?>">
                                    <?php echo $session['session_name']; ?>
                                </label>
                            </div>
                        <?php } ?>
                    </div>

                    <!-- Materials list -->
                    <div class="col-md-3 font-weight-bold" style="height: 350px; overflow-y: auto;">
                        <p><strong>قائمة المواد</strong></p>
                        <div class="form-check row justify-content-center">
                            <input class="form-check-input" type="checkbox" id="all_materials"
                                   onclick="toggleAllMaterials()">
                            <label class="mr-2 form-check-label" for="all_materials">كل المواد</label>
                        </div>
                        <?php foreach ($materials as $material) { ?>
                            <div class="form-check row justify-content-center">
                                <input class="form-check-input" type="checkbox" name="materials[]"
                                       id="material_<?php echo $material['id']; ?>"
                                       value="<?php echo $material['id']; ?>">
                                <label class="mr-2 form-check-label" for="material_<?php echo $material['id']; ?>">
                                    <?php echo $material['name']; ?>
                                </label>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="col-md-1 mt-3">
                        <button type="submit" style="height: 5rem" class="m-3 btn btn-success btn-block">حفظ</button>
                    </div>

                </div>
            </form>
        </div>

    </div>
</div>

<!-- For sessions report-->
<div class="container text-center">
    <div class="col-md-12 mb-2 font-weight-bold">

        <body>
        <h1>تقرير الدورات</h1>
        <table class="table table-striped text-right" id="reports">
            <thead>
            <tr>
                <th>الاسم</th>
                <th>النوع</th>
                <th>المواد</th>
                <th>عدد الساعات</th>
                <th>السعر</th>
                <th>المعلمون</th>
                <th>الطلاب</th>
            </tr>
            </thead>
            <tbody>
            <?php
            // Fetch all sessions with details


            // Display session details in the table
            foreach ($sessions as $session) {
                echo "<tr>";
                echo "<td>{$session['session_name']}</td>";
                echo "<td>{$session['type']}</td>";
                echo "<td>{$session['materials']}</td>";
                echo "<td>{$session['hours']}</td>";
                echo "<td>{$session['price']}</td>";

                // Display teacher details
                echo "<td>";
                foreach ($session['teachers'] as $teacher) {
                    echo "{$teacher['teacher_names']}"; // Add more teacher details here if needed
                }
                echo "</td>";

                // Display student details
                echo "<td>";
                foreach ($session['students'] as $student) {
                    echo "{$student['student_names']}<br>"; // Add more student details here if needed
                }
                echo "</td>";

                echo "</tr>";
            }
            ?>
            </tbody>
        </table>
        </body>

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
        var table = $('#reports').DataTable({
            lengthChange: false,
            buttons: [
                'copy', 'excel', 'csv', {
                    extend: 'pdfHtml5',
                    text: 'PDF',
                    filename: 'Sessions_Report',
                    title: 'Sessions Report',
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

<script>

    let selectedClassValues = [];
    let selectAllCheckbox = document.getElementById('all_materials_2');
    function filterStudents() {
        let selectedClass = document.getElementById('classDropdown').value.toUpperCase();
        let searchInput = document.getElementById('searchInput').value.toUpperCase();
        let studentRows = document.querySelectorAll('.student-row');

        studentRows.forEach(function(row) {
            let studentName = row.querySelector('.form-check-label').textContent.toUpperCase();
            let studentClass = row.getAttribute('data-class').toUpperCase();

            if ((selectedClass === "" || studentClass === selectedClass) &&
                (searchInput === "" || studentName.includes(searchInput))) {
                row.style.display = 'flex';
            } else {
                row.style.display = 'none';
            }
        });
    }
    function getClassValue(classValue, checkbox) {
        if (checkbox.checked) {
            selectedClassValues.push(classValue);
        } else {
            let index = selectedClassValues.indexOf(classValue);
            if (index > -1) {
                selectedClassValues.splice(index, 1); // Remove only the first occurrence
            }
        }

        let schoolBackpackDiv = document.getElementById('school_backpack');
        console.log(selectedClassValues)
        if (selectedClassValues.some(value => parseInt(value) >= 10)) {
            schoolBackpackDiv.style.visibility = 'hidden';
            alert('لا يمكنك تحديد حقيبة مدرسية مع طلاب الصف العاشر فما فوق!');
        } else {
            schoolBackpackDiv.style.visibility = 'visible';
        }
    }

    function getPrivateValue() {
        let privateValue = document.querySelector('input[name="session_package"]:checked').value;
        let hoursDiv = document.getElementById('hours-div');
        let individualDiv = document.getElementById('individual-div');

        if (privateValue === 'دورة خاصة') {
            hoursDiv.style.visibility = 'hidden';
            individualDiv.style.visibility = 'visible';
        } else if (privateValue === 'حقيبة مدرسية') {
            console.log(selectedClassValues)
            if (selectedClassValues.some(value => parseInt(value) >= 10)) {
                // alert('لا يمكنك تحديد حقيبة مدرسية مع طلاب الصف العاشر فما فوق!');
                document.querySelector('input[name="session_package"]:checked').checked = false;
                return;
            }
            individualDiv.style.visibility = 'hidden';
            hoursDiv.style.visibility = 'visible';
            toggleAllMaterials2(true); // Call the function here with true to check all checkboxes
        } else {
            hoursDiv.style.visibility = 'visible';
            individualDiv.style.visibility = 'visible';
        }
    }

    function toggleDropdown() {
        document.getElementById("myDropdown").classList.toggle("show");
    }

    function toggleAllMaterials() {
        var checkboxes = document.querySelectorAll('input[name="materials[]"]');
        var selectAllCheckbox = document.getElementById('all_materials');

        checkboxes.forEach(function (checkbox) {
            checkbox.checked = selectAllCheckbox.checked;
        });
    }

    function toggleAllMaterials2(checkAll = false) {

        let checkboxes = document.querySelectorAll('input[name="materials2[]"]');
        let selectAllCheckbox = document.getElementById('all_materials_2');

        // If the function is called with checkAll as true, set selectAllCheckbox.checked to true
        if (checkAll) {
            selectAllCheckbox.checked = true;
        }

        checkboxes.forEach(function (checkbox) {
            checkbox.checked = selectAllCheckbox.checked;
        });
    }

    function filterFunction() {
        var input, filter, div, label, i, txtValue;
        input = document.getElementById("searchInput");
        filter = input.value.toUpperCase();
        div = document.getElementById("myDropdown");
        label = div.getElementsByTagName("label");
        for (i = 0; i < label.length; i++) {
            txtValue = label[i].textContent || label[i].innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                label[i].style.display = "";
            } else {
                label[i].style.display = "none";
            }
        }
    }


</script>