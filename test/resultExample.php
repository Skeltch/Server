<?php
$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = 'password';
$conn = mysql_connect($dbhost, $dbuser, $dbpass);
if(!$conn){
	die('Could not connect: ' . mysql_error());
}
$sql = 'SELECT id, username, password, email, type, gpa, 
		first_name, last_name, dob, graduation_year, major
		FROM users
		ORDER BY id DESC';
		
mysql_select_db('TutorU');
$retval = mysql_query($sql, $conn);
if(!$retval){
	die('Could not get data: ' . mysql_error());
}

while($row = mysql_fetch_array($retval, MYSQL_ASSOC)){
	echo "User ID : {$row['id']} <br> ".
			"Username: {$row['username']} <br> ".
			"Password: {$row['password']} <br> ".
			"E-mail : {$row['email']} <br>".
			"Type: {$row['type']} <br>".
			"GPA: {$row['gpa']} <br>".
			"First Name:  {$row['first_name']} <br>".
			"Last Name: {$row['last_name']} <br>".
			"DOB: {$row['dob']} <br>".
			"Graduation Year: {$row['graduation_year']} <br>".
			"Major: {$row['major']} <br>".
			"-----------------------------------------------<br>";
}
echo "Fetched data successfully\n";
mysql_close($conn);
?>