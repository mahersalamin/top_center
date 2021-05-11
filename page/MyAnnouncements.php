<?php require 'header.php'; ?>


<div class="b-example-divider">
<br>
<br>


<?php 



if ($myAnno = $db->getUserJob($_COOKIE['id'])){

  foreach($myAnno as $row){
}

    ?>



<div class="row m-5 ">
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
                    <?php
                     if ($row['sponsored'] == 1 )
                     {echo "sponsored"; }
                      else
                       { echo "not sponsored"; } ?> 
                </p>




        <h6 style="color : blue"> 
          <i class="fas fa-location"></i>  location : <?php echo $row['location']; ?> 
        | <i class="fas fa-calendar-week"></i>  Post date :  <?php echo $row['post_date']; ?>  
        | <i class="fas fa-eye"></i> view cont :  <?php echo $row['visited']; ?></h6>
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
else {
?>

   <div class="row m-5 justify-content-center">
     <div class="col-md-10 shadow p-3  bg-dark rounded ">
         <h1 class="text-center text-white"> you dont have posted jobs ! ,
          <a href="postJob.php">Post a new Job now  </a>😍 </h1>
     </div>
   </div>

<?php } ?>





<?php require 'footer.php'; ?>