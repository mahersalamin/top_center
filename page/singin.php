
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
<body>

<div class="container text-center ">
        <div class="row mx-auto justify-content-center mt-5 ">
            <div class="col-md-6 shadow p-3  bg-body rounded m-5">


            <img  style="width: 20%; height: 27%; "src="https://i.ibb.co/sCX1rnJ/xjob-BLACK.png" alt="">
            
                <h1 class ="text-center m-4 text-success" > Sing in </h1>







                <form action="../checkSingin.php" method="POST">

                    <div class="form-group mb-2 ">
                        <input required type="text" name="email" class="form-control" aria-describedby="emailHelp" placeholder="Email">
                    </div>
          
                    <div class="form-group mb-2">
                       <input required type="password" name="password" class="form-control" aria-describedby="emailHelp" placeholder="Password">
                    </div>

                    <button  type="submit" class="btn btn-success mb-3  container-fluid fs-3 text-white ">Sign in</button>


                    <h6 > dont have account !</h6>


                </form>
                
                <form method="get" action="singup.php">
                       <button class="btn btn-primary mt-3 container-fluid fs-3 text-white " type="submit">Sign up</button>
                      </form>
                </div>

</div>
</div>







    
</body>
</html>


