<?php
$conn = new mysqli("localhost", "root", "", "capstone");

if ($conn->connect_error) {
  die("Database connection failed: " . $conn->connect_error);
}
?>
