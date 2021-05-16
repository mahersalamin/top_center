<?php


class MyDB{
   
    private static $connection;

   

    public function connect(){
        if (!isset(self::$connection)){
            self::$connection = new mysqli("localhost" , "root" , "" ,"xjobs");
        }
        if (self::$connection == false){
            echo "no connection".self::$connection->connect_error; 
        }
        return self::$connection;
    }



    public function getApproveJobs($j){

      if ($j==0){
         $query = "SELECT *  FROM jobs WHERE jobs.approve = 1 ORDER BY post_date DESC"; 
      }

      $jobs = self::getAllCatrgories();
      foreach($jobs as $row){
      if($j == $row['id']){
         $query = "SELECT *  FROM jobs WHERE jobs.approve = 1 AND categorie_id = $j  ORDER BY post_date DESC"; 
      }
   }


                 $conn = $this->connect();
                 $result = $conn->query($query);

                 $rows = array();


                 while($row = $result-> fetch_assoc()){

                    $rows[]=$row;

                 }

        return $rows; 
}


      public function getUserPhoto($id){

         $query = "SELECT * FROM users WHERE id = $id";

         
         $conn = $this->connect();
         $result = $conn->query($query);
         
         $rows = array();
         
         while($row = $result-> fetch_assoc()){
         
            $rows[]=$row;
         }
      
         return $rows; 
      
      }



      public function getSponserdJobs(){
             $query = "SELECT * FROM jobs
            WHERE sponsored = 1 AND  approve = 1 
            ORDER BY post_date ASC";
            
            
            $conn = $this->connect();
            $result = $conn->query($query);
            
            $rows = array();
            
            while($row = $result-> fetch_assoc()){
            
               $rows[]=$row;
            
            }
            
            return $rows; 
            
           
           }




   public function getaNotApproveJobs(){
      $query = "SELECT jobs.* , categories.name AS cname  , users.user_name AS Puser
      FROM jobs
      INNER JOIN categories ON jobs.categorie_id = categories.id
      INNER JOIN users ON jobs.user_id =  users.id
      WHERE jobs.approve = 0
     ORDER BY post_date DESC";
  
       $conn = $this->connect();
       $result = $conn->query($query); 
       $rows = array();
    
       while($row = $result-> fetch_assoc()){
          $rows[]=$row;   
       }
       
       return $rows;  
       }
    

       
   public function getAllJobs(){
    $query = "SELECT * FROM jobs
    ORDER BY post_date DESC";
  
       $conn = $this->connect();
       $result = $conn->query($query); 
       $rows = array();
    
       while($row = $result-> fetch_assoc()){
          $rows[]=$row;   
       }
       
       return $rows;  
       }
    
    

       
       
   public function getAllUsers(){
    $query = "SELECT * FROM users
    ORDER BY id ASC";
    
  
       $conn = $this->connect();
       $result = $conn->query($query); 
       $rows = array();
    
       while($row = $result-> fetch_assoc()){
          $rows[]=$row;   
       }
       
       return $rows;  
       }







       public function getAllCatrgories(){
        $query = "SELECT  * FROM categories";
      
           $conn = $this->connect();
           $result = $conn->query($query); 
           $rows = array();
        
           while($row = $result-> fetch_assoc()){
              $rows[]=$row;   
           }
           
           return $rows;  
           }
    

           

       public function getAllcounties(){
         $query = "SELECT location FROM jobs";
       
            $conn = $this->connect();
            $result = $conn->query($query); 
            $rows = array();
         
            while($row = $result-> fetch_assoc()){
               $rows[]=$row;   
            }
            
            return $rows;  
            }

            
       public function SearchJob($search){
         $query = "SELECT * FROM jobs
          WHERE title LIKE '%$search%'";
       
            $conn = $this->connect();
            $result = $conn->query($query); 
            $rows = array();
         
            while($row = $result-> fetch_assoc()){
               $rows[]=$row;   
            }
            
            return $rows;  
            }


            public function SearchUser($search){
               $query = "SELECT * FROM users
                WHERE user_name LIKE '%$search%' OR email LIKE '%$search%' ";
             
                  $conn = $this->connect();
                  $result = $conn->query($query); 
                  $rows = array();
               
                  while($row = $result-> fetch_assoc()){
                     $rows[]=$row;   
                  }
                  
                  return $rows;  
                  }



     
 







           
           public function ApproveJob($id){
      
            $query = "UPDATE jobs SET approve = 1  WHERE id = $id";
    
            $conn = $this->connect();
            $result = $conn->query($query); 
    
            return $result ; 
             }

             
           
           public function SponsoreJob($id){
      
       
            $query = "SELECT * FROM jobs  WHERE id = $id";
            $conn = $this->connect();
            $result = $conn->query($query); 
            $row = mysqli_fetch_array($result);
                $spo = $row[13];
            
            
                if ($spo == 1)
                {
                    $query = "UPDATE jobs SET sponsored =0  WHERE id = $id";
                }
   
                else {
                    $query = "UPDATE jobs SET sponsored =1  WHERE id = $id";
                }
                
                        $conn = $this->connect();
                        $result = $conn->query($query); 
                
                        return $result ; 
          }

       


       




          


          public function DeleteUser($id){

            $query = "DELETE FROM users WHERE id=$id";
 
             $conn = $this->connect();
             $result = $conn->query($query); 
             
             return $result;
 
            }



           public function DeleteJob($id){

           $query = "DELETE FROM jobs WHERE id=$id";

            $conn = $this->connect();
            $result = $conn->query($query); 
            
            return $result;

           }

         


           
           public function getUserJob($id){
          
            $query = "SELECT * FROM jobs WHERE user_id=$id";
            
                
          
               $conn = $this->connect();
               $result = $conn->query($query); 
               $rows = array();
            
               while($row = $result-> fetch_assoc()){
                  $rows[]=$row;   
               }
               
               return $rows;  
               }





           public function getSingleJob($id){
          
            $query = "SELECT jobs.* , categories.name AS cname 
            FROM jobs
            INNER JOIN categories 
            ON jobs.categorie_id = categories.id
            WHERE jobs.id=$id
            ";
            
                
          
               $conn = $this->connect();
               $result = $conn->query($query); 
               $rows = array();
            
               while($row = $result-> fetch_assoc()){
                  $rows[]=$row;   
               }
               
               return $rows;  
               }




           public function SortByCat($id){
               $i = $id;
          
            $query = "SELECT * FROM jobs 
            WHERE categorie_id = $i";
        
                
          
               $conn = $this->connect();
               $result = $conn->query($query); 
               $rows = array();
            
               while($row = $result-> fetch_assoc()){
                  $rows[]=$row;   
               }
               
               return $rows;  
               }



               
           public function MakeAdmin($id , $role){
            $i = $id;

            if ($role == 1){
               $query = "UPDATE users SET role = 0 WHERE id = $i";
            }
            else {
               $query = "UPDATE users SET role = 1 WHERE id = $i";

            }
  
       
     
       
            $conn = $this->connect();
            $result = $conn->query($query); 
      
      
            return $result;  
            }





        






               


            public function AddJob($user_id,$title,$description,$salary,$location,$categorie_id,$company_name,$contact_tele,$type,$contact_email,$image){

                     if ($image == ""){
                        $query = "INSERT INTO jobs (user_id,title,description,salary,location,categorie_id,company_name,contact_tele,contact_email,type) VALUES ($user_id,'$title','$description',$salary,'$location',$categorie_id,'$company_name','$contact_tele','$contact_email',$type)";
                      }
                      else{
                        $query = "INSERT INTO jobs (user_id,title,description,salary,location,categorie_id,company_name,contact_tele,contact_email,image,type) VALUES ($user_id,'$title','$description',$salary,'$location',$categorie_id,'$company_name','$contact_tele','$contact_email','$image',$type)";
                        }

        
              
                        $conn = $this->connect();
                        $result = $conn->query($query); 
         
                        return $result; 
               }





               public function CheckExist ($email){

                  $query = "SELECT * FROM users WHERE email='$email'";
                    $conn = $this->connect();
                    $result = $conn->query($query);       
                    
                    if (mysqli_num_rows($result) > 0){
                     return true;
                    }
                    else {
                       return false ; 
                    }
    
                    
            



               }

         
            public function AddUser($user_name,$email,$password,$address,$telephone,$role,$photo){


               if ($photo == ""){
                  $query = "INSERT INTO users (user_name,email,password,address,telephone,role) VALUES ('$user_name','$email','$password','$address','$telephone',$role)";

                }
                else{
                  $query = "INSERT INTO users (user_name,email,password,address,telephone,role,photo) VALUES ('$user_name','$email','$password','$address','$telephone',$role,'$photo')";

                  }


                    $conn = $this->connect();
                    $result = $conn->query($query); 
     
                    return $result ; 
            }


            
            public function AddCategory($name){
               $query = "INSERT INTO categories (name) VALUES ('$name')";
               $conn = $this->connect();
               $result = $conn->query($query); 

               return $result ; 
       }


               





       


    
    
    
    

















}

?>

