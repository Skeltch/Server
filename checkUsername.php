<?php 

if(isset($_POST['username'])){
	
	require_once __DIR__ . '/database_handler.php';
	
	$db = new database_handler();
	
	if(!$stmt = $db->con->prepare("SELECT id, username, password, email, type, gpa,
									first_name, last_name, graduation_year, dob, major
									FROM USERS WHERE username = ?")){
		echo "Prepare failed: (" . $db->con->errno . ")" . $db->con->error;
	}
	if(!$stmt->bind_param("s", $username)){
		echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
	}
	$username = $_POST['username'];
	if(!$stmt->execute()){
		echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
	}
	if(mysqli_stmt_get_result($stmt)->num_rows>0){
		echo "Username Taken";
	}
	else{
		echo "Unique Username";
		exit;
	}
}


?>