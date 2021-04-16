
<?php require '../dbconnection.php'; ?>
<?php require 'header.php'; ?>


<div class="b-example-divider">
   

<br>
<br>
<br>



<! --------------------------->
<! List all not Approve jobs>
<! --------------------------->

<h4 class=" text-info  text-center  "> List all not Approve jobs</h4>
<br>


<div class="container  shadow p-3  bg-body rounded">



<table class="table table-hover  ">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Title</th>
      <th scope="col">Company name</th>
      <th scope="col">Post date</th>
      <th scope="col">category</th>
      <th scope="col">Aprove</th>
      <th scope="col">Delete</th>

    </tr>
  </thead>
  <tbody>


<?php 
  $ApproveJ = $db->getaNotApproveJobs();
foreach($ApproveJ as $row){

    ?>


    <tr>
      <th scope="row"><?php echo $row['id']; ?></th>
      <td> <?php echo $row['title']; ?></td>
      <td> <?php echo $row['company_name']; ?></td>
      <td> <?php echo $row['post_date']; ?></td>
      <td> <?php echo $row['cname']; ?></td>
      <td>
      <form  method = "POST" action="../AproveJob.php">
               <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
              <button type="submit" class="btn btn-success"> <i class="fas fa-globe-europe"></i> Approve</button>
          </form>
          </td>


          <td>
          <form  method = "POST" action="../DeleteJob.php">
                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                <button type="submit" class="btn btn-danger"><i class="fas fa-trash-alt"></i> Delete</button>
          </form>
          </td>


    </tr>



 <?php } ?>
  </tbody>
</table>
</div>













<! --------------------------->
<! All Jobs>
<! --------------------------->



<div class="contanier text-center mt-5">
    <h1> All Jobs </h1>
</div>




<div class="container  shadow p-3  bg-body rounded my-5" >
<div class="row row-cols-1 row-cols-md-3 g-4 ">



<?php 
$ApproveJ = $db->getAllJobs();
foreach($ApproveJ as $row){


    ?>

        <div class="col">
            <div class="card h-100">
                <a href="details/b1.html">
                    <img src="<?php echo $row['image']; ?>" class="card-img-top " style=" width: 100%; height: 16vw;object-fit: cover;" alt="...">
                </a>
                <div class="card-body">
                    <h5 class="card-title"> <?php echo $row['title']; ?></h5>
                    <p class="card-text"><?php echo $row['description']; ?></p>
                </div>



                <div class="card-footer">

<div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
<p class="card-text" style="color : green">
                      <?php if ($row['approve'] == 1 ){echo "approve"; } else { echo "not approve"; } ?> |
                    </p>
                    <p class="card-text" style="color : blue">
                    <?php if ($row['sponsored'] == 1 ){echo "sponsored"; } else { echo "not sponsored"; } ?> |
                </p>
                <p class="card-text" style="color : gray">
                    <?php echo $row['post_date'] ;?> |
                </p>
                <p class="card-text" style="color : gray"> <i class="fas fa-eye"></i>
                    <?php echo $row['visited'] ;?>
                </p>

              

</div>
</div>







          <div class="card-footer">

                <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
            <form  method = "POST" action="singleJob.php">
                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                <button type="submit" class="btn btn-info"><i class="fas fa-eye"></i>  View</button>
            </form>
          
          <form  method = "POST" action="../DeleteJob.php">
                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                <button type="submit" class="btn btn-danger"><i class="fas fa-trash-alt"></i> Delete</button>
          </form>

          <form  method = "POST" action="../AproveJob.php">
               <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
              <button type="submit" class="btn btn-success"> <i class="fas fa-globe-europe"></i> Approve</button>
          </form>

          <form  method = "POST" action="../sponsoreJob.php">
               <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
              <button type="submit" class="btn btn-warning"><i class="fas fa-arrow-alt-circle-up"></i> Sponsored</button>
          </form>

        </div>
<br>

        <div class="card-footer">

<div class="d-grid gap-2 d-sm-flex justify-content-sm-center">

<form  method = "POST" action="../AproveJob.php">
<input type="hidden" name="id" value="<?php echo $row['id']; ?>">
<button type="submit" class="btn btn-secondary"><i class="fas fa-lock"></i> Unapprove</button>
</form>

<form  method = "POST" action="../sponsoreJob.php">
<input type="hidden" name="id" value="<?php echo $row['id']; ?>">

<button type="submit" class="btn btn-dark"><i class="fas fa-arrow-alt-circle-down"></i>  Unsponsored</button>
</form>

</div>
</div>
                





                </div>
            </div>
        </div>






<?php
}
 
?>

</div>
</div>


<br>


<div class="dropdown-divider"></div>

<br>
<br>


<! --------------------------->
<! List all user>
<! --------------------------->

<h4 class=" text-info  text-center   "> List all users</h4>
<br>


<div class="container  shadow p-3  bg-body rounded">



<table class="table table-hover  table-dark ">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">User name</th>
      <th scope="col">Email</th>
      <th scope="col">Address</th>
      <th scope="col">Telephone</th>
      <th scope="col">Role</th>
      <th scope="col">Delete</th>
      

    </tr>
  </thead>
  <tbody>


<?php 
$ApproveJ = $db->getAllUsers();
foreach($ApproveJ as $row){

    ?>


    <tr>
      <th scope="row"><?php echo $row['id']; ?></th>
      <td> <?php echo $row['user_name']; ?></td>
      <td> <?php echo $row['email']; ?></td>
      <td> <?php echo $row['address']; ?></td>
      <td> <?php echo $row['telephone']; ?></td>
      <td> <?php
      
      
      if($row['role'] == 1){
          
        echo "Admin";
      }  
      else if ($row['role'] == 2){
        echo "Business owner";

      }
      else{
        echo "User";


      }
      
      
      ?></td>


      <td>
      
      <form  method = "POST" action="../DeleteUser.php">
                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                <button type="submit" class="btn btn-danger"><i class="fas fa-trash-alt"></i> Delete</button>
          </form>
      </td>
      
        


    </tr>



 <?php } ?>
  </tbody>
</table>
</div>
<br>
<br>
<br>









<! --------------------------->
<! add catigory>
<! --------------------------->



<div class="container  shadow p-3  bg-body rounded mb-4 text-center">
<h4 class=" text-info  text-center   "> Add Catigory</h4>
<br>

<form action="../AddCategory.php" method="POST">




<div class="form-row-md-6 ">
                                    
<div class="form-group  ">
    <input required type="text" name="name" class="form-control " aria-describedby="emailHelp" placeholder="Catigory name">
</div>

<button  type="submit" class="btn btn-success  text-white  ">Add</button>

  </div>


</form>

</div>




<?php require 'footer.php'; ?>