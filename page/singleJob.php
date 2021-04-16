
<?php require '../dbconnection.php'; ?>
<?php require 'header.php'; ?>

<div class="b-example-divider"></div>





<?php 




if (isset($_POST['id'] )){

  $id = $_POST['id'];
  
$query = "SELECT * FROM jobs  WHERE id = $id";
$result =  mysqli_query($conn , $query);
$row = mysqli_fetch_array($result);
  $vis = $row[15]+1;

    $query = "UPDATE jobs SET visited = $vis  WHERE id = $id";
    $result = mysqli_query($conn, $query);
}



$single = $db->getSingleJob($id);
foreach($single as $row){
    
    ?>




<div class="px-4 py-5 my-5 text-center">
<div class =  "col-md-5 shadow p-1  bg-body rounded d-block mx-auto mb-4"> 
<img class="img-fluid" src="<?php echo $row['image'];  ?>" alt="" >
</div>
  <h1 class="display-5 fw-bold " style="display: inline"> <?php  echo $row['title']; ?>  - </h1><h1 class="display-4 fw-bold" style="color : blue ; display: inline;" > <?php  echo $row['location']; ?>  </h1>

  <div class="col-lg-8 mx-auto pt-4">
  
    <p class="lead mb-4"> <?php echo $row['description'];  ?>   </p>
    <h2> More Details </h2>
    <div class=" gap-5 ">
    <ul class="list-group">
       <li class ="list-group-item"><h6 style="color : blue ; display: inline;"> Commpany name : </h6> <h6 style="display: inline"><?php echo $row['company_name'];	 ?> </h6> </li>
       <li class ="list-group-item"><h6 style="color : blue ; display: inline;"> Salary : </h6> <h6 style="display: inline"><?php echo $row['salary'];	 ?> $</h6>
       <li class ="list-group-item"><h6 style="color : blue ; display: inline;">Contact Telephone : </h6> <h6 style="display: inline"> <?php echo $row['contact_tele']; 	 ?> </h6> </li>
       <li class ="list-group-item"><h6 style="color : blue ; display: inline;">Contact Email : </h6> <h6 style="display: inline"><?php echo $row['contact_email'];  	 ?> </h6> </li>
       <li class ="list-group-item"><h6 style="color : blue ; display: inline;">job type : </h6> <h6 style="display: inline"><?php 
       if( $row['type'] == 1){echo "Full Time";  	 }
       else{echo "Part Time";  	  }
       ?> </h6> </li>

<li class ="list-group-item"><h6 style="color : blue ; display: inline;">Post Date : </h6> <h6 style="display: inline"><?php echo $row['post_date'];  	 ?> </h6> </li>
<li class ="list-group-item"><h6 style="color : blue ; display: inline;">category : </h6> <h6 style="display: inline"><?php echo $row['cname'];  	 ?> </h6> </li>




      </ul>
 
<br>
<P>
     <i class="fas fa-eye"></i>
                    <?php echo $row['visited'] ;?>
</P>

     <a class="display-6" href ="bodyHomeUser.php">go back</a>
    </div>
  </div>
</div>


<?php } ?>




<?php require 'footer.php'; ?>