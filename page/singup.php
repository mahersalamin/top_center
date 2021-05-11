
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <!-- CSS only -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">

<!-- JavaScript Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>

</head>
<body class="bg-dark">

<div class="container text-center ">
        <div class="row mx-auto justify-content-center mt-5 ">
            <div class="col-md-5 shadow p-3  bg-body rounded m-5">
                <h1 class ="text-center m-4 text-success" > Register Now </h1>

                <div class="col-md-12">
                    <?php
                
                        if (isset($_GET['error'])){
                            ?>


                            <div class="alert alert-danger" role="alert">
                            Email "<?php echo $_GET['error']; ?>" is Registered, Please Enter Another Email or
                            <a href="singin.php?error=<?php echo $_GET['error']; ?>"> Login</a>
                            </div>

                          <?php } ?>
                </div>





                <form action="../register.php" method="POST">

                    <div class="form-group mb-2 ">
                        <input required type="text" name="user_name" class="form-control" aria-describedby="emailHelp" placeholder="Name">
                    </div>
          
                    <div class="form-group mb-2">
                       <input required type="email" name="email" class="form-control" aria-describedby="emailHelp" placeholder="Email">
                    </div>

                    <div class="form-group mb-2 ">
                       <input required type="password" name="password" class="form-control" aria-describedby="emailHelp" placeholder="Password">
                    </div>
           
                    <div class="form-group mb-2">
                        <input required type="text" name="address" class="form-control" aria-describedby="emailHelp" placeholder="Address">
                    </div>


                    <div class="form-group mb-2">
                        <input required type="number" name="telephone" class="form-control" aria-describedby="emailHelp" placeholder="Telephone">
                    </div>


                    <div class="form-group mb-2">
                        <input  type="text" name="photo" class="form-control" aria-describedby="emailHelp" placeholder="Photo url (optinal)">
                    </div>



                    
                    <div class="form-group ">

            <select require name= "role" class = "form-select" >        
            
            <option value="0"> User </option>
            <option value="2"> Business Owners </option>
     
                     </select>
                     </div>
                     <br>






               
                    <button  type="submit" class="btn btn-success  container-fluid fs-3 text-white ">sign up</button>
<br><br>
                    <a href="singin.php"> Back to login</a>

                </form>
                </div>

</div>
</div>







    
</body>
</html>


