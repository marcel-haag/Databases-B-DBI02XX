<?php
    // Exercise 2

    $db_host = "localhost";
    $db_name = "fern_uni";
    $db_user = "admin";
    $db_pass = "geheim";

    try {
        $con = new PDO ('mysql:host='.$db_host.';dbname='.$db_name,$db_user,$db_pass);

        $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // echo "Connected successfully \n";
    } catch(PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
    // Datenbankverbindung schließen
    $con = null;
?>
