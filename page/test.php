
<?php 

if (isset($_GET['file'] ) ){


    $file_name =$_GET['file']; 
    $file ="../upload/$file_name" ;
}


header('Content-type:application/pdf');
header ('Content-description:inline;filename="'.$file.'"');
header ('Content-Transfer-Encoding:binary');
header ('Accept-Ranges:bytes');
@readfile($file)


?>