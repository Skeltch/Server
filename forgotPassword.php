<?php
	require_once __DIR__ . '/database_handler.php';
	$db = new database_handler();
	
	if(isset($_POST['username'])){
		$query="SELECT email FROM USERS WHERE username=?";
		if(!$stmt = $db->con->prepare($query)){
			echo "Prepare failed: (" . $db->con->errno . ")" . $db->con->error;
		}
		if(!$stmt->bind_param("s",$username)){
			echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
		}
		$username = $_POST['username'];
		$stmt->execute();
		$stmt->bind_result($email);
		if($stmt->fetch()){
			$subject="Password Retrieval";
			$txt="TODO";
			$found=true;
		}
		else{
			$found=false;
		}
	}
	else if(isset($_POST['email'])){
		$query="SELECT email FROM USERS WHERE email=?";
		if(!$stmt = $db->con->prepare($query)){
			echo "Prepare failed: (" . $db->con->errno . ")" . $db->con->error;
		}
		if(!$stmt->bind_param("s",$email)){
			echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
		}
		$email=$_POST['email'];
		$stmt->execute();
		$stmt->bind_result($email);
		if($stmt->fetch()){
			$subject="Password Retrieval";
			$txt="TODO";
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