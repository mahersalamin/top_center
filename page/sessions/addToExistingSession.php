<?php
$students = $db->allStudents();
$sessions = $db->getSessionsDataDetailed();

?>


<!--    تعيين الى دورات    -->
<div class="tab-pane fade show" id="student-to-package" role="tabpanel"
     aria-labelledby="student-to-package-tab">
    <form id="studentToPackageForm" method="post" enctype="application/x-www-form-urlencoded"
          action="../../assignPackages.php">
        <label>
            <input hidden name="pkg" value="student-to-package">
        </label>

        <!-- Step 1: Select Students -->
        <div class="tab1">
            <div class="col-md-12 font-weight-bold">
                <h4>الطلاب</h4>
                <div class="mb-3">
                    <label for="classDropdown2"></label><select id="classDropdown2" class="form-control mb-2" onchange="filterStudents2()">
                        <option value="">اختر الصف</option>
                        <?php
                        // Get unique classes
                        $classes = array_unique(array_column($students, 'class'));
                        sort($classes);
                        foreach ($classes as $class) { ?>
                            <option value="<?php echo $class; ?>"><?php echo 'الصف: ' . $class; ?></option>
                        <?php } ?>
                    </select>
                    <label for="searchInput2"></label><input type="text" id="searchInput2" class="form-control" onkeyup="filterStudents2()"
                                                             placeholder="ابحث عن طالب...">
                </div>
                <div id="studentCheckboxes">
                    <?php foreach ($students as $student) {
                        if($student['archived'] !=1){?>
                        <div class="mr-2 mb-2 form-check row justify-content-center student-row2"
                             data-class="<?php echo $student['class']; ?>">
                            <input class="form-check-input" type="checkbox" name="students[]"
                                   id="session_<?php echo $student['id']; ?>"
                                   value="<?php echo $student['id']; ?>">
                            <label class="mr-2 form-check-label" for="session_<?php echo $student['id']; ?>">
                                <?php echo $student['name'] . ' - ' . $student['school_name'] . ' - ' . $student['class']; ?>
                            </label>
                        </div>
                    <?php } } ?>
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

        <!-- Step 3: Select Materials
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
        </div> -->

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
            <!-- <span class="step1"></span> -->
        </div>
    </form>
</div>

