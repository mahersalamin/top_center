<?php require 'header.php'; ?>
    <style>


        h1 {
            text-align: center;
        }


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


    </style>


<?php

if (!isset($_COOKIE['id'])) {
    header("location:signin.php");
}
$db = new MyDB();
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
            <a class="nav-link active" id="specs-tab" data-toggle="tab" href="#specs" role="tab" aria-controls="specs"
               aria-selected="false">نموذج إضافة تخصص</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="specs-tab" data-toggle="tab" href="#all-specs" role="tab" aria-controls="all-specs"
               aria-selected="false">كل التخصصات</a>
        </li>
    </ul>

    <div class="tab-content">

        <div class="tab-pane  fade show active" id="specs" role="tabpanel" aria-labelledby="specs-tab">
            <!-- Specs Form -->
            <div class="container col-md-6 shadow p-3 bg-body rounded mb-2 text-center" style="font-family: 'Cairo'">

                <form action="../addSpecialization.php" method="POST">
                    <div class="mb-3">
                        <label for="new_spec" class="form-label">اسم التخصص الجديد:</label>
                        <input type="text" class="form-control" id="new_spec" name="new_spec" required>
                    </div>

                    <div class="mb-3">
                        <label for="new_spec" class="form-label">المرحلة الدراسية:</label>
                        <select class="form-control" name="class_type" required>
                            <option selected disabled>اختر المرحلة الدراسية</option>
                            <option value="1">ابتدائي</option>
                            <option value="2">اعدادي</option>
                            <option value="3">ثانوي</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">إضافة التخصص</button>
                </form>
            </div>
        </div>
        <div class="tab-pane fade show" id="all-specs" role="tabpanel" aria-labelledby="all-specs-tab">
            <!-- Specs Form -->
            <div class="container col-md-10 shadow p-3 bg-body rounded mb-2 text-center" style="font-family: 'Cairo'">
                <?php $specs = $db->getSpecializations(); ?>

                <input type="text" id="searchInput" class="form-control mb-3" placeholder="ابحث عن التخصص...">

                <div id="specsContainer">
                    <?php foreach ($specs as $spec) { ?>
                        <div class="row spec-item">
                            <form action="../updateSpecialization.php" method="POST" class="d-flex align-items-center w-100">
                                <div class="col-md-4 mb-3">
                                    <label for="new_spec" class="form-label">اسم التخصص</label>
                                    <input type="text" value="<?=$spec['name']?>" class="form-control" id="<?=$spec['id']?>"
                                           name="new_spec"
                                           required>
                                    <input hidden name="id" value="<?=$spec['id']?>">
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label for="class_type" class="form-label">المرحلة الدراسية:</label>
                                    <select class="form-control" name="class_type" required>
                                        <option selected disabled>
                                            <?php
                                            switch ($spec['class_type']) {
                                                case 1: print "ابتدائي"; break;
                                                case 2: print "اعدادي"; break;
                                                case 3: print "ثانوي"; break;
                                            }
                                            ?>
                                        </option>
                                        <option value="1">ابتدائي</option>
                                        <option value="2">اعدادي</option>
                                        <option value="3">ثانوي</option>
                                    </select>

                                </div>
                                <div class="col-md-3">
                                    <label for="active1">أرشفة</label>
                                    <input type="radio" id="active1" name="active" value="1" <?php echo ($spec['active'] == 1) ? 'checked' : ''; ?>>

                                    <label for="active2">إلغاء</label>
                                    <input type="radio" id="active2" name="active" value="0" <?php echo ($spec['active'] == 0) ? 'checked' : ''; ?>>
                                </div>

                                <div class="col-md-2 mb-3 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary w-100">تعديل</button>
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
            const specItems = document.querySelectorAll('.spec-item');

            searchInput.addEventListener('input', function () {
                const searchTerm = searchInput.value.toLowerCase();
                specItems.forEach(item => {
                    const specName = item.querySelector('input[name="new_spec"]').value.toLowerCase();
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