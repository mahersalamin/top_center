<?php require '../dbconnection.php'; ?>
<?php require 'header.php'; ?>

<?php
$id = $_POST['id'];
$db = new MyDB();

$teacher = $db->getTeacherData($id);
$teacher=$teacher[0];
$specializations = $db->getSpecializations();
$teacher_specializations = $db->getTeacherSpecializations($id);

if ($teacher) {
?>
    <div class="px-4 py-5 my-5 text-center">
        <form id="teacherForm" action="../updateTeacher.php" method="POST" enctype="multipart/form-data">
            <div class="col-md-5 shadow p-1 bg-body rounded d-block mx-auto mb-4">
                <img class="img-fluid" src="../upload/<?php echo htmlspecialchars($teacher['img']); ?>" alt="">
            </div>

            <h1 class="display-4 fw-bold" style="color: green; display: inline;"><?php echo htmlspecialchars($teacher['name']); ?></h1>

            <div class="col-lg-5 mx-auto pt-4">
                <div class="gap-5">
                    <ul class="list-group">
                        <li class="list-group-item">
                            <label for="name" style="display: inline; font-weight: bold">اسم المعلم</label>
                            <br><br>
                            <input class="form-control" type="text" id="name" name="name" value="<?php echo htmlspecialchars($teacher['name']); ?>">
                        </li>
                        <li class="list-group-item">
                            <label for="email" style="color: black; display: inline; font-weight: bold">ايميل المعلم :</label>
                            <br><br>
                            <input class="form-control" type="email" id="email" name="email" value="<?php echo htmlspecialchars($teacher['user']); ?>">
                        </li>
                        <li class="list-group-item">
                            <label style="color: black; display: inline; font-weight: bold" for="specs">اختر التخصصات:</label>
                            <br><br>
                            <input type="text" id="specSearchInput" class="form-control" placeholder="ابحث عن تخصص...">
                            <div id="specsContainer" class="mt-2">
                                <?php foreach ($specializations as $spec) {
                                    $checked = false;
                                    foreach ($teacher_specializations as $teacher_spec) {
                                        if ($teacher_spec['spec'] == $spec['id']) {
                                            $checked = true;
                                        }
                                    }
                                ?>
                                    <div class="form-check">
                                        <input class="form-check-input spec-checkbox" type="checkbox"
                                               name="specs[<?php echo htmlspecialchars($spec['id']); ?>][price]"
                                               value="50"
                                               id="spec_<?php echo htmlspecialchars($spec['id']); ?>" <?php echo $checked ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="spec_<?php echo htmlspecialchars($spec['id']); ?>"><?php echo htmlspecialchars($spec['name']); ?></label>
                                    </div>
                                <?php } ?>
                            </div>
                        </li>
                    </ul>

                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($teacher['id']); ?>">
                    <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                    <a class="btn btn-secondary"
                       href="<?php echo $_COOKIE['role'] == 2 ? "bodyHomeUser.php" : ($_COOKIE['role'] == 1 ? "homeAdmin.php" : ""); ?>">
                        رجوع &gt;
                    </a>
                </div>
            </div>
        </form>

        <!-- JavaScript to filter specializations and handle form submission -->
        <script>
            document.getElementById('specSearchInput').addEventListener('keyup', function() {
                var input = this.value.toLowerCase();
                var labels = document.querySelectorAll('#specsContainer .form-check');

                labels.forEach(function(label) {
                    var text = label.querySelector('label').textContent.toLowerCase();
                    if (text.includes(input)) {
                        label.style.display = '';
                    } else {
                        label.style.display = 'none';
                    }
                });
            });

            // Only include checked checkboxes in the form submission
            document.getElementById('teacherForm').addEventListener('submit', function() {
                var checkboxes = document.querySelectorAll('.spec-checkbox');
                var uncheckedCheckboxes = [];
                
                checkboxes.forEach(function(checkbox) {
                    if (!checkbox.checked) {
                        uncheckedCheckboxes.push(checkbox.name);
                    }
                });
                
                uncheckedCheckboxes.forEach(function(name) {
                    var checkbox = document.querySelector(`input[name="${name}"]`);
                    if (checkbox) {
                        checkbox.remove();
                    }
                });
            });
        </script>
<?php } ?>

<?php require 'footer.php'; ?>
