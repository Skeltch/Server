<?php
error_reporting(E_ALL);
ini_set('display_errors',1);
//Checking credentials

if(isset($_POST['username']) && isset($_POST['password'])){
	
	require_once __DIR__ . '/database_handler.php';
	
	$db = new database_handler();
	
	$checkQuery = "SELECT encrypted_password FROM USERS where username = ?";
	
	if(!$checkStmt = $db->con->prepare($checkQuery)){
		echo "Prepare failed: (" . $db->con->errno . ")" . $db->con->error;
	}
	if(!$checkStmt->bind_param("s", $username)){
	echo "Binding parameters failed: (" . $checkStmt->errno . ") " . $checkStmt->error;
	}	
	$username = $_POST['username'];
	$password = $_POST['password'];
	$checkStmt->execute();
	$checkStmt->bind_result($encrypted_password);
	$checkStmt->fetch();
	$checkStmt->close();
	
	if(password_verify($password,$encrypted_password)){
	
		if(!$stmt = $db->con->prepare("SELECT id, username, encrypted_password, type, first_name, last_name, verified
										FROM USERS WHERE username = ? AND encrypted_password = ?")){
			echo "Prepare failed: (" . $db->con->errno . ")" . $db->con->error;
		}
		if(!$stmt->bind_param("ss", $username, $encrypted_password)){
			echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
		}
		
		if(!$stmt->execute()){
			echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
		}
		$stmt->store_result();
		if($stmt->num_rows>0){
			$stmt->bind_result($userID, $username, $encrypted_password, $role, $first_name, $last_name, $verified);
			$stmt->fetch();
			$output = array('result'=>'success' , 'id'=>$userID, 'role'=>$role, 'first_name'=>$first_name, 'last_name'=>$last_name);
			echo json_encode($output);
			exit;
		}
		else{
			$output = array('result'=>'Login Failed' , 'id'=>'null', 'role'=>'null');
			echo json_encode($output);
		}
	}
	else{
		$output = array('result'=>'Login Failed' , 'id'=>'null', 'role'=>'null');
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