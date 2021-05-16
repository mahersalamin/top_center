<?php require 'header.php'; ?>
<?php require 'sponserd.php'; ?>







<!------------------------------------------->
<!---------- sort and search Jobs ----------->
<!------------------------------------------->


<div class="contanier text-center mt-5">
    <h1> all jobs available </h1>
</div>


<div class="container col-md-7 text-warning text-center  shadow p-3  bg-body rounded">
    <form method="POST" action="">
        <h6> Sort Jobs by </h6>
        <div class="form-group ">

        <div class="row">
        
        <div class="col-md-4">

         
        <select name= "category" class = "form-select" >        
         <option value="0"> - all catrgory - </option>
           <?php 
            $jobs = $db->getAllCatrgories();
            foreach($jobs as $row){
         ?>
         
         <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?> </option>
                 
         <?php }?>
         </select>
        
        </div>


        <div class="col-md-4">

         
        <select name= "category" class = "form-select" >        
         <option value="0"> - all Counties - </option>
           <?php 
            $jobs = $db->getAllcounties();
            foreach($jobs as $row){
         ?>
         
         <option value="<?php echo $row['location']; ?>"><?php echo $row['location']; ?> </option>
                 
         <?php }?>
         </select>
        
        </div>


        <div class="col-md-4">

         
        <select name= "category" class = "form-select" >        
         <option value="0"> - all catrgory - </option>
           <?php 
            $jobs = $db->getAllCatrgories();
            foreach($jobs as $row){
         ?>
         
         <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?> </option>
                 
         <?php }?>
         </select>
        
        </div>
        
        
        
        </div>
       


         
         


         </div>
         
 
    
    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
   <button type="submit" class="btn btn-info"><i class="fas fa-eye"></i>Find</button>
    


    </form>
</div>












<!-------------------------------->
<!---------- List Jobs ----------->
<!-------------------------------->



<?php 


$j = 0 ; 

if (isset($_POST['category'])){

    $j=$_POST['category'] ; 


}

$jj = $db->getApproveJobs($j);

foreach($jj as $row){
?>


<div class="row m-5 jobs ">
    <div class="col-md-2 shadow p-3  bg-body rounded">
    <img class="img-fluid " src="<?php echo $row['image']; ?>" alt="">
 
    </div>
    <div class="col-md-8 shadow p-3  bg-body rounded">
        <h4><?php echo $row['title']; ?> </h4>
        <p style="color:gray"><?php echo $row['description']; ?> </p>
        <h6 style="color : blue"> location :<?php echo $row['location']; ?> </h6>
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

