<?php
	session_start();
	require 'pdo.php';
	if(!isset(($_GET['hid']))){
		$_SESSION['error'] = "Please enter hospital id to update rating.";
		header("Location: main.php");
		return;
	}
	if(!isset($_SESSION['user_id'])){
		$_SESSION['error'] = "Please first login.";
		header("Location: main.php");
		return;
    }
	if(isset($_POST['submit'])){
		if($_POST['r']>=0 and $_POST['r']<=5){
			$uid = $_SESSION['user_id'];
			$hid = $_GET['hid'];
			$usr_rat = "SELECT rating FROM bed_trcker.rating WHERE hospital_id = :hid and user_id = :uid";
			$stmt_usr_rat = $pdo->prepare($usr_rat);
			$stmt_usr_rat->execute(array('hid'=>$hid,'uid'=>$uid));
			$data = $stmt_usr_rat->fetch(PDO::FETCH_ASSOC);
			if($data == null){
				// insert
				$sql = "INSERT INTO bed_trcker.rating(`user_id`, `hospital_id`, `rating`) VALUES( :uid ,:hid ,:r )";
				$stmt = $pdo->prepare($sql);
				$stmt->execute(array(
					"uid" => $uid,
					"hid" => $hid,
					"r" => $_POST['r']
				));
			}
			else{
				// update
				$sql = "UPDATE bed_trcker.rating SET rating = :r WHERE user_id = :uid AND hospital_id = :hid";
				$stmt = $pdo->prepare($sql);
				$stmt->execute(array(
					"uid" => $uid,
					"hid" => $hid,
					"r" => $_POST['r']
				));
			}
			$_SESSION['error'] = "Rating updated";
			header("Location: rating.php?hid=".$_GET['hid']);
			return;
		}
		else{
			$_SESSION['error'] = "Enter between 0 to 5";
			header("Location: rating.php?hid=".$_GET['hid']);
			return;
		}
	}
	if(isset($_POST['cancel'])){
		header("Location: main.php");
		return;
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>rating</title>
</head>
<body>
	<?php
		if(isset($_SESSION['error'])){
			echo "<p style = 'color : red;'>".$_SESSION['error']."</p>";
			unset($_SESSION['error']);
		}
		$uid = $_SESSION['user_id'];
		$hid = $_GET['hid'];
		$usr_rat = "SELECT hospital_name FROM bed_trcker.hospital_details WHERE hospital_id = :hid";
		$stmt_usr_rat = $pdo->prepare($usr_rat);
		$stmt_usr_rat->execute(array('hid'=>$hid));
		$data = $stmt_usr_rat->fetch(PDO::FETCH_ASSOC);
		if($data!=null){
		echo "<br>Welcome user id: ".$_SESSION['user_id'].", update for hospital: ".$data["hospital_name"]."<br><br>";
		}
		else
		echo "<br>Welcome user id: ".$_SESSION['user_id'].", update for hospital id: ".$_GET['hid']."<br><br>";
		$uid = $_SESSION['user_id'];
		$hid = $_GET['hid'];
		$usr_rat = "SELECT rating FROM bed_trcker.rating WHERE hospital_id = :hid and user_id = :uid";
		$stmt_usr_rat = $pdo->prepare($usr_rat);
		$stmt_usr_rat->execute(array('hid'=>$hid,'uid'=>$uid));
		$data = $stmt_usr_rat->fetch(PDO::FETCH_ASSOC);
		if($data != null){
		echo ("Current given rating: ".$data["rating"]."<br><br>");    
		}
	?>
	<form method="post">
		<label>New rating</label>
		<input type="text" name="r"><br>
		<input type="submit" name="submit" value="submit">
		<input type="submit" name="cancel" value="cancel"><br>
	</form>
</body>
</html>