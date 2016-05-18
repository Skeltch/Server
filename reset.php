<?php

	require_once __DIR__ . '/database_handler.php';
	$db = new database_handler();
	if(isset($_GET['key'])){
		$key = $_GET['key'];
		$query="SELECT id FROM USERS WHERE encrypted_password=?";
		if(!$stmt = $db->con->prepare($query)){
			echo "Prepare failed: (" . $db->con->errno . ")" . $db->con->error;
		}
		if(!$stmt->bind_param("s",$key)){
			echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
		}
		$stmt->execute();
		$stmt->bind_result($id);
		if($stmt->fetch()  and (!isset($_POST['password']) || $_POST['password']!=$_POST['confirmPassword'])){
			?>
			<html>
			<head>
			<title>Reset Password</title>
			</head>
			<body>
			<form method="post" action="<?php $_PHP_SELF ?>">
			<table width="600" border="0" cellspacing="1" cellpadding="2">
			<tr>
			<td width="250">New Password</td>
			<td>
			<input name="password" required type="password" id="password" maxlength="255">
			</td>
			</tr>
			<tr>
			<td width="250">Confirm Password</td>
			<td>
			<input name="confirmPassword" required type="password" id="confirmPassword" maxlength="255">
			</td>
			</tr>
			<tr>
			<td width="250"> </td>
			<td> </td>
			</tr>
			<tr>
			<td width="250"> </td>
			<td>
			<input name="submit" type="submit" id="submit" value="submit">
			</td>
			</tr>
			</table>
			</form>
			</body>
			</html>
			<?php
		}
		else if(!isset($_POST['password'])){
			echo "Incorrect key. Please check the URL sent to you again and make sure they are the same.";
		}
		$stmt->close();
	}
	if(isset($_POST['submit'])){
		//set new password
		if($_POST['password']!=$_POST['confirmPassword']){
			echo "Passwords do not match. Try again.";
		}
		else{
			$query="UPDATE USERS SET encrypted_password = ? WHERE id=$id";
			if(!$updateStmt = $db->con->prepare($query)){
				echo "Prepare failed: (" . $db->con->errno . ")" . $db->con->error;
			}
			if(!$updateStmt->bind_param("s",$encrypted_password)){
				echo "Binding parameters failed: (" . $updateStmt->errno . ") " . $updateStmt->error;
			}
			$encrypted_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
			if($updateStmt->execute()){
				echo "Password changed successfully";
			}
		}
	}
?>
