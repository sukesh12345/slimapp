<?php

namespace config;

// use PDO;

// class dbconnection {

//     public function connect() {
//         $servername = "localhost";
//         $username = "root";
//         $password = "";
//         $dbname = "user_data";
//         $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
//         return $conn;
//     }
//  }



class dbconnection {
public function connect() {
$fm = new \FileMaker("FileMaker DB", "http://172.16.9.184", "admin", "12345678");
if (\FileMaker::isError($fm)) {
echo "<p>Error: " . $fm->getMessage() . "</p>";
exit;
}
return $fm;
}
}
