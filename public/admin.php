<?php
 include 'connection.php';
  $query="select * from registration_data" ;
  //$query = mysqli_real_escape_string($con,$query);
  $query_run = mysqli_query($con,$query);
  $arr = [];
  $count_query = "SELECT COUNT(Telephone) from registration_data DISTINCT;";
  $count = mysqli_query($con,$count_query);
  if($query_run)
  {
    while($row=mysqli_fetch_array($query_run)){
    //  echo "
    //   $row[0]
    //   $row[Address]
    //   $row[Email]
    //   $row[Password]
    //   $row[Telephone]
    //   $row[Gender]
    //   $row[Course]";
      for( $i= 0;$i<7;$i++) {
        array_push($arr,$row[$i]);
      }
    }
    // echo "<pre>";
    // print_r($arr);
    $myjson = json_encode($arr);
    echo $myjson;
    
  }
  else
  {
    echo"no record found";
  }
?>