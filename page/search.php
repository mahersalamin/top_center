

<?php 
require '../dbconnection.php';
$output = '';

if (isset($_POST['query'])){

    $search = $_POST['query'];
    $stmt = $conn->prepare("SELECT * FROM users WHERE user_name LIKE CONCAT('%',?,'%') OR  email LIKE CONCAT('%',?,'%') ") ;
    $stmt->bind_param("ss", $search,$search);
}

else {
    $stmt= $conn->prepare("SELECT * FROM users");
}

$stmt->execute();
$result = $stmt->get_result();


if($result->num_rows>0){

    $output = " 
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
  <tbody>"
    
    while($row=$result->mysqli_fetch_assoc()){

        $output .= "
        
    <tr >
    <th  scope="row">".$row['id']         ."   </th>
    <td>             ". $row['user_name'] ."   </td>
    <td>             ". $row['email']     ."   </td>
    <td>             ". $row['address']   ."   </td>
    <td>             ". $row['telephone'] ."   </td>
    <td>
    
    
    if(".$row['role'] ." == 1){echo "Admin";}  
      else if (".$row['role']." == 2){ echo "Business owner";}
      else{echo "User"; }

      </td>
  
      <td>
      
      <form  method = "POST" action="../DeleteUser.php">
                <input type="hidden" name="id" value="".$row['id'] ."">
                <button type="submit" class="btn btn-danger"><i class="fas fa-trash-alt"></i> Delete</button>
          </form>
      </td>
  
    </tr>"; 
 } 
$output .="</tbody>"; 
echo $output; 

    }
    else {
        echo "<h3> no record found </h3>";
    }

?> 