<?php ?>

<style>
    /* Stepper Styling */
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
    .tab {
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

    .input-group-text,
    .form-control {
        border-radius: 8px;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 0.25rem rgba(0, 123, 255, 0.25);
    }

    /* Modern Checkbox/Radio Styling */
    .form-check-input {
        /* Ensuring standard Bootstrap modern look */
        border-radius: 4px;
    }

    /* List Containers (e.g., Students, Materials) */
    #studentCheckboxes,
    #teachersContainer,
    #elementary_materials,
    #mid_materials,
    #secondary_materials {
        max-height: 400px;
        overflow-y: auto;
        border: 1px solid #e9ecef;
        padding: 15px;
        border-radius: 8px;
        background-color: #fff;
    }

    .student-row,
    .specialization-item,
    .teacher-card {
        padding: 10px 0;
        border-bottom: 1px solid #eee;
    }

    .teacher-card {
        transition: transform 0.2s;
        border: 1px solid #dee2e6;
        border-radius: 8px;
    }

    .teacher-card:hover {
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
    }
</style>

<div class="stepper-container">

    <div class="stepper-header" id="step-indicators">
        <div class="step-indicator active">
            <span class="step-number">1</span>
            <span class="step-title">معرف الدورة</span>
        </div>
        <div class="step-indicator">
            <span class="step-number">2</span>
            <span class="step-title">الطلاب</span>
        </div>
        <div class="step-indicator">
            <span class="step-number">3</span>
            <span class="step-title">الدورات</span>
        </div>
        <div class="step-indicator">
            <span class="step-number">4</span>
            <span class="step-title">الإشتراك والسعر</span>
        </div>
        <div class="step-indicator">
            <span class="step-number">5</span>
            <span class="step-title">المواد</span>
        </div>
        <div class="step-indicator">
            <span class="step-number">6</span>
            <span class="step-title">المعلمين</span>
        </div>
    </div>

    <form id="multiStepForm" method="post" enctype="application/x-www-form-urlencoded" action="../assignPackages.php">
        <label>
            <input hidden name="pkg" value="package">
        </label>

        <div class="tab">
            <h4 class="mb-4">معرف الدورة</h4>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="session_name_label">معرف الدورة</span>
                </div>
                <input required name="session_name" id="session_name" type="text" class="form-control" placeholder="أدخل معرف الدورة">
            </div>
        </div>

        <div class="tab">
            <div class="col-md-12 font-weight-bold">
                <h4>الطلاب</h4>
                <div class="mb-4 d-flex gap-3">
                    <select id="classDropdown" class="form-select" onchange="filterStudents()">
                        <option value="">اختر الصف</option>
                        <?php
                        $classes = array_unique(array_column($students, 'class'));
                        sort($classes);
                        foreach ($classes as $class) { ?>
                            <option value="<?php echo $class; ?>"><?php echo 'الصف: ' . $class; ?></option>
                        <?php } ?>
                    </select>
                    <input type="text" id="searchInput" class="form-control" onkeyup="filterStudents()" placeholder="ابحث عن طالب...">
                </div>

                <div id="studentCheckboxes" class="list-group">
                    <?php foreach ($students as $student) {
                        if ($student['archived'] != 1) { ?>
                            <div class="list-group-item student-row d-flex align-items-center" data-class="<?php echo $student['class']; ?>">
                                <input class="form-check-input" type="checkbox" name="students[]" id="students2_<?php echo $student['id']; ?>" value="<?php echo $student['id']; ?>" onchange="updateMaterialListVisibility()">
                                <label class="form-check-label mr-4" for="students2_<?php echo $student['id']; ?>">
                                    <?php echo $student['name'] . ' - ' . $student['school_name'] . ' - ' . $student['class']; ?>
                                </label>
                            </div>
                        <?php } ?>
                    <?php } ?>
                </div>
            </div>
        </div>

        <div class="tab">
            <div class="col-md-6 font-weight-bold">
                <h4>الدورات</h4>
                <div class="session_package list-group">
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <label class="form-check-label mr-4" for="1">دورة خاصة</label>
                        <input class="form-check-input" type="radio" name="session_package" id="1" value="دورة خاصة" oninput="getPrivateValue()">
                    </div>
                    <div id="school_backpack" class="list-group-item d-flex justify-content-between align-items-center">
                        <label class="form-check-label mr-4" for="2">حقيبة مدرسية</label>
                        <input class="form-check-input" type="radio" name="session_package" id="2" value="حقيبة مدرسية" oninput="getPrivateValue()">
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <label class="form-check-label mr-4" for="3">اشتراك شهري</label>
                        <input class="form-check-input" type="radio" name="session_package" id="3" value="اشتراك شهري" oninput="getPrivateValue()">
                    </div>
                </div>
            </div>
        </div>

        <div class="tab">
            <div class="col-md-6 font-weight-bold">
                <h4>اختر فئة الإشتراك والسعر</h4>
                <div class="mb-3">
                    <div class="form-check d-flex align-items-center mb-2" id="individual-div">
                        <input class="form-check-input" type="radio" name="is_group" id="individual" value="0">
                        <label class="form-check-label  mr-4" for="individual">فردي</label>
                    </div>
                    <div class="form-check d-flex align-items-center" id="group-div">
                        <input class="form-check-input" type="radio" name="is_group" id="group" value="1">
                        <label class="form-check-label mr-4" for="group">جماعي</label>
                    </div>
                </div>

                <div class="form-group mb-4" id="hours-div">
                    <label for="hours" class="form-label">عدد الساعات (اللقاءات في حال اختيار حقيبة مدرسية):</label>
                    <input type="number" min="0" value="0" class="form-control" id="hours" name="hours" placeholder="عدد الساعات">
                </div>

                <div class="form-group" id="price-div">
                    <label for="price" class="form-label">السعر لكل طالب:</label>
                    <input type="number" class="form-control" min="0" id="price" name="price" placeholder="السعر لكل طالب">
                </div>
            </div>
        </div>

        <div class="tab">
            <div class="row">
                <div id="elementary_materials" class="col-md-4 mt-3 font-weight-bold">
                    <h4>قائمة المواد الأساسية</h4>
                    <div class="list-group">
                        <?php foreach ($materials as $material) {
                            if ($material['class_type'] == 1 && $material['active'] == 0) { ?>
                                <div class="list-group-item d-flex align-items-center">
                                    <input 
                                        class="form-check-input class1" 
                                        type="checkbox" 
                                        name="materials2[]" 
                                        id="material1_<?php echo $material['id']; ?>" 
                                        value="<?php echo $material['id']; ?>"
                                        onchange="filterTeachersByMaterial()">

                                    <label class="form-check-label mr-4" for="material1_<?php echo $material['id']; ?>">
                                        <?php echo $material['name']; ?>
                                    </label>
                                </div>
                        <?php }
                        } ?>
                    </div>
                </div>

                <div id="mid_materials" class="col-md-4 mt-3 font-weight-bold">
                    <h4>قائمة المواد الاعدادية</h4>
                    <div class="list-group">
                        <?php foreach ($materials as $material) {
                            if ($material['class_type'] == 2 && $material['active'] == 0) { ?>
                                <div class="list-group-item d-flex align-items-center">
                                    <input 
                                        class="form-check-input class2" 
                                        type="checkbox" 
                                        name="materials2[]" 
                                        id="material2_<?php echo $material['id']; ?>" 
                                        value="<?php echo $material['id']; ?>" 
                                        onchange="filterTeachersByMaterial()">
                                    <label class="form-check-label mr-4" for="material2_<?php echo $material['id']; ?>">
                                        <?php echo $material['name']; ?>
                                    </label>
                                </div>
                        <?php }
                        } ?>
                    </div>
                </div>

                <div id="secondary_materials" class="col-md-4 mt-3 font-weight-bold">
                    <h4>قائمة المواد الثانوية</h4>
                    <div class="list-group">
                        <?php foreach ($materials as $material) {
                            if ($material['class_type'] == 3 && $material['active'] == 0) { ?>
                                <div class="list-group-item d-flex align-items-center">
                                    <input 
                                        class="form-check-input class3"
                                        type="checkbox"
                                        name="materials2[]"
                                        id="material3_<?php echo $material['id']; ?>" 
                                        value="<?php echo $material['id']; ?>"
                                        onchange="filterTeachersByMaterial()">
                                    <label class="form-check-label mr-4" for="material3_<?php echo $material['id']; ?>">
                                        <?php echo $material['name']; ?>
                                    </label>
                                </div>
                        <?php }
                        } ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab">
            <div class="col-md-12 mt-3 font-weight-bold">
                <h4>المعلمين</h4>
                <div class="mb-3">
                    <input type="text" id="teacherSearch" class="form-control" placeholder="ابحث باسم المعلم..." onkeyup="filterTeachersByName()">
                </div>

                <div id="teachersContainer">
                    <?php foreach ($teacherSpecializations as $teacherId => $teacher) { ?>
                        <div data-teacher-id="<?php echo htmlspecialchars($teacher['id']); ?>">
                            <div class="card mb-3 teacher-card" data-teacher-name="<?php echo htmlspecialchars($teacher['name']); ?>">
                                <div class="card-header bg-light d-flex align-items-center">
                                    <input class="form-check-input" type="checkbox" name="teachers[<?php echo htmlspecialchars($teacher['id']); ?>][id]" id="teacher_<?php echo htmlspecialchars($teacher['id']); ?>" value="<?php echo htmlspecialchars($teacher['id']); ?>">
                                    <label class="form-check-label mr-4" for="teacher_<?php echo htmlspecialchars($teacher['id']); ?>">
                                        <?php echo htmlspecialchars($teacher['name']); ?>
                                    </label>
                                </div>

                                <div class="card-body">
                                    <h6 class="card-title mb-3">التخصصات</h6>
                                    <div class="specializations-container row g-2">
                                        <?php foreach ($teacher['specializations'] as $spec) { ?>
                                            <div class="specialization-item col-md-6">
                                                <input class="form-check-input" type="checkbox" name="teachers[<?php echo htmlspecialchars($teacher['id']); ?>][specializations][]" id="spec_<?php echo htmlspecialchars($teacher['id']) . '_' . htmlspecialchars($spec[0]); ?>" value="<?php echo htmlspecialchars($spec[0]); ?>">
                                                <label class="form-check-label mr-4" for="spec_<?php echo htmlspecialchars($teacher['id']) . '_' . htmlspecialchars($spec[0]); ?>">
                                                    <?php echo htmlspecialchars($spec[1]); ?>
                                                </label>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>

                                <div class="card-footer text-center">
                                    <input type="number" class="form-control percentage-input" name="teachers[<?php echo htmlspecialchars($teacher['id']); ?>][percentage]" id="percentage_<?php echo htmlspecialchars($teacher['id']); ?>" min="0" max="100" step="1" value="<?php echo !empty($teacher['percentage']) ? htmlspecialchars($teacher['percentage']) : ''; ?>" placeholder="أدخل النسبة (0-100)">
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end mt-4 pt-3 border-top">
            <button type="button" class="btn btn-secondary me-2" id="prevBtn" onclick="nextPrev(-1)">السابق</button>
            <button type="button" class="btn btn-primary mr-4" id="nextBtn" onclick="nextPrev(1)">التالي</button>
        </div>
    </form>
</div>

<script>
    function updateMaterialListVisibility() {
        console.log("updateMaterialListVisibility called");

        const selectedStudentsCheckboxes = document.querySelectorAll('input[name="students[]"]:checked');
        const selectedClasses = new Set(); // Use a Set to store unique classes

        selectedStudentsCheckboxes.forEach(checkbox => {
            // Get the parent div with data-class attribute
            const studentRow = checkbox.closest('.student-row');
            if (studentRow) {
                selectedClasses.add(parseInt(studentRow.dataset.class));
            }
        });

        console.log("Selected Classes:", Array.from(selectedClasses));

        let showElementary = false;
        let showMiddle = false;
        let showSecondary = false;

        selectedClasses.forEach(grade => {
            if (grade >= 1 && grade <= 6) {
                showElementary = true;
            } else if (grade >= 7 && grade <= 9) {
                showMiddle = true;
            } else if (grade > 9) {
                showSecondary = true;
            }
        });

        // Update visibility based on selected classes
        document.getElementById("elementary_materials").style.display = showElementary ? "block" : "none";
        document.getElementById("mid_materials").style.display = showMiddle ? "block" : "none";
        document.getElementById("secondary_materials").style.display = showSecondary ? "block" : "none";
    }

    // Initial call to set correct visibility when the page loads
    function filterTeachersByMaterial() {
        console.log("filterTeachersByMaterial called");

        const selectedMaterialIds = new Set();
        // Get all currently checked material checkboxes
        document.querySelectorAll('input[name="materials2[]"]:checked').forEach(checkbox => {
            selectedMaterialIds.add(checkbox.value);
        });

        const teachersContainer = document.getElementById('teachersContainer');
        const teacherCards = teachersContainer.querySelectorAll('.teacher-card');

        teacherCards.forEach(teacherCard => {
            let showTeacherCard = false; // Flag to determine if the entire teacher's card should be shown
            // Get all specialization items for the current teacher
            const teacherSpecializationItems = teacherCard.querySelectorAll('.specialization-item');

            if (selectedMaterialIds.size === 0) {
                // If NO materials are selected, hide all specializations for this teacher
                // and hide the entire teacher card
                teacherSpecializationItems.forEach(specializationItem => {
                    specializationItem.style.display = 'none';
                    // Ensure the checkbox is unchecked if the specialization is hidden
                    const specCheckbox = specializationItem.querySelector('input[type="checkbox"]');
                    if (specCheckbox) {
                        specCheckbox.checked = false;
                    }
                });
                // Hide the whole teacher card
                teacherCard.closest('[data-teacher-id]').style.display = 'none';
            } else {
                // Materials ARE selected, filter specializations based on these
                teacherSpecializationItems.forEach(specializationItem => {
                    const specCheckbox = specializationItem.querySelector('input[type="checkbox"]');
                    if (!specCheckbox) return; // Skip if checkbox not found within item

                    // Check if this specialization's material ID is among the selected materials
                    if (selectedMaterialIds.has(specCheckbox.value)) {
                        specializationItem.style.display = 'block'; // Show this specific specialization
                        specCheckbox.checked = true; // Automatically check this specialization
                        showTeacherCard = true; // Since at least one specialization matches, show the teacher's card
                    } else {
                        specializationItem.style.display = 'none'; // Hide this specific specialization
                        specCheckbox.checked = false; // Ensure it's unchecked if hidden
                    }
                });
                // Finally, control the visibility of the entire teacher card based on matches
                teacherCard.closest('[data-teacher-id]').style.display = showTeacherCard ? 'block' : 'none';
            }
        });
    }

    // Initial call to set correct visibility when the page loads
    document.addEventListener('DOMContentLoaded', () => {
        updateMaterialListVisibility();
        filterTeachersByMaterial(); // Also call this on load
    });

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
    let currentTab = 0;

    document.addEventListener('DOMContentLoaded', function() {
        showTab(currentTab);

        // Ensure teacherSpecializations is defined if used in the backend
        // const teacherSpecializations = <?php echo $teacherSpecializationsJson; ?>;

        // Note: The previous teacher filtering script relied on this PHP variable.
        // If $teacherSpecializationsJson is defined, this event listener should work:
        // document.querySelectorAll('input[name="specializations[]"]').forEach(checkbox => {
        //     checkbox.addEventListener('change', filterTeachersBySpecialization);
        // });
    });

    // --- Stepper Navigation Functions ---

    function showTab(n) {
        const tabs = document.getElementsByClassName("tab");
        if (tabs.length > 0) {
            tabs[n].style.display = "block";
            updateStepperIndicators(n);

            if (n === 0) {
                document.getElementById("prevBtn").style.display = "none";
            } else {
                document.getElementById("prevBtn").style.display = "inline";
            }

            // Change "Next" button text on the last step
            if (n === (tabs.length - 1)) {
                document.getElementById("nextBtn").innerHTML = "إرسال";
            } else {
                document.getElementById("nextBtn").innerHTML = "التالي";
            }
        }
    }

    function nextPrev(n) {
        const tabs = document.getElementsByClassName("tab");

        // Validate the current step before moving forward
        if (n === 1 && !validateForm(currentTab)) {
            // Don't proceed if validation fails
            return false;
        }

        // Hide the current tab
        tabs[currentTab].style.display = "none";

        // Move to the next/previous tab
        currentTab = currentTab + n;

        // Submit if we're past the last tab
        if (currentTab >= tabs.length) {
            const teacherGroups = document.querySelectorAll('[data-teacher-id]');
            teacherGroups.forEach(group => {
                const checkbox = group.querySelector('input[type="checkbox"]');
                const inputs = group.querySelectorAll('input');
                if (!checkbox.checked) {
                    inputs.forEach(input => input.disabled = true);
                }
            });

            document.getElementById("multiStepForm").submit();
            return false;
        }

        showTab(currentTab);
    }

    function validateForm(step) {
        switch (step) {
            case 0: // Step 1: session_name
                const sessionName = document.getElementById('session_name');
                if (!sessionName.value.trim()) {
                    alert("يرجى إدخال معرف الدورة.");
                    sessionName.focus();
                    return false;
                }
                break;

            case 1: // Step 2: students
                const studentCheckboxes = document.querySelectorAll('input[name="students[]"]:checked');
                if (studentCheckboxes.length === 0) {
                    alert("يرجى اختيار طالب واحد على الأقل.");
                    return false;
                }
                break;

            case 2: // Step 3: session_package
                const packageSelected = document.querySelector('input[name="session_package"]:checked');
                if (!packageSelected) {
                    alert("يرجى اختيار نوع الدورة.");
                    return false;
                }
                break;

            case 3: // Step 4: is_group, price, hours
                const groupSelected = document.querySelector('input[name="is_group"]:checked');
                const price = document.getElementById('price');
                const hours = document.getElementById('hours');
                if (!groupSelected) {
                    alert("يرجى اختيار نوع الاشتراك.");
                    return false;
                }
                if (!price.value || parseFloat(price.value) <= 0) {
                    alert("يرجى إدخال سعر صحيح.");
                    price.focus();
                    return false;
                }
                if (!hours.value || parseFloat(hours.value) < 0) {
                    alert("يرجى إدخال عدد الساعات.");
                    hours.focus();
                    return false;
                }
                break;

            case 4: // Step 5: materials2[]
                const materialChecked = document.querySelectorAll('input[name="materials2[]"]:checked');
                if (materialChecked.length === 0) {
                    alert("يرجى اختيار مادة واحدة على الأقل.");
                    return false;
                }
                break;

            case 5: // Step 6: teachers with specializations and percentage
                const teacherCheckboxes = document.querySelectorAll('input[type="checkbox"][name$="[id]"]:checked');
                let validTeacher = false;
                teacherCheckboxes.forEach(cb => {
                    const id = cb.value;
                    const percentage = document.querySelector(`input[name="teachers[${id}][percentage]"]`);
                    const specs = document.querySelectorAll(`input[name="teachers[${id}][specializations][]"]:checked`);
                    if (percentage && specs.length > 0 && parseFloat(percentage.value) > 0) {
                        validTeacher = true;
                    }
                });

                if (!validTeacher) {
                    alert("يرجى اختيار معلم واحد على الأقل وتحديد تخصصه ونسبته.");
                    return false;
                }
                break;
        }

        return true;
    }

    // Update the stepper progress indicators
    function updateStepperIndicators(n) {
        const indicators = document.getElementsByClassName("step-indicator");
        // Remove 'active' and 'complete' classes from all indicators
        for (let i = 0; i < indicators.length; i++) {
            indicators[i].classList.remove("active");
            indicators[i].classList.remove("complete");
        }

        // Add 'complete' to previous steps and 'active' to the current step
        for (let i = 0; i < n; i++) {
            indicators[i].classList.add("complete");
        }
        if (indicators[n]) {
            indicators[n].classList.add("active");
        }
    }

    // --- Student Filtering and Search (Step 2) ---

    // This function handles filtering students by class dropdown and name search input
    function filterStudents() {
        const classDropdown = document.getElementById('classDropdown');
        const searchInput = document.getElementById('searchInput');
        const selectedClass = classDropdown.value;
        const searchTerm = searchInput.value.toLowerCase();
        const container = document.getElementById('studentCheckboxes');

        // Clear existing content
        container.innerHTML = '';

        // Filter and rebuild student list
        <?php foreach ($students as $student) {
            if ($student['archived'] != 1) { ?>
                // PHP generates JS condition - more efficient than filtering in JS
                if ((!selectedClass || '<?php echo $student["class"]; ?>' === selectedClass) &&
                    (!searchTerm || '<?php echo strtolower($student["name"] . ' - ' . $student["school_name"] . ' - ' . $student["class"]); ?>'.includes(searchTerm))) {

                    const studentDiv = document.createElement('div');
                    studentDiv.className = 'list-group-item student-row d-flex align-items-center';
                    studentDiv.setAttribute('data-class', '<?php echo $student["class"]; ?>');

                    studentDiv.innerHTML = `
                    <input class="form-check-input" type="checkbox"
                           name="students[]"
                           id="students2_<?php echo $student['id']; ?>"
                           value="<?php echo $student['id']; ?>"
                           >
                    <label class="form-check-label mr-4" for="students2_<?php echo $student['id']; ?>">
                        <?php echo $student['name'] . ' - ' . $student['school_name'] . ' - ' . $student['class']; ?>
                    </label>
                `;

                    container.appendChild(studentDiv);
                }
        <?php }
        } ?>
    }

    function filterTeachersByName() {
        const input = document.getElementById('teacherSearch');
        const filter = input.value.toUpperCase();
        const cards = document.querySelectorAll('.teacher-card');

        cards.forEach(card => {
            const teacherName = card.getAttribute('data-teacher-name').toUpperCase();
            if (teacherName.includes(filter)) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    }

    // Filters teachers based on specialization checkboxes (used in the original script provided)
    function filterTeachersBySpecialization() {
        // This function requires the `teacherSpecializations` object to be defined from PHP.
        // If you are using this function, ensure the PHP variable is loaded:
        // const teacherSpecializations = <?php echo $teacherSpecializationsJson; ?>;

        const selectedSpecializations = Array.from(document.querySelectorAll('input[name="specializations[]"]:checked'))
            .map(checkbox => checkbox.value);

        if (selectedSpecializations.length === 0) {
            document.querySelectorAll('.teacher-row').forEach(row => row.style.display = 'block');
            return;
        }

        document.querySelectorAll('.teacher-row').forEach(row => {
            const teacherId = row.dataset.teacherId;
            // Assuming teacherSpecializations is available
            const teacherData = teacherSpecializations[teacherId] || {
                specializations: []
            };
            const teacherSpecializationIds = teacherData.specializations.map(spec => spec[0]);

            const hasMatchingSpecialization = selectedSpecializations.some(specId => teacherSpecializationIds.includes(specId));
            row.style.display = hasMatchingSpecialization ? 'block' : 'none';
        });
    }
</script>