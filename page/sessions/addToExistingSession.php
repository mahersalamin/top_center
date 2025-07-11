<?php ?>

<style>
    /* Stepper and Form Styling (from previous optimization) */
    .stepper-container {
        background-color: #f8f9fa;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    }

    .stepper-header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 40px;
        position: relative;
    }

    .stepper-header::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        height: 4px;
        background-color: #e9ecef;
        transform: translateY(-50%);
        z-index: 0;
    }

    .step-indicator {
        position: relative;
        z-index: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        color: #6c757d;
        cursor: pointer;
    }

    .step-number {
        width: 40px;
        height: 40px;
        line-height: 40px;
        background-color: #ffffff;
        border: 2px solid #ced4da;
        border-radius: 50%;
        font-weight: bold;
        transition: all 0.3s;
    }

    .step-title {
        margin-top: 10px;
        font-size: 14px;
        transition: color 0.3s;
    }

    .step-indicator.active .step-number {
        background-color: #007bff;
        border-color: #007bff;
        color: #fff;
    }

    .step-indicator.active .step-title {
        color: #007bff;
        font-weight: bold;
    }

    .step-indicator.complete .step-number {
        background-color: #28a745;
        border-color: #28a745;
        color: #fff;
    }

    /* Form and Input Styling */
    /* Note: We use .tab1 for this specific stepper, but .tab styling is similar */
    .tab1 {
        display: none;
        animation: fadeIn 0.5s;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }

    .form-control:focus, .form-select:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 0.25rem rgba(0, 123, 255, 0.25);
    }

    /* Modern Checkbox/Radio Styling */
    .form-check-input {
        border-radius: 4px;
    }

    /* List Containers */
    #studentCheckboxes2,
    .session-list-container {
        max-height: 400px;
        overflow-y: auto;
        border: 1px solid #e9ecef;
        padding: 15px;
        border-radius: 8px;
        background-color: #fff;
    }

    .list-group-item {
        padding: 10px 0;
    }

    .list-group-item .form-check-input {
        margin-left: 1rem; /* Adjust margin for proper alignment in list items */
    }
</style>

<div class="stepper-container">

        <div class="stepper-header" id="step-indicators1">
            <div class="step-indicator active">
                <span class="step-number">1</span>
                <span class="step-title">الطلاب</span>
            </div>
            <div class="step-indicator">
                <span class="step-number">2</span>
                <span class="step-title">الدورات</span>
            </div>
        </div>

        <form id="studentToPackageForm" method="post" enctype="application/x-www-form-urlencoded" action="../assignPackages.php">
            <label>
                <input hidden name="pkg" value="student-to-package">
            </label>

            <div class="tab1">
                <div class="col-md-12 font-weight-bold">
                    <h4>الطلاب</h4>
                    <div class="mb-4 d-flex gap-3">
                        <select id="classDropdown2" class="form-select" onchange="filterStudents2()">
                            <option value="">اختر الصف</option>
                            <?php
                            $classes = array_unique(array_column($students, 'class'));
                            sort($classes);
                            foreach ($classes as $class) { ?>
                                <option value="<?php echo $class; ?>"><?php echo 'الصف: ' . $class; ?></option>
                            <?php } ?>
                        </select>
                        <input type="text" id="searchInput2" class="form-control" onkeyup="filterStudents2()"
                               placeholder="ابحث عن طالب...">
                    </div>

                    <div id="studentCheckboxes2" class="list-group">
                        <?php foreach ($students as $student) {
                            if ($student['archived'] != 1) { ?>
                                <div class="list-group-item student-row2 d-flex align-items-center"
                                     data-class="<?php echo $student['class']; ?>">
                                    <input class="form-check-input" type="checkbox" name="students[]"
                                           id="students_<?php echo $student['id']; ?>"
                                           value="<?php echo $student['id']; ?>">
                                    <label class="form-check-label mr-4" for="students_<?php echo $student['id']; ?>">
                                        <?php echo $student['name'] . ' - ' . $student['school_name'] . ' - ' . $student['class']; ?>
                                    </label>
                                </div>
                            <?php }
                        } ?>
                    </div>
                </div>
            </div>

            <div class="tab1">
                <div class="col-md-12 font-weight-bold">
                    <h4>الدورات</h4>
                    <div class="session-list-container list-group">
                        <?php foreach ($sessions as $session) { ?>
                            <div class="list-group-item d-flex align-items-center">
                                <input class="form-check-input" type="radio" name="sessions[]"
                                       id="session_<?php echo $session['id']; ?>" value="<?php echo $session['id']; ?>">
                                <label class="form-check-label mr-4" for="session_<?php echo $session['id']; ?>">
                                    <?php echo $session['session_name']; ?>
                                </label>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end mt-4 pt-3 border-top">
                <button type="button" class="btn btn-secondary me-2" id="prevBtn1" onclick="nextPrev1(-1)">السابق</button>
                <button type="button" class="btn btn-primary" id="nextBtn1" onclick="nextPrev1(1)">التالي</button>
            </div>
        </form>
</div>

<script>
    let currentTab1 = 0;
    showTab1(currentTab1);

    // --- Stepper Navigation Functions (Tab 2) ---

    function showTab1(n) {
        const tabs = document.getElementsByClassName("tab1");
        tabs[n].style.display = "block";
        updateStepperIndicators1(n);

        // Fix the Previous/Next buttons
        if (n === 0) {
            document.getElementById("prevBtn1").style.display = "none";
        } else {
            document.getElementById("prevBtn1").style.display = "inline";
        }

        // Change button text on the last step
        if (n === (tabs.length - 1)) {
            document.getElementById("nextBtn1").innerHTML = "إرسال";
        } else {
            document.getElementById("nextBtn1").innerHTML = "التالي";
        }
    }

    function nextPrev1(n) {
        const tabs = document.getElementsByClassName("tab1");

        // Validate the current step before moving
        if (n === 1 && !validateForm1()) return false;

        // Hide the current tab
        tabs[currentTab1].style.display = "none";

        // Increase or decrease the current tab by 1
        currentTab1 = currentTab1 + n;

        // If at the end, submit the form
        if (currentTab1 >= tabs.length) {
            document.getElementById("studentToPackageForm").submit();
            return false;
        }

        // Otherwise, display the correct tab
        showTab1(currentTab1);
    }

    // Simplified validation for this stepper.
    // We validate based on whether at least one input in the current step is selected (checkbox or radio)
    function validateForm1() {
        const currentTab = document.getElementsByClassName("tab1")[currentTab1];
        const inputs = currentTab.querySelectorAll('input[type="checkbox"], input[type="radio"]');

        let isValid = false;

        // Check if any required input (checkbox or radio) is checked
        if (inputs.length > 0) {
            for (let i = 0; i < inputs.length; i++) {
                if (inputs[i].checked) {
                    isValid = true;
                    break;
                }
            }
        } else {
            // If there are no checkboxes/radios (e.g., if a step only has a text input),
            // you might need different validation logic here.
            // For now, assuming Step 1 and 2 require selection.
            isValid = true; // Assume valid if no selection inputs found (e.g., a text-only step)
        }

        // Add 'active' class to the step indicator if valid (for visual feedback)
        if (isValid) {
            updateStepperIndicators1(currentTab1);
        } else {
            alert("Please make a selection before proceeding."); // Provide user feedback
        }

        return isValid;
    }


    // Function to update the modern stepper indicators
    function updateStepperIndicators1(n) {
        const indicators = document.getElementsByClassName("step-indicator");

        // Remove 'active' and 'complete' from all
        for (let i = 0; i < indicators.length; i++) {
            indicators[i].classList.remove("active");
            indicators[i].classList.remove("complete");
        }

        // Add 'complete' to past steps and 'active' to the current step
        for (let i = 0; i < n; i++) {
            indicators[i].classList.add("complete");
        }
        if (indicators[n]) {
            indicators[n].classList.add("active");
        }
    }

    // --- Student Filtering and Search (Tab 2, Step 1) ---

    // This function filters students by class dropdown and name search input for the second tab
    function filterStudents2() {
        const classDropdown = document.getElementById('classDropdown2');
        const searchInput = document.getElementById('searchInput2');
        const selectedClass = classDropdown ? classDropdown.value : null;
        const searchTerm = searchInput ? searchInput.value.toLowerCase() : '';
        const container = document.querySelector('#studentCheckboxes2'); // Make sure this matches your container ID

        // Clear existing content
        container.innerHTML = '';

        // Filter and rebuild student list
        <?php foreach ($students as $student) {
        if ($student['archived'] != 1) { ?>
        // PHP generates JS condition - more efficient than filtering in JS
        if ((!selectedClass || '<?php echo $student["class"]; ?>' === selectedClass) &&
            (!searchTerm || '<?php echo strtolower($student["name"] . ' - ' . $student["school_name"] . ' - ' . $student["class"]); ?>'.includes(searchTerm))) {

            const studentDiv = document.createElement('div');
            studentDiv.className = 'list-group-item student-row2 d-flex align-items-center';
            studentDiv.setAttribute('data-class', '<?php echo $student["class"]; ?>');

            studentDiv.innerHTML = `
                    <input class="form-check-input" type="checkbox"
                           name="students[]"
                           id="students2_<?php echo $student['id']; ?>"
                           value="<?php echo $student['id']; ?>"
                           oninput="getClassValue(<?= $student['class'] ?>, this)">
                    <label class="form-check-label mr-4" for="students2_<?php echo $student['id']; ?>">
                        <?php echo $student['name'] . ' - ' . $student['school_name'] . ' - ' . $student['class']; ?>
                    </label>
                `;

            container.appendChild(studentDiv);
        }
        <?php }
        } ?>
    }
</script>