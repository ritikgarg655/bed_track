<?php
	session_start();
	require_once('pdo.php');
	// echo $_SESSION['id'];
	if(isset($_SESSION['id'])){
		echo "<p> Hospital ID: ".$_SESSION['id']."</p>";
	}
	else{
		header('Location: hospital_login.php');
		die('Access denied.');
	}
	if(isset($_POST['submit'])){
		$sql = "SELECT * FROM bed_trcker.Hospital_option_a WHERE hospital_ref = :hid";
		$stmt = $pdo->prepare($sql);
		$stmt->execute(array('hid'=>$_SESSION['id']));
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		if($row==false){
			$count = 0;
		}
		else
			$count = 1;
		// vardump($count);
		if($count>0){
			$sql = "UPDATE bed_trcker.Hospital_option_a SET `ip_add`=:ip,`dbname`=:bd,`tablename`=:tb,`fiel_name_tot_bed`=:tot_bed,`fiel_name_unoc_bed`=:unoc_bed WHERE `hospital_ref`=:hosp_ref";
			$stmt = $pdo->prepare($sql);
			$stmt->execute(array(
				'ip'=>$_POST['ip'],
				'bd'=>$_POST['dbname'] ,
				'tb'=> $_POST['tbname'] ,
				'tot_bed'=> $_POST['f_tot_bed'] ,
				'unoc_bed'=> $_POST['f_unoc_bed'] ,
				'hosp_ref'=> $_SESSION['id']
			));
			header('Location: hospital_show_ip.php');
			return;
		}
		$sql = "INSERT INTO bed_trcker.Hospital_option_a (`ip_add`,`dbname`,`tablename`,`fiel_name_tot_bed`,`fiel_name_unoc_bed`,`hospital_ref` ) VALUES (:ip,:bd,:tb,:tot_bed,:unoc_bed,:hosp_ref);";
		$stmt = $pdo->prepare($sql);
		$stmt->execute(array(
			'ip'=>$_POST['ip'],
			'bd'=>$_POST['dbname'] ,
			'tb'=> $_POST['tbname'] ,
			'tot_bed'=> $_POST['f_tot_bed'] ,
			'unoc_bed'=> $_POST['f_unoc_bed'] ,
			'hosp_ref'=> $_SESSION['id']
		));
		header('Location: hospital_show_ip.php');
		return;
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<form method="post">
		<label>IP Add:</label>
		<input type="text" name="ip" required><br>
		<label>Database name:</label>
		<input type="text" name="dbname" required><br>
		<label>Table name:</label>
		<input type="text" name="tbname" required><br>
		<label>Field name of total bed data:</label>
		<input type="text" name="f_tot_bed" required><br>
		<label>Field name of unoccupied name:</label>
		<input type="text" name="f_unoc_bed" required><br>
		<input type="submit" name="submit" value="submit">
	</form>
</body>
</html>