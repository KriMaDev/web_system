<?php
//========================================================================================
//  DATABASE CONNECTION 
//========================================================================================
    $host 			= "localhost";    // Database host
    $username		= "root";         // Database username
    $password 		= "";             // Database password
    $database 		= "krima_rep";    // Database name
    
    try {
        $pdo       = new PDO("mysql:host=$host;dbname=$database;charset=utf8", $username, $password);
        // set PDO error mode to exception
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Check the character set
        $result     = $pdo->query( "SHOW VARIABLES LIKE 'character_set_database'" );
        $charset    = $result->fetch(PDO::FETCH_ASSOC)['Value'];
    }
    catch(PDOException $e) {
        echo "ERROR_00 <br>Connection to server failed!<br>Error Message:    " . $e->getMessage();
    }
?>