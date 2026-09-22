<?php
$conn = mysqli_connect("localhost", "root", "", "furniture");

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>