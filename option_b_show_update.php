<?php
	require_once 'pdo.php';
	session_start();
	if(isset($_SESSION['id'])){
		echo "<p> Hospital ID: ".$_SESSION['id']."</p>";
		$sql = "SELECT * FROM bed_trcker.option_b WHERE hosp_id = :hid";
		$stmt = $pdo->prepare($sql);
		$stmt->execute(array('hid'=>$_SESSION['id']));
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		if($row==true){
			// $count = 0;
			echo "Total bed:".$row['tot_bed']."<br>";
			echo "unoccupied bed:".$row['unocc_bed'];
		}
		// else{
		// 	$count = 1;
		// }
	}
	else{
		header('Location: hospital_login.php');
		die('Access denied.');
	}
	if(isset($_POST['update'])){
		$sql = "SELECT * FROM bed_trcker.option_b WHERE hosp_id = :hid";
		$stmt = $pdo->prepare($sql);
		$stmt->execute(array('hid'=>$_SESSION['id']));
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		if($row==false){
			$count = 0;
		}
		else{
			$count = 1;
		}
		echo $count;
		if($count > 0){
			$sql = "UPDATE bed_trcker.option_b SET `tot_bed`=:tot,`unocc_bed`=:un WHERE `hosp_id`=:hid;";
			$stmt = $pdo->prepare($sql);
			$stmt->execute(array(
				'tot' => $_POST['tot_bed'],
				'un' => $_POST['occ_bed'] ,
				'hid' => $_SESSION['id']
			));
			// echo 45;
			header('Location: option_b_show_update.php');
			return;
		}
		else{
			$sql = "INSERT INTO bed_trcker.option_b(`tot_bed`, `unocc_bed`, `hosp_id`) VALUES (:tot,:un,:hid);";
			$stmt = $pdo->prepare($sql);
			$stmt->execute(array(
				'tot' => $_POST['tot_bed'],
				'un' => $_POST['occ_bed'] ,
				'hid' => $_SESSION['id']
			));
			// echo 87;
			header('Location: option_b_show_update.php');
			return;
		}
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
	<form method="post">
		<label>Total bed:</label>
		<input type="number" name="tot_bed" required><br>
		<label>Unoccupied bed:</label>
		<input type="number" name="occ_bed" required><br>
		<input type="submit" name="update" value="update">
	</form>
	<p> <form method="post"><input type="submit" name="logout" value="logout"></form> </p>
</body>
</html>