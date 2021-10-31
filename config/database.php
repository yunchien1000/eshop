<?php
// used to connect to the database
$host = "localhost";
$db_name = "online_store";
$username = "root";
$password = "";
  
try {
    $con = new PDO("mysql:host={$host};dbname={$db_name}", $username, $password);
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //echo "Connected successfully"; 
}
  
// show error
catch(PDOException $exception){
    echo "Connection error: ".$exception->getMessage();
}
?>
