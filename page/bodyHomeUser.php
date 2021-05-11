<?php require 'header.php'; ?>
<?php require 'sponserd.php'; ?>





<div class="contanier text-center mt-5">
    <h1> all jobs available </h1>
</div>


<div class="container col-md-7 text-warning text-center  shadow p-3  bg-body rounded">
    <form method="POST" action="allJob.php">
        <h6> Sort by category </h6>
        <div class="form-group ">
        
        <select name= "category" class = "form-select" >        
         <option value="0"> choose catrgory </option>
           <?php 
            $jobs = $db->getAllCatrgories();
            foreach($jobs as $row){
         ?>
         
         <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?> </option>
                 
         <?php }?>
         </select>
         </div>
         
 
    
    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
   <button type="submit" class="btn btn-info"><i class="fas fa-eye"></i>Find</button>
    


    </form>
</div>



<?php require 'allJob.php'; ?>



<?php require 'footer.php'; ?>