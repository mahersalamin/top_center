<?php ?>

<!--    اضافة دورات جديدة    -->
<div class="tab-pane fade show active" id="new-package" role="tabpanel" aria-labelledby="new-package-tab">
    <form id="multiStepForm" method="post" enctype="application/x-www-form-urlencoded"
          action="../assignPackages.php">
        <label>
            <input hidden name="pkg" value="package">
        </label>
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
            <div class="col-md-12 font-weight-bold">
                <h4>الطلاب</h4>
                <div class="mb-3">
                    <select id="classDropdown" class="form-select mb-2" onchange="filterStudents()">
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
                    <?php foreach ($students as $student) {
                        if ($student['archived'] != 1) { ?>
                            <div class="mr-2 mb-2 form-check row justify-content-center student-row"
                                 data-class="<?php echo $student['class']; ?>">
                                <input class="form-check-input" type="checkbox" name="students[]"
                                       id="students2_<?php echo $student['id']; ?>"
                                       value="<?php echo $student['id']; ?>"
                                       oninput="getClassValue(<?= $student['class'] ?>, this)">
                                <label class="mr-2 form-check-label"
                                       for="students2_<?php echo $student['id']; ?>">
                                    <?php echo $student['name'] . ' - ' . $student['school_name'] . ' - ' . $student['class']; ?>
                                </label>
                            </div>
                        <?php } ?>
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
                    <label for="hours" class="col-form-label">عدد الساعات (اللقاءات في حال اختيار حقيبة
                        مدرسية):</label>
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
                <div id="elementary_materials" class="col-md-4 mt-3 font-weight-bold"
                     style="height: 350px; overflow-y: auto;">
                    <h4>قائمة المواد الأساسية</h4>

                    <?php foreach ($materials as $material) {
                        if ($material['class_type'] == 1 && $material['active'] == 0) { ?>
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
                <div id="mid_materials" class="col-md-4 mt-3 font-weight-bold"
                     style="height: 350px; overflow-y: auto;">
                    <h4>قائمة المواد الاعدادية</h4>

                    <?php foreach ($materials as $material) {
                        if ($material['class_type'] == 2 && $material['active'] == 0) { ?>
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
                <div id="secondary_materials" class="col-md-4 mt-3 font-weight-bold"
                     style="height: 350px; overflow-y: auto;">
                    <h4>قائمة المواد الثانوية</h4>

                    <?php foreach ($materials as $material) {
                        if ($material['class_type'] == 3 && $material['active'] == 0) { ?>
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
            <div class="col-md-8 mt-3 font-weight-bold" style="height: 400px; overflow-y: auto;">
                <div>
                    <p><strong>المعلمين</strong></p>

                    <?php foreach ($teacherSpecializations as $teacherId => $teacher) { ?>
                        <div class="card mb-3 teacher-card">
                            <!-- Card Header: Teacher Name -->
                            <div class="card-header">
                                <input class="form-check-input" type="checkbox"
                                       name="teachers[<?php echo htmlspecialchars($teacher['id']); ?>][id]"
                                       id="teacher_<?php echo htmlspecialchars($teacher['id']); ?>"
                                       value="<?php echo htmlspecialchars($teacher['id']); ?>">
                                <label class="form-check-label mr-4"
                                       for="teacher_<?php echo htmlspecialchars($teacher['id']); ?>">
                                    <?php echo htmlspecialchars($teacher['name']); ?>
                                </label>
                            </div>

                            <!-- Card Body: Specializations -->
                            <div class="card-body">
                                <h5 class="card-title">التخصصات</h5>
                                <div class="specializations-container">
                                    <?php foreach ($teacher['specializations'] as $spec) { ?>
                                        <div class="specialization-item">
                                            <input class="form-check-input"
                                                   type="checkbox"
                                                   name="teachers[<?php echo htmlspecialchars($teacher['id']); ?>][specializations][]"
                                                   id="spec_<?php echo htmlspecialchars($teacher['id']) . '_' . htmlspecialchars($spec[0]); ?>"
                                                   value="<?php echo htmlspecialchars($spec[0]); ?>">
                                            <label class="form-check-label mr-4"
                                                   for="spec_<?php echo htmlspecialchars($teacher['id']) . '_' . htmlspecialchars($spec[0]); ?>">
                                                <?php echo htmlspecialchars($spec[1]); ?>
                                            </label>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>

                            <!-- Card Footer: Percentage -->
                            <div class="card-footer text-center">
                                <select class="form-select percentage-select"
                                        name="teachers[<?php echo htmlspecialchars($teacher['id']); ?>][percentage]"
                                        id="percentage_<?php echo htmlspecialchars($teacher['id']); ?>">
                                    <option value="" <?php echo $teacher['percentage'] === '' ? 'selected' : ''; ?> disabled>اختر النسب</option>
                                    <option value="50" <?php echo $teacher['percentage'] === '50' ? 'selected' : ''; ?>>50%</option>
                                    <option value="75" <?php echo $teacher['percentage'] === '75' ? 'selected' : ''; ?>>75%</option>
                                </select>
                            </div>
                        </div>
                    <?php } ?>


                </div>
            </div>

        </div>
        <!-- Navigation Buttons -->
        <div style="overflow:auto;">
            <div style="float:right;">
                <button type="button" class="btn btn-secondary" id="prevBtn" onclick="nextPrev(-1)">السابق
                </button>
                <button type="button" class="btn btn-primary" id="nextBtn" onclick="nextPrev(1)">التالي
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


<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Convert the PHP data into a JavaScript object
        const teacherSpecializations = <?php echo $teacherSpecializationsJson; ?>;

        // Function to filter teachers based on selected specializations
        function filterTeachers() {
            // Get selected specialization IDs
            const selectedSpecializations = Array.from(document.querySelectorAll('input[name="specializations[]"]:checked'))
                .map(checkbox => checkbox.value);

            // If no specializations selected, show all teachers
            if (selectedSpecializations.length === 0) {
                document.querySelectorAll('.teacher-row').forEach(row => row.style.display = 'block');
                return;
            }

            // Filter teachers
            document.querySelectorAll('.teacher-row').forEach(row => {
                const teacherId = row.dataset.teacherId;
                const teacherData = teacherSpecializations[teacherId] || {
                    specializations: []
                };

                // Extract specialization IDs from the teacher's data
                const teacherSpecializationIds = teacherData.specializations.map(spec => spec[0]);

                // Check if any of the selected specializations match the teacher's specializations
                const hasMatchingSpecialization = selectedSpecializations.some(specId => teacherSpecializationIds.includes(specId));
                row.style.display = hasMatchingSpecialization ? 'block' : 'none';
            });
        }

        // Add event listeners to specialization checkboxes
        document.querySelectorAll('input[name="specializations[]"]').forEach(checkbox => {
            checkbox.addEventListener('change', filterTeachers);
        });

        // Initial call to set the default state
        filterTeachers();
    });
</script>