<html>
<head>
<title>Add New User</title>
</head>
<body>
<?php
if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['email']) && isset($_POST['type']) && isset($_POST['gpa']))
//if(isset($_POST['username']))
{
	require_once __DIR__ . '/db_connect.php';
	//$gpa = floatval($gpa);
	//$sql = "INSERT INTO TEST (num) VALUES (NULL)";
	$db = new DB_CONNECT();
	$conn = $db->con;
	/*
	if(!($stmt = $mysqli->prepare("INSERT INTO USERS(id, username, password, email, type, gpa) VALUES (NULL, ?, ?, ?, ?, ?,) "))){
		echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
	}
	*/
	if(!$stmt = $conn->prepare("INSERT INTO USERS(id, username, password, email, type, gpa) VALUES (NULL, ?, ?, ?, ?, ?) ")){
		echo "Prepare failed: (" . $conn->errno . ")" . $conn->error;
	}
	if(!$stmt->bind_param("ssssd", $username, $password, $email, $type, $gpa)){
		echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
	}
	$username = $_POST['username'];
	$password = $_POST['password'];
	$email = $_POST['email'];
	$type = $_POST['type'];
	$gpa = $_POST['gpa'];
	
	if(!$stmt->execute()){
		echo "Execute failed: (" .$stmt->errno . ") " . $stmt->error;
	}
	/* UNSAFE
	$sql = "INSERT INTO USERS ".
		"(id, username, password, email, type, gpa)".
		"VALUES ".
		"(NULL,'$username', '$password', '$email', '$type', '$gpa')";
	*/
		
	//mysql_select_db('users');
	echo "success";
}
else
{
?>
<style>
input[type=number]::-webkit-inner-spin-button, 
input[type=number]::-webkit-outer-spin-button { 
  -webkit-appearance: none; 
  margin: 0; 
}
</style>
<!-- Need a min length for username and password-->
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
<td width="250">email</td>
<td>
<input name="email" required type="email" id="email" maxlength="256">
</td>
</tr>
<tr>
<td width="250">GPA</td>
<td>
<input name="gpa" required type="number" step=".001" min="0" max="4" maxlength="4" id="gpa">
</td>
</tr>
<tr>
<!-- //Change to type to tutor eventually-->
<td width="250">Role</td>
<td>
<!-- Make sure they have to choose a role, right now it lets you add a user without choosing a role-->
<select name="type">
<option selected = "selected" disabled="disabled">Choose role</option>
<option value ="Tutor">Tutor</option>
<option value ="Tutee">Tutee</option>
<option value ="Both">Both</option>
</select>
</td>
</tr>
<tr>
<td width="250"> </td>
<td> </td>
</tr>
<tr>
<td width="250"> </td>
<td>
<input name="add" type="submit" id="add" value="Add User">
</td>
</tr>
</table>
</form>
<?php
}
?>
</body>
</html>