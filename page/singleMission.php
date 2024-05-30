
<?php require '../dbconnection.php'; ?>
<?php require 'header.php'; ?>








<?php 

if (isset($_POST['id'])){
  $id = $_POST['id'] ;

}else if(isset($_GET['mission'] )){
  $id=$_GET['mission'];
}





$d = date('d/m/Y'); 
$ld = date('d/m/Y', strtotime('+1 day'));
$single = $db->getSinglemission($id);
foreach($single as $row){
    
    ?>




<div class="px-4 py-5 my-5 text-center">
<div class =  "col-md-5 shadow p-1  bg-body rounded d-block mx-auto mb-4"> 
<img class="img-fluid" src="../upload/file-6462212e582329.91627289.png " alt="" >

</div>
 <h1 class="display-4 fw-bold" style="color : blue ; display: inline;" > <?php  echo $row['title']; ?>  </h1>


  <div class="col-lg-5 mx-auto pt-4">
  
    <p class="lead mb-4"> <?php echo $row['description'];  ?>   </p>
    <h2> More Details </h2>
    <div class=" gap-5 ">
    <ul class="list-group">
       
       

<li class ="list-group-item"><h6 style="color : black ; display: inline; font-weight:bold"> Dedeline : </h6>

  <?php 

  if ($row['date'] > $d){
  
?>
 <h6 style="display: inline; color : green"><?php echo $row['date'];?>   </h6>

<?php }elseif($row['date'] < $d){
  ?>

<h6 style="display: inline; color : red"><?php echo $row['date'];?>   </h6>


<?php
}elseif($row['date'] == $ld){
?>
<h6 style="display: inline; color : yellow"><?php echo $row['date'];?>  </h6>

<?php }?>

</li>


<li class ="list-group-item">
  <?php if ($row['file'] == "No File"){ ?>
    <h6   style="color : black ; display: inline; font-weight:bold">File : <?php echo $row['file']; ?></h6>
  

  <?php }else {?>
    <h6   style="color : black ; display: inline; font-weight:bold">File :</h6>
  <a href="../upload/<?php echo $row['file']; ?>"><?php echo $row['file']; ?> </a>
  <?php } ?>
   



</li>


<?php if (isset($_POST['id'])){

?>
<li class ="list-group-item"><h6>Status</h6>  

<form action="../changStatus.php" method="POST" enctype="multipart/form-data">
<input type="hidden" name="id" value="<?php echo $_POST['id']; ?>">

<button type="submit"  value="1"  name = "status" class="btn btn-outline-success">Accept ✓</button>
<button type="submit"  value="2"  name = "status" class="btn btn-outline-danger">Reject ✕</button>
<button type="submit"  value="3"  name = "status" class="btn btn-outline-warning">Not Now ⁐</button>
 
</form>


</li>
<?php }else{}?>


      </ul>
 
      <br>
     <a class="display-6" href ="bodyHomeUser.php"> < go back </a>
    </div>
  </div>
</div>


<?php } ?>




<?php require 'footer.php'; ?>