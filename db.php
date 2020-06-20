<?php 
	$server = '127.0.0.1';
	$pass = '';
	$username = 'root';
	// $port = ;
	$database = 'bed_trcker';
	try{
		$conn = new PDO('mysql:host = $server;dbname = $database;',$username,$pass);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    	echo "Connected successfully";
	}	
	catch(PDOException $e){
		echo "Connection failed: " . $e->getMessage();
	}
?>