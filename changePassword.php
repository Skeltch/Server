<?php 
	require_once __DIR__ . '/database_handler.php';
	$db = new database_handler();
	
	if(isset($_POST['password'])){
		$checkQuery = "SELECT encrypted_password FROM USERS where id = ?";
		
		if(!$checkStmt = $db->con->prepare($checkQuery)){
			echo "Prepare failed: (" . $db->con->errno . ")" . $db->con->error;
		}
		if(!$checkStmt->bind_param("s", $id)){
		echo "Binding parameters failed: (" . $checkStmt->errno . ") " . $checkStmt->error;
		}	
		$id = $_POST['id'];
		$checkStmt->execute();
		$checkStmt->bind_result($encrypted_password);
		$checkStmt->fetch();
		$checkStmt->close();
		$password=$_POST['password'];
		
		if(password_verify($password,$encrypted_password)){
			echo "success";
		}
		else{
			echo "failed";
		}
	}
	else if(isset($_POST['newPassword'])){
		$password = $_POST['newPassword'];
		$id = $_POST['id'];
		$query = "UPDATE USERS SET encrypted_password=? WHERE id='$id'";
		
		if(!$stmt = $db->con->prepare($query)){
		echo "Prepare failed: (" .$db->con->errno . ")" . $db->con->error;
		}
		if(!$stmt->bind_param("s",$encrypted_password)){
			echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
		}
		$encrypted_password = password_hash($password, PASSWORD_DEFAULT);
		
		if(!$stmt->execute()){
			echo "Execute failed: (" .$stmt->errno . ") " . $stmt->error;
		}
		echo "changed";
	}
?>