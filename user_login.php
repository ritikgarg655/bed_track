<?php
	session_start();
	require 'pdo.php';
	if(isset($_POST['submit'])){
		$sql0 = "SELECT * FROM bed_trcker.user_details where (user_name=:user OR email=:emai) AND pass = :pas" ;
		$stmt0 = $pdo->prepare($sql0);
		$salt = "unkown";
		$hash_code  =hash('md5',$salt.$_POST['pass']);
		$stmt0->execute(array("user" => $_POST['ue'],"emai"=>$_POST['ue'],"pas"=>$hash_code));
		$row0 = $stmt0->fetch(PDO::FETCH_ASSOC);
		if($row0===false){
			$_SESSION['error'] = "Username or Password Invalid.";
			header("Location: user_login.php");
			return;
		}
		else{
			$_SESSION["user_id"] = $row0['user_id'] ;
			header("Location: main.php");
			return;
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
			echo "<p style = 'color : red;'>".$_SESSION['error']."</p>";
			unset($_SESSION['error']);
		}
	?>
	<form method="post">
		<label>Username/email:</label>
		<input type="text" name="ue"><br>
		<label>Password:</label>
		<input type="password" name="pass"><br>
		<input type="submit" name="submit" value="submit"><br>
	</form>
</body>
</html>