<?php
/*
 * Following code will create a new user row
 * All user details are read from HTTP Post Request
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
optional resume	not implemented
*/
if (isset($_POST['username']) 			&& isset($_POST['password']) 
	&& isset($_POST['email']) 			&& isset($_POST['type']) 
	&& isset($_POST['gpa']) 			&& isset($_POST['first_name'])
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
	$gpa = floatval($gpa);
	$gpa = (float)$gpa;
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
		if($type=='Tutor'){
			if(!$tutorStmt = $db->con->prepare("Select id FROM USERS WHERE username = ?")){
				echo "Prepare failed: (" . $db->con->errno . ")" . $db->con->error;
			}
			if(!$tutorStmt->bind_param("s", $username)){
				echo "Binding parameters failed: (" . $tutorStmt->errno . ") " . $tutorStmt->error;
			}
			if(!$tutorStmt->execute()){
				echo "Execute failed: (" . $tutorStmt->errno . ") " . $tutorStmt->error;
			}
			
			$tutorStmt->bind_result($id);
			$tutorStmt->fetch();
			$tutorStmt->close();
			/*
			$idResult = mysqli_fetch_assoc(mysqli_query($db->con, "SELECT id FROM USERS WHERE username='$username'"));
			$id = $idResult['id'];	
			*/
			mysqli_query($db->con,"INSERT INTO tutorInfo (id) VALUES('$id')");
		}
		$to = $email;
		$subject = "TutorU Email Notification";
		$txt = "This email is just to notify you that this email has been used for registration. If this was not you click on the link provided and we will remove their account from our database.\n
		**ONLY CLICK THIS TO DELETE YOUR ACCOUNT**\n
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
		/*
		$to = $email;
		$subject = "Confirmation Email for TutorU";
		$txt = "This email is just to notify you that this email has been used for registration. If this was not you click on the link provided and we will remove their account from our database.\n
		**ONLY CLICK THIS TO DELETE YOUR ACCOUNT**\n
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
		*/
		//echo "success";
	}
	/*
			if(!$tutorStmt = $db->con->prepare($tutorQuery)){
			echo "Prepare failed: (" . $db->con->errno . ")" . $db->con->error;
		}
		$tutorStmt->execute();
		$tutorStmt->bind_result($description, $rating, $price);
		$tutorStmt->fetch();
		$outputInfo = array('username'=>$username, 'first_name'=>$firstName,
						'last_name'=>$lastName, 'email'=>$email, 'gpa'=>$gpa, 'graduation_year'=>$gradYear,
						'major'=>$major, 'description'=>$description);
		$tutorStmt->close();
		
		//Are prepared statements necessary here as all classes will be strings that we decide
		$classesQuery ="SELECT classes FROM CLASSES WHERE id = '$id' ORDER BY CLASSES";
		$resultClasses = mysqli_query($db->con, $classesQuery) or die ("Error in selecting " . mysqli_error($db->con));
		while($row = mysqli_fetch_assoc($resultClasses)){
			$outputClasses[] = $row;
		}
 
	/*Testing
	if(gettype($gpa)=="double"){
	$result = mysqli_query($db->con,"INSERT INTO TEST (num) VALUES (NULL)");
	*/
}
/*
else{
	echo "No inputs";
}
*/
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