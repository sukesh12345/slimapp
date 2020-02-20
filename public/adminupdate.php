<?php
  include 'connection.php';
  $Name = mysqli_real_escape_string($con,$_POST['Name']);
  $Address = mysqli_real_escape_string($con,$_POST['Address']);
  $Email = mysqli_real_escape_string($con,$_POST['Email']);
  $Password = mysqli_real_escape_string($con,$_POST['Password']);
  $Telephone = mysqli_real_escape_string($con,$_POST['Telephone']);
  $Gender = mysqli_real_escape_string($con,$_POST['Gender']);
  $Course = mysqli_real_escape_string($con,$_POST['Course']);
  $sql = "UPDATE registration_data
    SET Name = '$Name', Address= '$Address',Email='$Email',Password = '$Password' ,Gender ='$Gender',Course = '$Course'
    WHERE Telephone = '$Telephone'";
    if(!mysqli_query($con,$sql))
    {
      echo'email already taken';
    }
    else
    {
      session_start();
      $_SESSION['user_name'] = 'admin';
      echo'updated';
    }
 ?>