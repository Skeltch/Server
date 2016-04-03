<html>
<head>
<title>Creating MySQL Tables</title>
</head>
<body>
<?php
$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = 'password';
$conn = mysql_connect($dbhost, $dbuser, $dbpass);
if(!$conn){
	die('Could not connect: ' .mysql_error());
}
echo 'Connected successfully<br \>';
//Using the id we can join the user's courses table and other data tables
//Might want to include more information like date created and or last updated
$sql = "CREATE TABLE users( ".
		"id INT NOT NULL PRIMARY KEY AUTO_INCREMENT, ".
		"username VARCHAR(16) NOT NULL, ".
		"password VARCHAR(128) NOT NULL, ".
		"email VARCHAR(255),".
		"type ENUM('Tutor', 'Tutee', 'Both') NOT NULL,".
		"gpa FLOAT(4,3) NOT NULL,
		first_name varchar(35) NOT NULL,
		last_name varchar(35),
		graduation_year int(4),
		dob date,
		major varchar(255));";
		
mysql_select_db('tutoru');
$retval = mysql_query($sql, $conn);
$sql1= "CREATE TABLE tutorInfo( id int(11) NOT NULL,
								classes text,
								description text,
								rating int(1),
								price double(4,2))";
mysql_query($sql1,$conn);
if(!$retval){
	die('Could not create table: ' . mysql_error());
}

echo "Table created successfully\n";
mysql_close($conn);
?>
</body>
</html>
