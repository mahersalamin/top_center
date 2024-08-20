<?php require '../dbconnection.php'; ?>
<?php require 'header.php'; ?>

<?php
$id = $_POST['id'];
$db = new MyDB();

$teacher = $db->getTeacherData($id);
$teacher = $teacher[0];
$specializations = $db->getSpecializations();
$teacher_specializations = $db->getTeacherSpecializations($id);

if ($teacher) {
    ?>
    <div class="container my-5">
        <div class="text-center">
            <div class="col-md-5 shadow p-1 bg-body rounded mx-auto mb-4">
                <img class="img-fluid" src="../upload/<?php echo htmlspecialchars($teacher['img']); ?>"
                     alt="Teacher Image">
            </div>

            <h1 class="display-4 fw-bold text-success"><?php echo htmlspecialchars($teacher['name']); ?></h1>
        </div>

        <div class="col-lg-6 mx-auto">
            <form id="teacherForm" action="../updateTeacher.php" method="POST" enctype="multipart/form-data">
                <div class="card mb-4 text-right">
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="name" class="form-label fw-bold">اسم المعلم</label>
                            <input type="text" class="form-control" id="name" name="name"
                                   value="<?php echo htmlspecialchars($teacher['name']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label fw-bold">ايميل المعلم</label>
                            <input type="email" class="form-control" id="email" name="email"
                                   value="<?php echo htmlspecialchars($teacher['user']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="specSearchInput" class="form-label fw-bold">اختر التخصصات</label>
                            <input type="text" id="specSearchInput" class="form-control mb-2"
                                   placeholder="ابحث عن تخصص...">
                            <div id="specsContainer">
                                <?php foreach ($specializations as $spec) {
                                    $checked = in_array($spec['id'], array_column($teacher_specializations, 'spec'));
                                    ?>
                                    <div class="form-check">
                                        <input class="form-check-input spec-checkbox " type="checkbox"
                                               name="specs[<?php echo htmlspecialchars($spec['id']); ?>][price]"
                                               value="50"
                                               id="spec_<?php echo htmlspecialchars($spec['id']); ?>" <?php echo $checked ? 'checked' : ''; ?>>
                                        <label class="form-check-label mr-3"
                                               for="spec_<?php echo htmlspecialchars($spec['id']); ?>"><?php echo htmlspecialchars($spec['name']); ?></label>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>

                <input type="hidden" name="id" value="<?php echo htmlspecialchars($teacher['id']); ?>">
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                    <a class="btn btn-secondary"
                       href="<?php echo $_COOKIE['role'] == 2 ? "bodyHomeUser.php" : ($_COOKIE['role'] == 1 ? "homeAdmin.php" : ""); ?>">رجوع
                        &gt;</a>
                </div>
            </form>
            <div class="d-flex justify-content-center mt-5">
                <form method="post" action="../deleteTeacher.php" class="d-inline-block">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($teacher['id']); ?>">
                    <button type="submit" class="btn btn-danger">أرشفة المعلم</button>
                </form>
            </div>

        </div>
    </div>

    <script>
        document.getElementById('specSearchInput').addEventListener('keyup', function () {
            var input = this.value.toLowerCase();
            var labels = document.querySelectorAll('#specsContainer .form-check');

            labels.forEach(function (label) {
                var text = label.querySelector('label').textContent.toLowerCase();
                if (text.includes(input)) {
                    label.style.display = '';
                } else {
                    label.style.display = 'none';
                }
            });
        });

        document.getElementById('teacherForm').addEventListener('submit', function () {
            var checkboxes = document.querySelectorAll('.spec-checkbox');
            var uncheckedCheckboxes = [];

            checkboxes.forEach(function (checkbox) {
                if (!checkbox.checked) {
                    uncheckedCheckboxes.push(checkbox.name);
                }
            });

            uncheckedCheckboxes.forEach(function (name) {
                var checkbox = document.querySelector(`input[name="${name}"]`);
                if (checkbox) {
                    checkbox.remove();
                }
            });
        });
    </script>
<?php } ?>

<?php require 'footer.php'; ?>
