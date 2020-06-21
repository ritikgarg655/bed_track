<?php 
	$server = 'localhost';
	$pass = '';
	$username = 'root';
	// $port = ;
	$database = 'bed_trcker';
	$pdo = new PDO("mysql:host = $server;dbname = $database;",$username,$pass);
	// $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    	// echo "Connected successfully";
?>