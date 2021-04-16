<?php


class MyDB{
    private static $connection;

    public function connect(){
        if (!isset(self::$connection)){
            self::$connection = new mysqli("localhost" , "root" , "" ,"xjob");
        }
        if (self::$connection == false){
            echo "no connection".self::$connection->connect_error; 
        }
        return self::$connection;
    }



    public function getApproveJobs(){

                  $query = "SELECT jobs.* , categories.name AS cname 
                  FROM jobs
                  INNER JOIN categories 
                  ON jobs.categorie_id = categories.id
                  WHERE jobs.approve = 1 
                 ORDER BY post_date DESC";

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



public function getUserPostJobs(){
   
$query = "SELECT * FROM jobs
WHERE user_id = 2
ORDER BY post_date DESC";
   
   $conn = $this->connect();
   $result = $conn->query($query);
   
   $rows = array();
   while($row = $result-> fetch_assoc()){
      $rows[]=$row;
   }
   
   return $rows; 
   
   }



   public function getaNotApproveJobs(){
    $query = "SELECT jobs.* , categories.name AS cname 
    FROM jobs
    INNER JOIN categories 
    ON jobs.categorie_id = categories.id
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



        
    

    
    






       


    
    
    
    

















}

?>

