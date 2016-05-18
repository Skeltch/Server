<?php
	
	require_once __DIR__ . '/database_handler.php';
	$db = new database_handler();
	
	if(isset($_POST['username'])){
		$query="SELECT email, encrypted_password FROM USERS WHERE username=?";
		if(!$stmt = $db->con->prepare($query)){
			echo "Prepare failed: (" . $db->con->errno . ")" . $db->con->error;
		}
		if(!$stmt->bind_param("s",$username)){
			echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
		}
		$username = $_POST['username'];
		$stmt->execute();
		$stmt->bind_result($email, $key);
		if($stmt->fetch()){
			$subject="Password Retrieval";
			$txt="Click this link to reset your password. If this was not you please ignore this email.\ntutoru.mooo.com/reset.php?key=$key";
			$found=true;
		}
		else{
			$found=false;
		}
	}
	else if(isset($_POST['email'])){
		$query="SELECT email, encrypted_password FROM USERS WHERE email=?";
		if(!$stmt = $db->con->prepare($query)){
			echo "Prepare failed: (" . $db->con->errno . ")" . $db->con->error;
		}
		if(!$stmt->bind_param("s",$email)){
			echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
		}
		$email=$_POST['email'];
		$stmt->execute();
		$stmt->bind_result($email, $key);
		if($stmt->fetch()){
			$subject="Password Retrieval";
			$txt="Click this link to reset your password. If this was not you please ignore this email.\ntutoru.mooo.com/reset.php?key=$key";
			$found=true;
		}
		else{
			$found=false;
		}
	}
	else if(isset($_POST['contactUs'])){
		$email="Scheung901@gmail.com";
		$subject="Forgot Password Help";
		$txt=$_POST['contactUs'];
		$found=true;
	}
	if($found){
		$to = $email;
		$headers = "From: RutgersTutorU@gmail.com" . "\r\n" .
		"Reply-To: RutgersTutorU@gmail.com" . "\r\n" .
		"X-Mailer: PHP/" . phpversion();
		if(mail($to,$subject,$txt,$headers)){
			 echo "success";
		}
		else{
			 echo "failed";
		}
	}
	else{
		echo "Not Found";
	}
?>