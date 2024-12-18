<?php
$servername = "localhost";
$username = "root";
$password = ""; //Store password securely, do not hardcode in the file
$dbname = "pcstore1";

// Create connection
$con = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}
?>