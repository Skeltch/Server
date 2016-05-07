<?php 
/*
Script used to check uniqueness of username when user is registering
Created and debugged by Samuel Cheung
*/
if(isset($_POST['username'])){
	
	require_once __DIR__ . '/database_handler.php';
	
	$db = new database_handler();
	
	if(!$stmt = $db->con->prepare("SELECT id FROM USERS WHERE username = ?")){
		echo "Prepare failed: (" . $db->con->errno . ")" . $db->con->error;
	}
	if(!$stmt->bind_param("s", $username)){
		echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
	}
	$username = $_POST['username'];
	if(!$stmt->execute()){
		echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
	}
	//Any usernames found in table
	if(mysqli_stmt_get_result($stmt)->num_rows>0){
		echo "Username Taken";
	}
	else{
		echo "Unique Username";
		exit;
	}
}


?>