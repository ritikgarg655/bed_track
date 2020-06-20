<?php
	session_start();
	require_once 'pdo.php';
	echo $_SESSION["id"];
	if(isset($_SESSION['id'])){
		$sql  = 'SELECT * FROM bed_trcker.Hospital_option_a where hospital_ref = :hid';
		$stmt = $pdo->prepare($sql);
		$stmt->execute(array('hid'=>$_SESSION['id']));
		$row = $stmt->fetch();
		echo "Hospital details.<br>";
		echo 'Hospital ID: '.$row['hospital_ref']."<br>";
		echo "IP Address: ".$row['ip_add']."<br>";
		echo "Total bed field name: ".$row['fiel_name_tot_bed']."<br>";
		echo "Unoccupied bed field name: ".$row['fiel_name_unoc_bed']."<br>";
		$sql = 'select option_selected from bed_trcker.Hospital_details where hospital_id = :hid';
		$stmt = $pdo->prepare($sql);
		$stmt->execute(array('hid'=>$_SESSION['id']));
		$row = $stmt->fetch();
		// echo $row['option_selected'];
		if(isset($_POST['edit'])){
			header('Location: hospital_edit_ip.php');
			return;
		}
	}
	else{
		header('Location: hospital_login.php');
		die('Access denied.');
	}
	if(isset($_POST['logout'])){
		unset($_SESSION['id']);
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<p> <form method="post"><input type="submit" name="edit" value="edit"></form></p>
	<p> <form method="post"><input type="submit" name="logout" value="logout"></form> </p>
</body>
</html>