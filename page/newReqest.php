<?php require 'header.php'; ?>

<?php 
if (!isset($_COOKIE['id'])){
    header("location:signin.php");
    }
?>





<br>
<br>



<!-- add Reqest -->




<div class="container col-md-6 shadow p-3  bg-body rounded mb-2 text-center ">
<h4 class=" text-info  text-center  font-weight-bold  "> Add Request</h4>
<br>


<form action="../requestadd.php" method="POST" enctype="multipart/form-data">

  <div class="form-row-md-4 row ">     


    

    <div class="form-group col-md-4 mb-2">
       <input required type="text"  name="title" class="form-control " aria-describedby="emailHelp" placeholder="🎫 Request Title">
    </div>

    
    <div class="form-group">
      <textarea class="form-control" required name="description" placeholder="Request Description"></textarea>
    </div>


    <div class="form-group mb-2">      
       <input require class="col-md form-control" name="file"  type="file"   title = "Add file" >               
    </div>
    


    <input type="hidden" name="from" value="<?php echo $_COOKIE['id']; ?>">

    <button  type="submit" class="btn btn-info  text-white   font-weight-bold">Add Request</button>

  </div>
</form>

</div>



