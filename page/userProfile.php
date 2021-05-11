<?php require 'header.php'; ?>


<?php 
$photo = $db->getUserPhoto($_COOKIE['id']);
foreach($photo as $row){          
    ?>

    




<div class="  text-center ">

    <img  src="<?php echo $row['photo']; ?>"  class="shadow   bg-body rounded" style="width: 20%; "    alt="">

    </div>


<?php } ?>

<form action="../" method="POST">

<div class="container  shadow p-3  bg-body rounded">
<h2> Choose your interests </h2>

<?php 
$jobs = $db->getAllCatrgories();
foreach($jobs as $row){
               
    ?>


<div class="custom-control custom-checkbox">
  <input type="checkbox" class="custom-control-input" id="<?php echo $row['id']; ?>" value ="<?php echo $row['id']; ?>">
  <label class="custom-control-label" for="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></label>
</div>

                    
<?php }?>

<br>
<button  class="btn btn-success "> done </button>
</form>

<?php 
$jobs = $db->getAllCatrgories();
foreach($jobs as $row){
               
    ?>


                 
<?php }?>





</div>
