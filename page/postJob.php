

<?php require 'header.php'; ?>

<div class="b-example-divider"></div>


<?php 

if (!isset($_COOKIE['id'])){
    header("location:singin.php");
   
}

?>


<div class="container ">
        <div class="row mx-auto justify-content-center ">
            <div class="col-md-6 shadow p-3  bg-body rounded m-5">
                <h1 class ="text-center m-4 text-success" > Add Job </h1>


                <form action="../AddJob.php" method="POST">

                    <div class="form-group ">
                        <input required type="hidden" name="user_id" class="form-control" value=<?php echo $_COOKIE['id']; ?> aria-describedby="emailHelp" placeholder="User id">
                    </div>
          
                <div class="form-row mt-4">
                                    <div class="col-12 col-sm-6">
                                    <input required type="text" name="title" class="form-control" aria-describedby="emailHelp" placeholder="Title">
                                    </div>
                                    <div class="col-12 col-sm-6 mt-4 mt-sm-0">
                                    <input required type="text" name="company_name" class="form-control" aria-describedby="emailHelp" placeholder="Company Name">
                                    </div>
                 </div>

              <br>

                    <div class="form-group">
                    <textarea class="form-control" name="description" placeholder="Description"></textarea>
                    </div>


                   
                    <div class="form-group ">
                        <div class="row   rounded  mx-2 mb-2 text-center ">
                            <div class="col-md-6 ">
                            
                                <label>Full time</label>
                                <input  type="radio" name="type" value="1">
                            </div>
                            <div class="col-md-3 ">
                                <label>Part time</label>
                                <input   type="radio" name="type" value="0">
                            </div>
                        </div>

                    
                    <div class="form-group ">
                        <input required type="text" name="salary" class="form-control" aria-describedby="emailHelp" placeholder="Salary">
                    </div>


                    <div class="form-group">
                        <input required type="text" name="location" class="form-control" aria-describedby="emailHelp" placeholder="Location">
                    </div>




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

                    
                <div class="form-row mt-4">
                    <div class="col-12 col-sm-6">
                   <input required type="text" name="contact_tele" class="form-control" aria-describedby="emailHelp" placeholder="Contact telephone">
                   </div>
                   <div class="col-12 col-sm-6 mt-4 mt-sm-0">
                   <input required type="text" name="contact_email" class="form-control" aria-describedby="emailHelp" placeholder="Contact email">
                   </div>
                </div>


              <br>


                <div class="form-group">
                  image:  <input type="text" name="image" class="form-control" aria-describedby="emailHelp" placeholder="Image url (optinal)">
                </div>

                    

                    </div>
                    <button  type="submit" class="btn btn-success mb-3 container-fluid fs-3 text-white ">Submit</button>

                </form>
                </div>

</div>
</div>






<?php require 'footer.php'; ?>
