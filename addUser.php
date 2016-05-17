<?php
/*
 * Following code will create a new user row
 * All user details are read from HTTP Post Request
 * Created and debugged by Samuel Cheung
 */
  
// check for required fields
/*
name			string
username		string
password		string
email			string
role			string (Tutor, Tutee, Both)
gpa				double(4,3)
dob				date
graduation date	int(4)
major			string
*/
if (isset($_POST['username']) 			&& isset($_POST['password']) 
	&& isset($_POST['email']) 			&& isset($_POST['type']) 
	/*&& isset($_POST['gpa'])*/			&& isset($_POST['first_name'])
	&& isset($_POST['last_name'])		&& isset($_POST['dob'])
	&& isset($_POST['graduation_year'])	&& isset($_POST['major'])) {
	
	require_once __DIR__ . '/database_handler.php';
	$db = new database_handler();
	
	if(!$stmt = $db->con->prepare("INSERT INTO USERS (id, username, encrypted_password, email, 
									type, gpa, first_name, last_name, dob,
									graduation_year, major) 
									VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")){
		echo "Prepare failed: (" .$db->con->errno . ")" . $db->con->error;
	}
	if(!$stmt->bind_param("ssssdsssis", $username, $encrypted_password, $email, $type, $gpa, 
										$first_name, $last_name, $dob,
										$graduation_year, $major)){
		echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
	}
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
	$type = $_POST['type'];
	$gpa = $_POST['gpa'];
	if($gpa!="NULL"){
		$gpa = floatval($gpa);
		$gpa = (float)$gpa;
	}
	$first_name = $_POST['first_name'];
	$last_name = $_POST['last_name'];
	$dob = $_POST['dob'];
	$graduation_year = $_POST['graduation_year'];
	$major = $_POST['major'];
	
	$encrypted_password = password_hash($password, PASSWORD_DEFAULT);
	
	if(!$stmt->execute()){
		echo "Execute failed: (" .$stmt->errno . ") " . $stmt->error;
	}
	else{
		if(!$idStmt = $db->con->prepare("Select id FROM USERS WHERE username = ?")){
			echo "Prepare failed: (" . $db->con->errno . ")" . $db->con->error;
		}
		if(!$idStmt->bind_param("s", $username)){
			echo "Binding parameters failed: (" . $idStmt->errno . ") " . $idStmt->error;
		}
		if(!$idStmt->execute()){
			echo "Execute failed: (" . $idStmt->errno . ") " . $idStmt->error;
		}
		
		$idStmt->bind_result($id);
		$idStmt->fetch();
		$idStmt->close();
		//Insert into temp and delete when user confirms email
		mysqli_query($db->con, "INSERT INTO temp(id,`key`,time) VALUES('$id','$encrypted_password',NOW())");
		if($type=='Tutor'){
			/*
			$idResult = mysqli_fetch_assoc(mysqli_query($db->con, "SELECT id FROM USERS WHERE username='$username'"));
			$id = $idResult['id'];	
			*/
			mysqli_query($db->con,"INSERT INTO tutorInfo (id) VALUES('$id')");
		}
		$to = $email;
		$subject = "TutorU Email Notification";
		$txt = "This email is just to notify you that this email has been used for registration. Click on the provided link to confirm your account.
		tutoru.mooo.com/confirm.php?key=$encrypted_password"."\n\nThanks for registering for TutorU!";
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
}
else{
	?>
<!--For browser testing-->
<html>
<head>
<title>Add New User</title>
</head>
<body>
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

<td width="250">First Name</td>
<td>
<input name="first_name" required type="text" id="first_name" maxlength="256">
</td>
</tr>

<td width="250">Last Name</td>
<td>
<input name="last_name" required type="text" id="last_name" maxlength="256">
</td>
</tr>

<td width="250">Date of Birth</td>
<td>
<input name="dob" required type="date" id="dob" maxlength="256">
</td>
</tr>

<td width="250">Graduation Year</td>
<td>
<input name="graduation_year" required type="number" id="graduation_year" maxlength="4">
</td>
</tr>

<td width="250">Major</td>
<td>
<input name="major" required type="text" id="major" maxlength="256">
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
</body>
</html>
<?php
}
?>