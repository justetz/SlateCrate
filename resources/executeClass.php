<?php
	require 'rpiCAS.php';
	require '../../config.php';

    $conn = new PDO('mysql:host=localhost;dbname=slatecrate', $config['DB_USERNAME'], $config['DB_PASSWORD']);

    $string = $_POST["className"] . ", " . $_POST["inputCategory"] . ", " . phpCAS::getUser() + ", ";

    $conn->query("INSERT INTO `categories` (`title`, `prefix`, `user_id`, `creation_date`)
    	VALUES (" . $string . "CURDATE());");

?>