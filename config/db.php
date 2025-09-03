<?php
$host = "localhost";
$user = "root";   // default in XAMPP
$pass = "";       // empty password by default
$db   = "lecture_scheduler";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>
