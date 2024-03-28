<?php    
    $host = "localhost";
    $dbname = "test_appointments";
    $username = "root";
    $password = "";
                    
    //connect to mysql
    $conn = new mysqli($host, $username, $password, $dbname);

    if($conn->connect_error){
        die("Connection failed: " . $conn->connect_error);
    }