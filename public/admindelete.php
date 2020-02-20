<?php
include 'connection.php';
session_start();
$user_name = mysqli_escape_string($con,$_POST['user_name']);
$sql = "delete FROM `registration_data` WHERE Telephone=' $user_name'";
$result = mysqli_query($con,$sql);
header("location:admindashboard.php");
?>