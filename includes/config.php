<?php
//1--------------
ob_start(); //turns on output buffering


//***********form signup.php page-- session variable */
session_start();


//2--------------
date_default_timezone_set("Asia/Kolkata");

//3 use PDO for more secure---------

/* $servername = "localhost";
$username = "username";
$password = "password";

try {
  $conn = new PDO("mysql:host=$servername;dbname=myDB", $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  echo "Connected successfully";
} catch(PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
}*/

// root--> default user name and no password ""
try{

    $connection = new PDO("mysql:dbname=youtube;host=localhost","root","");

    //set the PDO error mode to execution
    $connection->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);
    // echo "<script>console.log('Database connected succesfully ✅')</script>";
    
    
}
catch (PDOException $e ){
    echo "Databse Connection fail ❌" . $e->getMessage();
}

?>