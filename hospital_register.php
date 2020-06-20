<?php 
	session_start();
	// require_once 'db.php';
	require 'pdo.php';
	if(isset($_POST['submit'])){
		// session_start();
		$sql = "INSERT INTO bed_trcker.Hospital_details (`hospital_name`, 	`hospital_long`, 	`hospital_lat`, 	`username`, 	`pass`, 	`option_selected` ) VALUES (:name,:hos_long,:hos_lang,:usname,:pass,:opt);";
		// $sql2 = "SELECT * FROM "
		// echo $sql;
		$salt = "unkown";
		$sqlstmt = $pdo->prepare($sql);
		$hash_code  =hash('md5',$salt.$_POST['password']);
		$sqlstmt->execute(array(
			'name'=>$_POST['hospital_name'],
			'hos_long'=>$_POST['hospital_long'],
			'hos_lang'=>$_POST['hospita_lang'],
			'usname'=>$_POST['user_name'],
			'pass'=>$hash_code,
			'opt'=>$_POST['option']
		));
		// echo "ritik";
		// $us = $_POST['user_name'];
		$sql1 = 'SELECT `hospital_id` from bed_trcker.Hospital_details where username = :usn;';
		$stmt = $pdo->prepare($sql1);
		$stmt->execute(array('usn'=>$_POST['user_name']));
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$_SESSION['id'] =  $row['hospital_id'];
		// echo $_SESSION['id'];
		if($_POST['option']==1)
			header("Location: hospital_edit_ip.php");
		else{
			header("Location: option_b_show_update.php");
		}
		// die();
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
		<label>Hospital name:</label>
		<input type="text" name="hospital_name" required><br>
		<label>Hospital location:</label>
		<input type="text" name="hospital_long" required><br>
		<input type="text" name="hospita_lang" required><br>
		<label>user_name:</label>
		<input type="text" name="user_name" required><br>
		<label>Password</label>
		<input type="password" name="password" required><br>
		<label>Select option to provide data:</label><br>
		<input type="radio" name="option" value=1 required>
		<label>Directly connect to server.</label><br>
		<input type="radio" name="option" value=2 required>
		<label>Update manually.</label><br>
		<input type="submit" name="submit" value="submit">
	</form>
</body>
</html>