<?php
	require 'rpiCAS.php';
	require 'config.php';

	try{
    $conn = new PDO('mysql:host=localhost;dbname=slatecrate', $config['DB_USERNAME'], $config['DB_PASSWORD']);

    $string = "'" . $_POST["className"] . "', '" . $_POST["inputCategory"] . "', '" . $_POST["user"] . "', " . "CURDATE()";

    $conn->query("INSERT INTO `categories` (`title`, `prefix`, `rcs_id`, `creation_date`)
    	VALUES (" . $string . ");");

	}catch(PDOException $e){
        echo $e;
    }
?>