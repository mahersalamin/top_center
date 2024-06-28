<?php require 'header.php'; ?>
    <style>
        #regForm {
            background-color: #ffffff;
            padding: 40px;
            width: 100%;
            min-width: 300px;
        }

        h1 {
            text-align: center;
        }

        /*input {*/
        /*    padding: 10px;*/
        /*    width: 100%;*/
        /*    font-size: 17px;*/
        /*    */
        /*    border: 1px solid #aaaaaa;*/
        /*}*/

        /* Mark input boxes that gets an error on validation: */
        input.invalid {
            background-color: #ffdddd;
        }

        /* Hide all steps by default: */
        .tab {
            display: none;
        }

        button {
            background-color: #04AA6D;
            color: #ffffff;
            border: none;
            padding: 10px 20px;
            font-size: 17px;
            font-family: Raleway;
            cursor: pointer;
        }

        button:hover {
            opacity: 0.8;
        }

        #prevBtn {
            background-color: #bbbbbb;
        }

        /* Make circles that indicate the steps of the form: */
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

        .step.active {
            opacity: 1;
        }

        /* Mark the steps that are finished and valid: */
        .step.finish {
            background-color: #04AA6D;
        }
    </style>
<?php

if (!isset($_COOKIE['id'])) {
    header("location:signin.php");
}
$db = new MyDB();
$schools = $db->getSchools();
$classes = $db->getClasses();

?>


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

    <ul class="nav nav-tabs justify-content-center mb-4">
        <li class="nav-item">
            <a class="nav-link active" id="students-tab" data-toggle="tab" href="#students" role="tab"
               aria-controls="students" aria-selected="true">نموذج إضافة طالب</a>
        </li>

    </ul>

    <div class="tab-content">
        <div class="tab-pane fade show active" id="students" role="tabpanel" aria-labelledby="students-tab">
            <!-- Student form -->
            <div class="container col-md-6 shadow p-3  bg-body rounded mb-2 text-center " style="font-family: 'Cairo' ">
                <h4 class=" text-info  text-center  font-weight-bold  "> إضافة طالب جديد</h4>
                <br>

                <form id="regForm" action="../AddST.php" method="post" enctype="application/x-www-form-urlencoded">

                    <!-- One "tab" for each step in the form: -->
                    <div class="tab">
                        <p><input required type="text" name="name" class="form-control " aria-describedby="nameHelp"
                                  placeholder="🎫اسم الطالب "></p>

                        <select class="mb-3 form-select" id="school_name" name="school_name">
                            <option disabled selected value="">اختر المدرسة</option>

                            <?php
                            foreach ($schools as $school) {
                                ?>
                                <option value="<?= $school['id'] ?>"> <?php echo $school['name']; ?></option>

                                <?php
                            }
                            ?>
                        </select>
<!--                        <p><input id="student-class" type="number" min="1" max="12" step="1" oninput="getClassValue()"-->
<!--                                  class="form-control"-->
<!--                                  name="class"-->
<!--                                  placeholder="الصف"></p>-->
                        <select class="mb-3 form-select" id="class" name="class">
                            <option disabled selected value="">اختر الصف</option>

                            <?php
                            foreach ($classes as $class) {
                                ?>
                                <option value="<?= $class['id'] ?>"> <?php echo $class['name']; ?></option>

                                <?php
                            }
                            ?>
                        </select>
                        <p><input required type="text" name="phone" class="form-control " aria-describedby="phoneHelp"
                                  placeholder=" 📱رقم الهاتف"></p>
<!--                        <div class="form-group mb-2 ">-->
<!--                            <label for="file" class="btn btn-outline-primary">اختر صورة</label>-->
<!--                            <input class="col-md form-control" style="visibility:hidden;" type="file" id="file" name="file" title="اختر صورة">-->
<!--                        </div>-->

                    </div>
<!--                    <div class="tab">-->
<!--                        <p><strong>قائمة الدورات</strong></p>-->
<!--                        <div class="m-auto form-check col session_package">-->
<!--                            <div class="row justify-content-between">-->
<!--                                <label for="1">دورة خاصة</label>-->
<!--                                <input class="form-check-input" type="radio" name="session_package" id="1"-->
<!--                                       value="دورة خاصة" oninput="getPrivateValue()">-->
<!--                            </div>-->
<!--                            <div id="school_backpack" class="row justify-content-between">-->
<!--                                <label for="2">حقيبة مدرسية</label>-->
<!--                                <input class="form-check-input" type="radio" name="session_package" id="2"-->
<!--                                       value="حقيبة مدرسية" oninput="getPrivateValue()">-->
<!--                            </div>-->
<!--                            <div class="row justify-content-between">-->
<!--                                <label for="3">اشتراك شهري</label>-->
<!--                                <input class="form-check-input" type="radio" name="session_package" id="3"-->
<!--                                       value="اشتراك شهري" oninput="getPrivateValue()">-->
<!--                            </div>-->
<!--                        </div>-->
<!---->
<!--                    </div>-->
<!---->
<!--                    <div class="tab">-->
<!--                        <div class="col-md-12 mb-2 font-weight-bold">-->
<!---->
<!--                            <p><strong>اختر فئة الإشتراك والسعر</strong></p>-->
<!--                            <div class="mr-2 form-check row justify-content-center">-->
<!--                                <input class="form-check-input" type="radio" name="is_group"-->
<!--                                       id="individual"-->
<!--                                       value="0">-->
<!--                                <label class="form-check-label" for="individual">-->
<!--                                    فردي-->
<!--                                </label>-->
<!--                            </div>-->
<!--                            <div class="mr-2 form-check row justify-content-center">-->
<!--                                <input class="form-check-input" type="radio" name="is_group"-->
<!--                                       id="group" value="1">-->
<!--                                <label class="form-check-label"-->
<!--                                       for="group">-->
<!--                                    جماعي-->
<!--                                </label>-->
<!--                            </div>-->
<!--                            <div class="form-group row justify-content-center mt-3" id="hours-div">-->
<!--                                <label for="hours" class="col-form-label">عدد الساعات:</label>-->
<!--                                <div class="col-md-6">-->
<!--                                    <input type="number" min="0" value="0" class="form-control" id="hours" name="hours"-->
<!--                                           placeholder="عدد الساعات">-->
<!--                                </div>-->
<!--                            </div>-->
<!--                            <div class="form-group row justify-content-center mt-3">-->
<!--                                <label for="price" class="col-form-label">السعر:</label>-->
<!--                                <div class="col-md-6">-->
<!--                                    <input type="number" class="form-control" min="0" id="price" name="price"-->
<!--                                           placeholder="السعر">-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                    <div class="tab">المواد:-->
<!--                        <div class="col-md-12 mb-2 font-weight-bold" style="height: 150px; overflow-y: auto;">-->
<!---->
<!--                            <p><strong>تعيين مواد للطالب 👤 </strong></p>-->
<!--                            <div class="mr-2 form-check row justify-content-center">-->
<!--                                <input class="form-check-input" type="checkbox"-->
<!--                                       id="all_materials"-->
<!--                                       onclick="toggleAllMaterials()">-->
<!--                                <label class="form-check-label"-->
<!--                                       for="all_materials">-->
<!--                                    كل المواد-->
<!--                                </label>-->
<!--                            </div>-->
<!--                            --><?php //$materials = $db->getSpecializations();
//
//                            foreach ($materials as $material) { ?>
<!--                                <div class="mr-2 form-check row justify-content-center">-->
<!--                                    <input class="form-check-input" type="checkbox" name="materials[]"-->
<!--                                           id="material_--><?php //echo $material['id']; ?><!--"-->
<!--                                           value="--><?php //echo $material['id']; ?><!--">-->
<!--                                    <label class="form-check-label"-->
<!--                                           for="material--><?php //echo $material['id']; ?><!--">-->
<!--                                        --><?php //echo $material['name']; ?>
<!--                                    </label>-->
<!--                                </div>-->
<!--                            --><?php //} ?>
<!---->
<!--                        </div>-->
<!--                    </div>-->
<!--                    <div class="tab">-->
<!--                        <div class="col-md-6 mb-2 font-weight-bold" style="height: 150px; overflow-y: auto;">-->
<!--                            <div>-->
<!--                                <p><strong>تعيين معلمين للطالب 👤 </strong></p>-->
<!--                                --><?php //$teachers = $db->getAllTeachers();
//                                foreach ($teachers as $teacher) { ?>
<!--                                    <div class="mr-2 form-check row justify-content-center">-->
<!--                                        <input class="form-check-input" type="checkbox" name="teachers[]"-->
<!--                                               id="teacher_--><?php //echo $teacher['id']; ?><!--"-->
<!--                                               value="--><?php //echo $teacher['id']; ?><!--">-->
<!--                                        <label class="form-check-label"-->
<!--                                               for="teacher_--><?php //echo $teacher['id']; ?><!--">-->
<!--                                            --><?php //echo $teacher['name']; ?>
<!--                                        </label>-->
<!--                                    </div>-->
<!--                                --><?php //} ?>
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
                    <div style="overflow:auto;">
                        <div style="float:right;">
                            <button type="button" id="prevBtn" onclick="nextPrev(-1)">السابق</button>
                            <button type="button" id="nextBtn" onclick="nextPrev(1)">التالي</button>
                        </div>
                    </div>
                    <!-- Circles which indicates the steps of the form: -->
                    <div style="text-align:center;margin-top:40px;">
                        <span class="step"></span>
<!--                        <span class="step"></span>-->
<!--                        <span class="step"></span>-->
<!--                        <span class="step"></span>-->
<!--                        <span class="step"></span>-->
                    </div>
                </form>
            </div>

        </div>


    </div>

    <script>

        function toggleAllMaterials() {
            var checkboxes = document.querySelectorAll('input[name="materials[]"]');
            var selectAllCheckbox = document.getElementById('all_materials');

            checkboxes.forEach(function (checkbox) {
                checkbox.checked = selectAllCheckbox.checked;
            });
        }
        function getClassValue() {
            let classVal = parseInt(document.getElementById('student-class').value);
            let schoolBackPack = document.getElementById('school_backpack');

            // Get the radio buttons
            let radioButtons = schoolBackPack.querySelectorAll('input[type="radio"]');

            let inputElement = document.getElementById('2');

            // inputElement.disabled = true;
            if (classVal >= 10) {

                inputElement.disabled = true;
                schoolBackPack.style.visibility = 'hidden';
            } else {

                inputElement.disabled = false
                schoolBackPack.style.visibility = 'visible';

            }

        }
        function getPrivateValue() {
            let privateValue = document.querySelector('input[name="session_package"]:checked').value;
            let hoursDiv = document.getElementById('hours-div');
            if (privateValue === 'دورة خاصة') {
                hoursDiv.style.visibility = 'hidden';
            } else {
                hoursDiv.style.visibility = 'visible';

            }
        }


    </script>
    <script>
        var currentTab = 0; // Current tab is set to be the first tab (0)
        showTab(currentTab); // Display the current tab

        function showTab(n) {
            // This function will display the specified tab of the form...
            var x = document.getElementsByClassName("tab");
            x[n].style.display = "block";
            //... and fix the Previous/Next buttons:
            if (n === 0) {
                document.getElementById("prevBtn").style.display = "none";
            } else {
                document.getElementById("prevBtn").style.display = "inline";
            }
            if (n === (x.length - 1)) {
                nextButton = document.getElementById("nextBtn");
                nextButton.innerHTML = "حفظ";
                // nextButton.setAttribute('type', 'submit');
            } else {
                document.getElementById("nextBtn").innerHTML = "التالي";
            }
            //... and run a function that will display the correct step indicator:
            fixStepIndicator(n)
        }

        function nextPrev(n) {
            // This function will figure out which tab to display
            let x = document.getElementsByClassName("tab");
            // Exit the function if any field in the current tab is invalid:
            if (n === 1 && !validateForm()) return false;
            // Hide the current tab:
            x[currentTab].style.display = "none";
            // Increase or decrease the current tab by 1:
            currentTab = currentTab + n;
            // if you have reached the end of the form...
            if (currentTab >= x.length) {
                // ... the form gets submitted:
                document.getElementById("regForm").submit();
                return false;
            }
            // Otherwise, display the correct tab:
            showTab(currentTab);
        }

        function validateForm() {
            // This function deals with validation of the form fields
            var x, y, i, valid = true;
            x = document.getElementsByClassName("tab");
            y = x[currentTab].getElementsByTagName("input");
            // A loop that checks every input field in the current tab:
            for (i = 0; i < y.length; i++) {
                // If a field is empty...
                if (y[i].value === "") {
                    // add an "invalid" class to the field:
                    y[i].className += " invalid";
                    // and set the current valid status to false
                    valid = false;
                }
            }
            // If the valid status is true, mark the step as finished and valid:
            if (valid) {

                document.getElementsByClassName("step")[currentTab].className += " finish";
            }
            return valid; // return the valid status
        }

        function fixStepIndicator(n) {
            // This function removes the "active" class of all steps...
            var i, x = document.getElementsByClassName("step");
            for (i = 0; i < x.length; i++) {
                x[i].className = x[i].className.replace(" active", "");
            }
            //... and adds the "active" class on the current step:
            x[n].className += " active";
        }
    </script>

<?php require 'ut/datepicker.php'; ?>