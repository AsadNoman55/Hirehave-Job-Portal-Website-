<?php
$host = "localhost";
$db_user = "root";         // default XAMPP/WAMP user
$db_pass = "";             // default is blank in local servers
$db_name = "hirehaven";    // your database name

$conn = mysqli_connect($host, $db_user, $db_pass, $db_name);

// Check connection
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>
