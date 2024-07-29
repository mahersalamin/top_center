<?php require 'header.php';


?>
<style>


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

    /* Multi-step form container */
    .tab-pane {
        font-family: 'Cairo', sans-serif;
    }

    /* Form step container */
    .tab {
        display: none;
    }

    /* Step indicator */
    .step {
        height: 15px;
        width: 15px;
        margin: 0 2px;
        background-color: #bbbbbb;
        border: none;
        border-radius: 50%;
        display: inline-block;
        opacity: 0.5;
    }

    /* Active step indicator */
    .step.active {
        opacity: 1;
    }

    /* Completed step indicator */
    .step.finish {
        background-color: #4CAF50;
    }

    /* Navigation buttons */
    .btn-secondary, .btn-primary {
        margin-top: 20px;
    }

    /* Make the Previous button appear inline if it's not the first tab */
    .btn-secondary {
        display: none;
    }


    /* Input fields */
    .form-control {
        margin-bottom: 20px;
    }

    /* Step-specific styling */
    #studentCheckboxes, #materialsCheckboxes, #teachersCheckboxes {
        height: 350px;
        overflow-y: auto;
    }

    #studentCheckboxes .form-check, #materialsCheckboxes .form-check, #teachersCheckboxes .form-check {
        margin-bottom: 10px;
    }

    /* Ensure that the form container takes the full width */
    form#multiStepForm {
        width: 100%;
    }

    /* Style the buttons consistently */
    button {
        padding: 10px 20px;
    }

    /* Highlight the current step */
    .step.active {
        background-color: #007bff;
        color: white;
    }

    /* Customize the submit button */
    #studentCheckboxes {
        background-color: #e9e9ff;
    }


    /* Form step container */
    .tab1 {
        display: none;
    }

    /* Step indicator */
    .step1 {
        height: 15px;
        width: 15px;
        margin: 0 2px;
        background-color: #bbbbbb;
        border: none;
        border-radius: 50%;
        display: inline-block;
        opacity: 0.5;
    }

    /* Active step indicator */
    .step1.active {
        opacity: 1;
    }

    /* Completed step indicator */
    .step1.finish {
        background-color: #4CAF50;
    }

    /* Navigation buttons */
    .btn-secondary, .btn-primary {
        margin-top: 20px;
    }

    .invalid {
        color: red;
        border: 1px solid;
    }


    /* Step-specific styling */
    #studentCheckboxes, #materialsCheckboxes, #teachersCheckboxes {
        height: 350px;
        overflow-y: auto;
    }

    #studentCheckboxes .form-check, #materialsCheckboxes .form-check, #teachersCheckboxes .form-check {
        margin-bottom: 10px;
    }

    /* Ensure that the form container takes the full width */
    form#multiStepForm, form#studentToPackageForm {
        width: 100%;
    }


    /* Highlight the current step */
    .step1.active {
        background-color: #007bff;
        color: white;
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
        <!--    اضافة دورات جديدة    -->
        <div class="tab-pane fade show active" id="new-package" role="tabpanel" aria-labelledby="new-package-tab">
            <form id="multiStepForm" method="post" enctype="application/x-www-form-urlencoded"
                  action="../assignPackages.php">
                <input hidden name="pkg" value="package">

                <!-- Step 1: Session Name -->
                <div class="tab">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="session_name">معرف الدورة</span>
                        </div>
                        <input required name="session_name" id="session_name" type="text" class="form-control">
                    </div>
                </div>

                <!-- Step 2: Students list -->
                <div class="tab">
                    <div class="col-md-12 font-weight-bold" style="height: 400px; overflow-y: auto;">
                        <h4>الطلاب</h4>
                        <div class="mb-3">
                            <select id="classDropdown" class="form-control mb-2" onchange="filterStudents()">
                                <option value="">اختر الصف</option>
                                <?php
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
                                <div class="mr-2 mb-2 form-check row justify-content-center student-row"
                                     data-class="<?php echo $student['class']; ?>">
                                    <input class="form-check-input" type="checkbox" name="students[]"
                                           id="session_<?php echo $student['id']; ?>"
                                           value="<?php echo $student['id']; ?>"
                                           oninput="getClassValue(<?= $student['class'] ?>, this)">
                                    <label class="mr-2 form-check-label" for="session_<?php echo $student['id']; ?>">
                                        <?php echo $student['name'] . ' - ' . $student['school_name'] . ' - ' . $student['class']; ?>
                                    </label>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>

                <!-- Step 3: Sessions list -->
                <div class="tab">
                    <div class="col-md-6 font-weight-bold">
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
                </div>

                <!-- Step 4: Price and subscription -->
                <div class="tab">
                    <div class="col-md-6 font-weight-bold">
                        <h4>اختر فئة الإشتراك والسعر</h4>
                        <div class="mr-2 form-check row justify-content-center" id="individual-div">
                            <input class="form-check-input" type="radio" name="is_group" id="individual" value="0">
                            <label class="mr-2 form-check-label" for="individual">فردي</label>
                        </div>
                        <div class="mr-2 form-check row justify-content-center" id="group-div">
                            <input class="form-check-input" type="radio" name="is_group" id="group" value="1">
                            <label class="mr-2 form-check-label" for="group">جماعي</label>
                        </div>
                        <div class="form-group row justify-content-center mt-3" id="hours-div">
                            <label for="hours" class="col-form-label">عدد الساعات (اللقاءات في حال اختيار حقيبة مدرسية):</label>
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
                </div>

                <!-- Step 5: Materials list -->
                <div class="tab">
                    <div class="row">
                        <!-- Column for Class Type 1 -->
                        <div id="elementary_materials" class="col-md-4 mt-3 font-weight-bold" style="height: 350px; overflow-y: auto;">
                            <h4>قائمة المواد الأساسية</h4>
                            <div class="mr-2 form-check row justify-content-center">
                                <input class="form-check-input" type="checkbox" id="all_class1"
                                       onclick="toggleAll('class1')">
                                <label class="mr-2 form-check-label" for="all_class1">كل المواد</label>
                            </div>
                            <?php foreach ($materials as $material) {
                                if ($material['class_type'] == 1) { ?>
                                    <div class="mr-2 form-check row justify-content-center">
                                        <input class="form-check-input class1" type="checkbox" name="materials2[]"
                                               id="material1_<?php echo $material['id']; ?>"
                                               value="<?php echo $material['id']; ?>">
                                        <label class="mr-2 form-check-label"
                                               for="material1_<?php echo $material['id']; ?>">
                                            <?php echo $material['name']; ?>
                                        </label>
                                    </div>
                                <?php }
                            } ?>
                        </div>

                        <!-- Column for Class Type 2 -->
                        <div id="mid_materials" class="col-md-4 mt-3 font-weight-bold" style="height: 350px; overflow-y: auto;">
                            <h4>قائمة المواد الاعدادية</h4>
                            <div class="mr-2 form-check row justify-content-center">
                                <input class="form-check-input" type="checkbox" id="all_class2"
                                       onclick="toggleAll('class2')">
                                <label class="mr-2 form-check-label" for="all_class2">كل المواد</label>
                            </div>
                            <?php foreach ($materials as $material) {
                                if ($material['class_type'] == 2) { ?>
                                    <div class="mr-2 form-check row justify-content-center">
                                        <input class="form-check-input class2" type="checkbox" name="materials2[]"
                                               id="material2_<?php echo $material['id']; ?>"
                                               value="<?php echo $material['id']; ?>">
                                        <label class="mr-2 form-check-label"
                                               for="material2_<?php echo $material['id']; ?>">
                                            <?php echo $material['name']; ?>
                                        </label>
                                    </div>
                                <?php }
                            } ?>
                        </div>

                        <!-- Column for Class Type 3 -->
                        <div id="secondary_materials" class="col-md-4 mt-3 font-weight-bold" style="height: 350px; overflow-y: auto;">
                            <h4>قائمة المواد الثانوية</h4>
                            <div class="mr-2 form-check row justify-content-center">
                                <input class="form-check-input" type="checkbox" id="all_class3"
                                       onclick="toggleAll('class3')">
                                <label class="mr-2 form-check-label" for="all_class3">كل المواد</label>
                            </div>
                            <?php foreach ($materials as $material) {
                                if ($material['class_type'] == 3) { ?>
                                    <div class="mr-2 form-check row justify-content-center">
                                        <input class="form-check-input class3" type="checkbox" name="materials2[]"
                                               id="material3_<?php echo $material['id']; ?>"
                                               value="<?php echo $material['id']; ?>">
                                        <label class="mr-2 form-check-label"
                                               for="material3_<?php echo $material['id']; ?>">
                                            <?php echo $material['name']; ?>
                                        </label>
                                    </div>
                                <?php }
                            } ?>
                        </div>
                    </div>
                </div>

                <!-- Step 6: Teachers list -->
                <div class="tab">
                    <div class="col-md-5 mt-3 font-weight-bold" style="height: 400px; overflow-y: auto;">
                        <div>
                            <p><strong>المعلمين</strong></p>
                            <?php foreach ($teachers as $teacher) { ?>
                                <div class="mr-2 form-check row justify-content-center">
                                    <input class="form-check-input" type="checkbox" name="teachers[<?php echo $teacher['id']; ?>][id]"
                                           id="teacher_<?php echo $teacher['id']; ?>"
                                           value="<?php echo $teacher['id']; ?>">
                                    <label class="form-check-label mr-2" for="teacher_<?php echo $teacher['id']; ?>">
                                        <?php echo $teacher['name']; ?>
                                    </label>

                                    <select class="form-control percentage-select ml-2"
                                            name="teachers[<?php echo $teacher['id']; ?>][percentage]"
                                            id="percentage_<?php echo $teacher['id']; ?>"
                                            disabled>
                                        <option value="" disabled selected>اختر النسب</option>
                                        <option value="50">50%</option>
                                        <option value="75">75%</option>
                                    </select>
                                </div>
                            <?php } ?>
                        </div>
                    </div>

                </div>
                <!-- Navigation Buttons -->
                <div style="overflow:auto;">
                    <div style="float:right;">
                        <button type="button" class="btn btn-secondary" id="prevBtn" onclick="nextPrev(-1)
">السابق
                        </button>
                        <button type="button" class="btn btn-primary" id="nextBtn" onclick="nextPrev(1)
">التالي
                        </button>
                    </div>
                </div>

                <!-- Step Indicators -->
                <div style="text-align:center;margin-top:40px;">
                    <span class="step"></span>
                    <span class="step"></span>
                    <span class="step"></span>
                    <span class="step"></span>
                    <span class="step"></span>
                    <span class="step"></span>
                </div>
            </form>
        </div>


        <!--    تعيين الى دورات    -->
        <div class="tab-pane fade show" id="student-to-package" role="tabpanel"
             aria-labelledby="student-to-package-tab">
            <form id="studentToPackageForm" method="post" enctype="application/x-www-form-urlencoded"
                  action="../assignPackages.php">
                <input hidden name="pkg" value="student-to-package">

                <!-- Step 1: Select Students -->
                <div class="tab1">
                    <div class="col-md-12 font-weight-bold" style="height: 350px; overflow-y: auto;">
                        <h4>الطلاب</h4>
                        <div class="mb-3">
                            <select id="classDropdown2" class="form-control mb-2" onchange="filterStudents2()">
                                <option value="">اختر الصف</option>
                                <?php
                                // Get unique classes
                                $classes = array_unique(array_column($students, 'class'));
                                sort($classes);
                                foreach ($classes as $class) { ?>
                                    <option value="<?php echo $class; ?>"><?php echo 'الصف: ' . $class; ?></option>
                                <?php } ?>
                            </select>
                            <input type="text" id="searchInput2" class="form-control" onkeyup="filterStudents2()"
                                   placeholder="ابحث عن طالب...">
                        </div>
                        <div id="studentCheckboxes">
                            <?php foreach ($students as $student) { ?>
                                <div class="mr-2 mb-2 form-check row justify-content-center student-row2"
                                     data-class="<?php echo $student['class']; ?>">
                                    <input class="form-check-input" type="checkbox" name="students[]"
                                           id="session_<?php echo $student['id']; ?>"
                                           value="<?php echo $student['id']; ?>">
                                    <label class="mr-2 form-check-label" for="session_<?php echo $student['id']; ?>">
                                        <?php echo $student['name'] . ' - ' . $student['school_name'] . ' - ' . $student['class']; ?>
                                    </label>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Select Sessions -->
                <div class="tab1">
                    <div class="col-md-12 font-weight-bold" style="height: 350px; overflow-y: auto;">
                        <h4>الدورات</h4>
                        <?php foreach ($sessions as $session) { ?>
                            <div class="mr-2 mt-2 form-check row justify-content-center">
                                <input class="form-check-input" type="radio" name="sessions[]"
                                       id="session_<?php echo $session['id']; ?>" value="<?php echo $session['id']; ?>">
                                <label class="mr-2 form-check-label" for="session_<?php echo $session['id']; ?>">
                                    <?php echo $session['session_name']; ?>
                                </label>
                            </div>
                        <?php } ?>
                    </div>
                </div>

                <!-- Step 3: Select Materials -->
                <div class="tab1">
                    <div class="col-md-12 font-weight-bold" style="height: 350px; overflow-y: auto;">
                        <h4>قائمة المواد</h4>
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
                </div>

                <!-- Navigation Buttons -->
                <div style="overflow:auto;">
                    <div style="float:right;">
                        <button type="button" class="btn btn-secondary" id="prevBtn1" onclick="nextPrev1(-1)
">السابق
                        </button>
                        <button type="button" class="btn btn-primary" id="nextBtn1" onclick="nextPrev1(1)
">التالي
                        </button>
                    </div>
                </div>

                <!-- Step Indicators -->
                <div style="text-align:center;margin-top:40px;">
                    <span class="step1"></span>
                    <span class="step1"></span>
                    <span class="step1"></span>
                </div>
            </form>
        </div>

    </div>
</div>



<script>
    // Enable or disable percentage select based on checkbox state
    document.querySelectorAll('.form-check-input').forEach(checkbox => {
        checkbox.addEventListener('change', function () {
            const teacherId = this.value;
            const percentageSelect = document.getElementById('percentage_' + teacherId);
            if (this.checked) {
                percentageSelect.disabled = false;
            } else {
                percentageSelect.disabled = true;
                percentageSelect.value = ''; // Clear the select if unchecked
            }
        });
    });
</script>

<script>
    let currentTab = 0;
    showTab(currentTab);

    function showTab(n) {
        let x = document.getElementsByClassName("tab");
        x[n].style.display = "block";
        if (n == 0) {
            document.getElementsByClassName("btn-secondary")[0].style.display = "none";
        } else {
            document.getElementsByClassName("btn-secondary")[0].style.display = "inline";
        }
        if (n == (x.length - 1)) {
            document.getElementById("nextBtn").innerHTML = "حفظ";
        } else {
            document.getElementById("nextBtn").innerHTML = "التالي";
        }
        fixStepIndicator(n);
    }

    function nextPrev(n) {
        let x = document.getElementsByClassName("tab");
        x[currentTab].style.display = "none";
        currentTab = currentTab + n;
        if (currentTab >= x.length) {
            document.getElementById("multiStepForm").submit();
            return false;
        }
        showTab(currentTab);
    }

    function fixStepIndicator(n) {
        let i, x = document.getElementsByClassName("step");

        for (i = 0; i < x.length; i++) {
            x[i].className = x[i].className.replace(" active", "");
        }
        x[n].className += " active";
    }
</script>

<script>
    let currentTab1 = 0; // Current tab is set to be the first tab (0)
    showTab1(currentTab1); // Display the current tab

    function showTab1(n) {
        // This function will display the specified tab of the form
        let x = document.getElementsByClassName("tab1");
        x[n].style.display = "block";
        // Fix the Previous/Next buttons:
        if (n == 0) {
            document.getElementById("prevBtn1").style.display = "none";
        } else {
            document.getElementById("prevBtn1").style.display = "inline";
        }
        if (n == (x.length - 1)) {
            document.getElementById("nextBtn1").innerHTML = "حفظ";
        } else {
            document.getElementById("nextBtn1").innerHTML = "التالي";
        }
        // Run a function that displays the correct step indicator:
        fixStepIndicator1(n)
    }

    function nextPrev1(n) {

        // This function will figure out which tab to display
        let x = document.getElementsByClassName("tab1");

        // Exit the function if any field in the current tab is invalid:
        if (n == 1 && !validateForm1()) return false;
        // Hide the current tab:
        x[currentTab1].style.display = "none";
        // Increase or decrease the current tab by 1:
        currentTab1 = currentTab1 + n;
        // if you have reached the end of the form... :
        if (currentTab1 >= x.length) {
            //...the form gets submitted:
            document.getElementById("studentToPackageForm").submit();
            return false;
        }
        // Otherwise, display the correct tab:
        showTab1(currentTab1);
    }

    function validateForm1() {
        // This function deals with validation of the form fields
        let x, y, i, valid = true;
        x = document.getElementsByClassName("tab1");
        y = x[currentTab1].getElementsByTagName("input");
        // A loop that checks every input field in the current tab:

        for (i = 1; i < y.length; i++) {
            // If a field is empty...
            if (y[i].value == "") {
                // add an "invalid" class to the field:
                y[i].className += " invalid";
                // and set the current valid status to false
                valid = false;
            }
        }
        // If the valid status is true, mark the step as finished and valid:
        if (valid) {
            document.getElementsByClassName("step")[currentTab1].className += " finish";
        }
        return valid; // return the valid status
    }

    function fixStepIndicator1(n) {
        // This function removes the "active" class of all steps...
        let i, x = document.getElementsByClassName("step1");
        for (i = 0; i < x.length; i++) {
            x[i].className = x[i].className.replace(" active", "");
        }
        //... and adds the "active" class to the current step:
        x[n].className += " active";
    }
</script>




<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<!-- DataTables Buttons JS -->
<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>
<!-- JSZip for Excel export -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

<script>
    $(document).ready(function () {
        $('#reports').DataTable({
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
            "order": [[0, 'desc']],
            buttons: [
                'excel', 'print', {
                    text: 'PDF',
                    action: function (e, dt, button, config) {
                        // Get the table headers
                        var headers = [];
                        $('#dt-filter-search-income thead th').each(function() {
                            headers.push($(this).text());
                        });

                        // Get the table data
                        var data = [];
                        dt.rows({ search: 'applied' }).every(function() {
                            let row = [];
                            $(this.node()).find('td').each(function() {
                                row.push($(this).text());
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
                            value: 'income_report'
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

    });
</script>



<script>

    let selectedClassValues = [];


    function filterStudents() {
        let selectedClass = document.getElementById('classDropdown').value.toUpperCase();
        let searchInput = document.getElementById('searchInput').value.toUpperCase();
        let studentRows = document.querySelectorAll('.student-row');

        studentRows.forEach(function (row) {
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

    function filterStudents2() {
        let selectedClass = document.getElementById('classDropdown2').value.toUpperCase();
        let searchInput = document.getElementById('searchInput2').value.toUpperCase();
        let studentRows = document.querySelectorAll('.student-row2');

        studentRows.forEach(function (row) {
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
        let individualDiv = document.getElementById('individual-div');
        let groupInput = document.getElementById('group');
        if (selectedClassValues.length > 1) {
            individualDiv.style.visibility = 'hidden';
            console.log("more than 1")
            groupInput.checked = true
        } else {
            individualDiv.style.visibility = 'visible';
            console.log("only 1")
            groupInput.checked = false
        }

        if (selectedClassValues.some(value => parseInt(value) >= 10)) {
            schoolBackpackDiv.style.visibility = 'hidden';
            // alert('لا يمكنك تحديد حقيبة مدرسية مع طلاب الصف العاشر فما فوق!');
        } else {
            schoolBackpackDiv.style.visibility = 'visible';
        }

        let elementary_div = document.getElementById('elementary_materials');
        let mid_div = document.getElementById('mid_materials');
        let secondary_div = document.getElementById('secondary_materials');

        if (selectedClassValues.some(value => parseInt(value) >= 10)) {
            elementary_div.style.visibility = 'hidden';
            mid_div.style.visibility = 'hidden';
            secondary_div.style.visibility = 'visible';

            // alert('لا يمكنك تحديد حقيبة مدرسية مع طلاب الصف العاشر فما فوق!');
        } else if(selectedClassValues.some(value => parseInt(value) <= 6 )){
            elementary_div.style.visibility = 'visible';
            mid_div.style.visibility = 'hidden';
            secondary_div.style.visibility = 'hidden';
        }else {
            elementary_div.style.visibility = 'hidden';
            mid_div.style.visibility = 'visible';
            secondary_div.style.visibility = 'hidden';
        }
    }

    function getPrivateValue() {
        let privateValue = document.querySelector('input[name="session_package"]:checked').value;
        let hoursDiv = document.getElementById('hours-div');
        let individualDiv = document.getElementById('individual-div');

        if (privateValue === 'دورة خاصة') {
            if (selectedClassValues.length < 2) {
                hoursDiv.style.visibility = 'hidden';
                individualDiv.style.visibility = 'visible';
                console.log("دورة 879")
            } else {
                hoursDiv.style.visibility = 'visible';
                individualDiv.style.visibility = 'hidden';
                console.log("دورة 883")
            }
        } else if (privateValue === 'حقيبة مدرسية') {

            if (selectedClassValues.some(value => parseInt(value) >= 10)) {
                alert('لا يمكنك تحديد حقيبة مدرسية مع طلاب الصف العاشر فما فوق!');
                document.querySelector('input[name="session_package"]:checked').checked = false;
                console.log("حقيبة 890")

            }
            else {
                individualDiv.style.visibility = 'hidden';
                console.log("حقيبة 894")
                hoursDiv.style.visibility = 'visible';
            }
        } else {
            if (selectedClassValues.length < 2) {
                hoursDiv.style.visibility = 'hidden';
                individualDiv.style.visibility = 'visible';
                console.log("اشتراك 900")
            } else {
                hoursDiv.style.visibility = 'visible';
                console.log("اشتراك 903")
                individualDiv.style.visibility = 'hidden';
            }
        }
    }

    function toggleDropdown() {
        document.getElementById("myDropdown").classList.toggle("show");
    }

    function toggleAllMaterials() {
        let checkboxes = document.querySelectorAll('input[name="materials[]"]');
        let selectAllCheckbox = document.getElementById('all_materials');

        checkboxes.forEach(function (checkbox) {
            checkbox.checked = selectAllCheckbox.checked;
        });
    }

    function toggleAll(classType) {
        let checkboxes = document.querySelectorAll('.' + classType);
        let allChecked = document.getElementById('all_' + classType).checked;
        checkboxes.forEach(function (checkbox) {
            checkbox.checked = allChecked;
        });
    }

    function filterFunction() {
        let input, filter, div, label, i, txtValue;
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