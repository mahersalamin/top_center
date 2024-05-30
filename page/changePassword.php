<?php require 'header.php'; ?>



<div class="container col-md-6 shadow p-3  bg-body rounded mb-2 text-center ">
<h4 class=" text-success font-weight-bold "> نموذج تغيير كلمة المرور</h4>
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

<form action="../changPassword.php" method="POST" enctype="multipart/form-data">
<div class="form-row-md-2 row  ">     



<input type="hidden" name="id" value="<?php echo $_COOKIE['id']; ?>">




    <div class="form-group mb-2">
       <input required type="text"  name="old" class="form-control font-weight-bold" aria-describedby="emailHelp" placeholder="🗒كلمة المرور القديمة">
    </div>

    
    <div class="form-group mb-2">
       <input required type="text"  name="new" class="form-control font-weight-bold" aria-describedby="emailHelp" placeholder="📝كلمة المرور الجديدة">
    </div>
    
    


    <button  type="submit" class="btn btn-success  text-white   font-weight-bold">حفظ</button>
  </div>
 
</form>
</div>
