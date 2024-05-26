<?php require 'header.php'; $db = new MyDB(); ?>

<?php
$user = $db->getSingleTeacher($_COOKIE['id']);
foreach ($user as $row) {
    ?>

    <div class="container mt-5">

        <div class="text-center mt-3">
            <h1><?php echo $row['name']; ?></h1>
        </div>
        <div class="text-center mt-2">
            <h3><?php echo $row['user']; ?></h3>
        </div>
        <div class="text-center mt-4">
            <li class="list-group-item">
                <button class="btn btn-outline-info">
                    <a href="changePassword.php">🔑تغيير كلمة المرور</a>
                </button>
            </li>
        </div>
    </div>

<?php } ?>
