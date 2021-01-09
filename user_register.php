<?php
	require "pdo.php"; 
	session_start();
	if(isset($_POST["submit"])){
		// echo $_POST['email'];
		$sql0 = "SELECT user_id FROM bed_trcker.user_details where user_name=:user OR email=:email";
		$stmt0 = $pdo->prepare($sql0);
		$stmt0->execute(array("user" => $_POST['usern'],"email"=>$_POST['email']));
		$row0 = $stmt0->fetch(PDO::FETCH_ASSOC);
		if($row0!==false){
			$_SESSION['error'] = "Username or email should be unique.";
			header("Location: user_register.php");
			return;
		}
		$sql = "INSERT INTO bed_trcker.user_details( `user_name`, `email`, `pass`) VALUES( :name ,:email ,:pass )";
		$stmt = $pdo->prepare($sql);
		$salt = "unkown";
		$hash_code  =hash('md5',$salt.$_POST['pass']);
		$stmt->execute(array(
			"name" => $_POST['usern'],
			"email" => $_POST['email'],
			"pass" => $hash_code
		));
		// setting seesion
		$sql1 = "SELECT * FROM bed_trcker.user_details WHERE email = :email";
		$stmt1 = $pdo->prepare($sql1);
		$stmt1->execute(array('email'=>$_POST['email']));
		$row1 = $stmt1->fetch(PDO::FETCH_ASSOC);
		// echo $row1['user_id'];
		header('Location: user_login.php');
		return ;
		// unset($_POST['submit']);
	}
?>
<html>
    <head>
    </head>
    <body>
    	<?php
    		if(isset($_SESSION['error'])){
    			echo "<p style = 'color : red;'>".$_SESSION['error']."</p>";
    			unset($_SESSION['error']);
    		}
    	?>
        <form method="post">
        	<label>Username:</label>
        	<input type="text" name="usern" required><br>
        	<label>Email:</label>
        	<input type="text" name="email" required><br>
        	<label>Password:</label>
        	<input type="text" name="pass" required><br>
        	<input type="submit" name="submit"><br>
        </form>
    </body>
</html>