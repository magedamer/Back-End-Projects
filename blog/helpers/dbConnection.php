<?php 

  session_start();


$server = "localhost";
$dbUser = "root";
$dbPassword = "";
$dbName = "group9blog";
    
   $con =  mysqli_connect($server,$dbUser,$dbPassword,$dbName);

   if(!$con){
       die("Error : ".mysqli_connect_error());
   }

?>