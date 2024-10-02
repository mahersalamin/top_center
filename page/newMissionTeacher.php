<?php require 'header.php'; ?>
<style>

    h1 {
        text-align: center;
    }

    input.invalid {
        background-color: #ffdddd;
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


</style>


<?php

if (!isset($_COOKIE['id'])) {
    header("location:signin.php");
}
$db = new MyDB();
?>

<br>
<br>

<?php
if (isset($_GET['message']) && isset($_GET['status'])) {
    $message = $_GET['message'];
    $status = $_GET['status'];

    if ($status === "success") {
        echo '<div class="alert alert-success alert-dismissible" role="alert">' . $message . '<span type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></span>' . '</div>';
    } elseif ($status === "error") {
        echo '<div class="alert alert-danger alert-dismissible" role="alert">' . $message . '<span type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></span>' . '</div>';
    }
}
?>

<ul class="nav nav-tabs justify-content-center mb-4">
    <li class="nav-item">
        <a class="nav-link active" id="teachers-tab" data-toggle="tab" href="#teachers" role="tab"
           aria-controls="teachers"
           aria-selected="false">نموذج إضافة معلم</a>
    </li>

</ul>

<div class="tab-content">
    <div class="tab-pane fade show active" id="teachers" role="tabpanel" aria-labelledby="teachers-tab">
        <div class="container col-md-6 shadow p-3 bg-body rounded mb-2 text-center" style="font-family: 'Cairo'">
            <h4 class="text-info text-center font-weight-bold">إضافة معلم جديد</h4>
            <br>

            <form action="../addTeacher.php" method="POST" enctype="multipart/form-data">
                <div class="form-row-md-4 row">
                    <div class="form-group col-md-6 mb-2">
                        <input required type="text" name="email" class="form-control" placeholder="👤 ايميل المعلم">
                    </div>
                    <div class="form-group col-md-6 mb-2">
                        <input required type="text" name="name" class="form-control" placeholder="👤 اسم المعلم">
                    </div>
                    <div class="form-group col-md-6 mb-2">
                        <input required type="password" name="password" class="form-control"
                               placeholder="🔑 كلمة السر للمعلم">
                    </div>
                    <div class="form-group col-md-6 mb-2">
                        <input required type="number" name="id_number" maxlength="9" minlength="9" min="111111111" max="999999999" class="form-control" placeholder="رقم الهوية">
                    </div>
                    <div class="form-group col-md-6 mb-2">
                        <input required type="text" name="degree" class="form-control" placeholder="الدرجة العلمية">
                    </div>
                    <div class="form-group col-md-6 mb-2">
                        <input required type="text" name="phone_number" class="form-control" placeholder="رقم الهاتف">
                    </div>
                    <div class="form-group col-md-6 mb-2">
                        <input required type="text" name="address" class="form-control" placeholder="العنوان">
                    </div>
                    <div class="form-group col-md-6 mb-2 ">
                        <input class="col-md form-control" type="file" name="file" title="Add photo">
                    </div>

                    <div class="form-group col-md-12 mb-2">
                        <label class="font-weight-bold" for="filterSelect">التخصصات:</label><br>
                        <select id="filterSelect" class="form-select mb-2">
                            <option value="">كل المراحل الدراسية</option>
                            <option value="1">ابتدائي</option>
                            <option value="2">اعدادي</option>
                            <option value="3">ثانوي</option>
                            <!-- Add more class types as needed -->
                        </select>
                        <?php $specs = $db->getSpecializations(); ?>
                        <input type="text" id="searchInput" class="form-control mb-2" placeholder="اكتب للبحث...">

                        <div class="form-group col-md-12 mb-2" style="height: 350px; overflow-y: auto;">
                            <?php foreach ($specs as $spec) {
                                if($spec['active'] != 1){    ?>
                                <div class="mr-2 mb-2 form-check row justify-content-center  spec-item" data-class-type="<?php echo $spec['class_type']; ?>">
                                    <input class="form-check-input" type="checkbox"
                                           name="specs[<?php echo $spec['id']; ?>][id]"
                                           value="<?php echo $spec['id']; ?>" id="spec_<?php echo $spec['id']; ?>">
                                    <label class="form-check-label"
                                           for="spec_<?php echo $spec['id']; ?>"><?php echo $spec['name']; ?></label>
                                </div>
                            <?php }} ?>
                        </div>
                    </div>

                    <input type="hidden" name="role" value="2">

                    <button type="submit" class="btn btn-info text-white font-weight-bold">إضافة</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('searchInput');
        const filterSelect = document.getElementById('filterSelect');
        const specItems = document.querySelectorAll('.spec-item');

        searchInput.addEventListener('input', function () {
            const searchTerm = searchInput.value.toLowerCase();
            filterSpecs(searchTerm, filterSelect.value);
        });

        filterSelect.addEventListener('change', function () {
            const filterTerm = filterSelect.value;
            filterSpecs(searchInput.value.toLowerCase(), filterTerm);
        });

        function filterSpecs(searchTerm, filterTerm) {
            specItems.forEach(item => {
                const text = item.textContent.toLowerCase();
                const classType = item.getAttribute('data-class-type');
                const matchesSearch = text.includes(searchTerm);
                const matchesFilter = filterTerm === '' || classType === filterTerm;
                if (matchesSearch && matchesFilter) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        }
    });
</script>

<?php require 'ut/datepicker.php'; ?>
