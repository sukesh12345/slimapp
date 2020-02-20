<?php

namespace config;

use PDO;

class dbconnection {

    public function connect() {
        // $servername = "localhost";
        // $username = "root";
        // $password = "";
        // $dbname = "slimapp";
        // $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        // return $conn;
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "user_data";
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        return $conn;
    }
  }
?>