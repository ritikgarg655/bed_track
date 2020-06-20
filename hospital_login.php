<?php 
	require_once 'pdo.php';
	session_start();
	if(isset($_POST['submit'])){
		$sql = "SELECT hospital_id,pass,option_selected FROM bed_trcker.Hospital_details WHERE username=:usr";	
		$stmt = $pdo->prepare($sql);
		$salt = "unkown";
		$hashed_pass = hash('md5',$salt.$_POST['password']);
		$stmt->execute(array('usr'=>$_POST['username']));
   		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		// var_dump($row["pass"]);
		// echo($hashed_pass);
		if($hashed_pass==$row["pass"]){
			echo 'Succesful logined';
			// $_SESSION['id'] = 0;
			$_SESSION['id'] = $row['hospital_id'];
			if($row['option_selected']==1)
				header("Location: hospital_show_ip.php");
			else{
				header("Location: option_b_show_update.php");
			}
			// die();
			return;
		}
		else{
			$_SESSION['error'] = 'Incorect password/username.';
			header("Location: hospital_login.php");
			return ;
		}
	}
?>

<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<?php 
		if(isset($_SESSION['error'])){
			echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
			unset($_SESSION['error']);
		}
	?>
	<form method="post">
		<label>Username:</label>
		<input type="text" name="username"><br>
		<label>Password:</label>
		<input type="password" name="password"><br>
		<input type="submit" name="submit" value="submit">
	</form>
</body>
</html>