<?php 
// Configuration template - Copy this to config.php and update with your database credentials

$Server = "localhost";           // Database host (e.g., localhost or remote host)
$Username = "root";              // Database username
$Password = "";                  // Database password
$Database = "PharmacyX_DB";      // Database name

// For production, consider using environment variables:
// $Server = $_ENV['DB_HOST'] ?? 'localhost';
// $Username = $_ENV['DB_USER'] ?? 'root';
// $Password = $_ENV['DB_PASS'] ?? '';
// $Database = $_ENV['DB_NAME'] ?? 'PharmacyX_DB';

//connection with Database
$Connection = mysqli_connect($Server, $Username, $Password, $Database);

//Check Connection
if(!$Connection){
    die("Connection Failed: ".mysqli_connect_error());
}

?>
