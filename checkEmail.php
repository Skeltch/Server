<?php 
/*
Check uniquness of email when user is registering
Created and debugged by Samuel Cheung
*/
if(isset($_POST['email'])){
	
	require_once __DIR__ . '/database_handler.php';
	
	$db = new database_handler();
	
	if(!$stmt = $db->con->prepare("SELECT id FROM USERS WHERE email = ?")){
		echo "Prepare failed: (" . $db->con->errno . ")" . $db->con->error;
	}
	if(!$stmt->bind_param("s", $email)){
		echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
	}
	$email = $_POST['email'];
	if(!$stmt->execute()){
		echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
	}
	if(mysqli_stmt_get_result($stmt)->num_rows>0){
		echo "Email is already in use";
	}
	else{
		echo "Unique Email";
		exit;
	}
}


?>