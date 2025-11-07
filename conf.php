<?php 
$servername = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbname = "waktuctfDB";

// Create connection
$conn = new mysqli($servername, $dbUsername, $dbPassword, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

session_start();
?>