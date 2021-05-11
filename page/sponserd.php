

<div class="b-example-divider"></div>

<br>
<h2 class= "text-center"> Sponserd jobs</h2>

<div class="card-group" >


<?php 
$i = 0 ; 


$spom = $db->getSponserdJobs();


foreach($spom as $row){
 
  if ($i == 6){
    break ;

  }
  else{
    ?>


  <div class="card m-3 shadow   bg-body rounded">

   <img  src="<?php echo $row['image']; ?>" style=" width: 100%; height: 10vw;object-fit: cover;" class="card-img-top" alt="...">
    <div class="card-body">
      <h5 class="card-title"> job title : <?php echo $row['title']; ?></h5>
      <p class="card-text"> company name : <?php echo $row['company_name']; ?></p>
      <form  method = "post" action="singleJob.php">
<input type="hidden" name="id" value="<?php echo $row['id']; ?>">
<button type="submit" class="btn btn-info">view more detalis</button>
 </form>
    </div>
    <div class="card-footer">
      <small class="text-muted"> location : <?php echo $row['location']; ?></small>
      
    

    </div>


  </div>




          
    <?php  $i++; }} ?>
 

    </div>
    <br>
    <br>


















    <div class="b-example-divider"></div>