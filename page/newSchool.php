<?php require 'header.php'; ?>
<style>

    h1 {
        text-align: center;
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
        <a class="nav-link active" id="new-school-tab" data-toggle="tab" href="#new_school" role="tab"
           aria-controls="teachers"
           aria-selected="false">إضافة مدرسة جديدة</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="schools-tab" data-toggle="tab" href="#schools" role="tab"
           aria-controls="teachers"
           aria-selected="false">قائمة المدارس</a>
    </li>

</ul>

<div class="tab-content">
    <div class="tab-pane fade show active" id="new_school" role="tabpanel" aria-labelledby="new-school-tab">
        <div class="container col-md-6 shadow p-3 bg-body rounded mb-2 text-center" style="font-family: 'Cairo'">
            <h4 class="text-info text-center font-weight-bold">إضافة مدرسة جديدة</h4>
            <br>

            <form action="../addSchool.php" method="POST" enctype="multipart/form-data">
                <div class="form-row-md-4 row">

                    <div class="form-group col-md-12 mb-2">
                        <input required type="text" name="name" class="form-control" placeholder="👤 اسم المدرسة">
                    </div>


                    <div class="form-group col-md-12 mb-2">
                        <label for="filterSelect">نوع المدرسة:</label>
                        <select id="filterSelect" name="type" class="form-control mb-2">
                            <option value=""></option>
                            <option value="1">مدرسة حكومية</option>
                            <option value="2">مدرسة خاصة</option>

                        </select>

                    </div>



                    <button type="submit" class="btn btn-info text-white font-weight-bold">إضافة</button>
                </div>
            </form>
        </div>
    </div>
    <div class="tab-pane fade show" id="schools" role="tabpanel" aria-labelledby="schools-tab">
        <!-- Specs Form -->
        <div class="container col-md-6 shadow p-3 bg-body rounded mb-2 text-center" style="font-family: 'Cairo'">
            <?php $schools = $db->getSchools();?>

            <input type="text" id="searchInput" class="form-control mb-3" placeholder="ابحث عن مدرسة...">

            <div id="specsContainer">
                <?php foreach ($schools as $school) { ?>
                    <div class="row school-item">
                        <form action="editSchool.php" method="POST" class="d-flex align-items-center col-md-12 w-100">
                            <div class="col-md-5 mb-3">
                                <label for="new_school_<?=$school['id']?>" class="form-label">اسم المدرسة</label>
                                <input type="text" value="<?=$school['name']?>" class="form-control" id="new_school_<?=$school['id']?>"
                                       name="new_school"
                                       required>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="type" class="form-label">نوع المدرسة:</label>
                                <select class="form-control" id="type" name="type" required>
                                    <option selected disabled class="text-secondary">
                                        <?php
                                        switch ($school['type']) {
                                            case 1: print "مدرسة حكومية"; break;
                                            case 2: print "مدرسة خاصة"; break;
                                        }
                                        ?>
                                    </option>
                                    <option value="1">مدرسة حكومية</option>
                                    <option value="2">مدرسة خاصة</option>
                                </select>
                            </div>

                            <div class="col-md-3 mt-3 d-flex align-items-end justify-content-between">
                                <input hidden name="id" value="<?= $school['id'] ?>">
                                <button type="submit" value="1" name="action" class="btn btn-primary ml-2 w-100">تعديل</button>
                                <?php if($school['is_archived'] == 0){ ?>
                                <button type="submit" value="2" name="action" class="btn btn-danger mr-2 w-100">أرشفة</button>
                                <?php } else { ?>
                                <button type="submit" value="3" name="action" class="btn btn-danger mr-2 w-100">إستعادة</button>
                                <?php } ?>
                            </div>
                        </form>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('searchInput');
        const specItems = document.querySelectorAll('.school-item');

        searchInput.addEventListener('input', function () {
            const searchTerm = searchInput.value.toLowerCase();
            specItems.forEach(item => {
                const specName = item.querySelector('input[name="new_school"]').value.toLowerCase();
                if (specName.includes(searchTerm)) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });
</script>

<?php require 'ut/datepicker.php'; ?>
