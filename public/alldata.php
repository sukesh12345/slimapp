<?php
include 'connection.php';
session_start();
if($_SESSION['user_name']=='admin')
{
    $query = "select * from registration_data";
    //$query = mysqli_real_escape_string($con,$query);
    $query_run = mysqli_query($con, $query);
    $arr = [];
    if ($query_run) {
        while ($row = mysqli_fetch_array($query_run)) {
            for ($i = 0; $i < 7; $i++) {
                array_push($arr, $row[$i]);
            }
        }
        // echo "<pre>";
        // print_r($arr);
        $myjson = json_encode($arr);
        echo $myjson;
    } else {
        echo "no record found";
    }
}
else
{
    header("location:index.html");
}
?>