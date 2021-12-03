<?php
     define('DB_DSN','mysql:host=localhost;dbname=valorant_database;charset=utf8');
     define('DB_USER','jeremy');
     define('DB_PASS','Password01');     
     
     try {
        // creating PDO connection to MySQL
        $db = new PDO(DB_DSN, DB_USER, DB_PASS);
        
     } catch (PDOException $e) {
        print "Error: " . $e->getMessage();
        die(); // Execution stops on errors.
     }
 ?>