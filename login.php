<?php

//Checking credentials

if(isset($_POST['username']) && isset($_POST['password'])){
	
	require_once __DIR__ . '/database_handler.php';
	
	$db = new database_handler();
	
	if(!$stmt = $db->con->prepare("SELECT id, username, password, email, type, gpa,
									first_name, last_name, dob, graduation_year, major 
									FROM USERS WHERE username = ? AND password = ?")){
		echo "Prepare failed: (" . $db->con->errno . ")" . $db->con->error;
	}
	if(!$stmt->bind_param("ss", $username, $password)){
		echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
	}
	
	$username = $_POST['username'];
	$password = $_POST['password'];
	if(!$stmt->execute()){
		echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
	}
	if(mysqli_stmt_get_result($stmt)->num_rows>0){
		$getID = mysqli_fetch_assoc(mysqli_query($db->con,"SELECT id FROM users WHERE username = '$username'"));
		$userID = $getID['id'];
		$output = array('result'=>'success' , 'id'=>$userID);
		echo json_encode($output);
		exit;
	}
	else{
		$output = array('result'=>'Login Failed' , 'id'=>'null');
		echo json_encode($output);
	}
}

else{
	?>
	<form method="post" action="<?php $_PHP_SELF ?>">
	<table width="600" border="0" cellspacing="1" cellpadding="2">
	<tr>
	<td width="250">Username</td>
	<td>
	<input name="username" required type="text" id="username" maxlength="16">
	</td>
	</tr>
	<tr>
	<td width="250">Password</td>
	<td>
	<input name="password" required type="password" id="password" maxlength="25">
	</td>
	</tr>
	<tr>
	<td width="250"> </td>
	<td>
	<input name="add" type="submit" id="add" value="Login">
	</td>
	</tr>
	</table>
	</form>
<?php
}
?>
</body>
</html>