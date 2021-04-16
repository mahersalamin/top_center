<?php require '../dbconnection.php'; ?>
<?php require 'header.php'; ?>


<div class="b-example-divider">


<?php 




$myAnno = $db->getUserPostJobs();
foreach($myAnno as $row){
    ?>



<div class="row m-5 jobs ">
    <div class="col-md-2 shadow p-3  bg-body rounded">
    <img class="img-fluid " src="<?php echo $row['image']; ?>" alt="">
 
    </div>
    <div class="col-md-8 shadow p-3  bg-body rounded">
        <h4>
           <?php echo $row['title']; ?> </h4>
        <p style="color:gray">
           <?php echo $row['description']; ?>
        </p>



                 <p class="card-text" style="color : green">
                      <?php if ($row['approve'] == 1 ){echo "approve"; } else { echo "not approve"; } ?> 
                </p>


                <p class="card-text" style="color : green">
                    <?php if ($row['sponsored'] == 1 ){echo "sponsored"; } else { echo "not sponsored"; } ?> 
                </p>




        <h6 style="color : blue"> 
          <i class="fas fa-location"></i>  location : <?php echo $row['location']; ?> 
        | <i class="fas fa-calendar-week"></i>  Post date :  <?php echo $row['post_date']; ?>  
        | <i class="fas fa-eye"></i> view :  <?php echo $row['visited']; ?></h6>
    </div>



    <div class="col-md-2 text-center mt-4">


    <form  method = "POST" action="singleJob.php">
    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
   <button type="submit" class="btn btn-info"><i class="fas fa-eye"></i> view Job</button>
     </form>

</div>

  
</div>
<?php
}
 
?>



 









<?php require 'footer.php'; ?>