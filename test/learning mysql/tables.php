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
$users = "CREATE TABLE users( 
		id INT NOT NULL PRIMARY KEY AUTO_INCREMENT, 
		username VARCHAR(16) NOT NULL, 
		encrypted_password VARCHAR(255) NOT NULL, 
		email VARCHAR(255),
		type ENUM('Tutor', 'Tutee', 'Both') NOT NULL,
		gpa FLOAT(4,3) NOT NULL,
		first_name varchar(35) NOT NULL,
		last_name varchar(35),
		graduation_year int(4),
		dob date,
		major varchar(255));";
		
mysql_select_db('tutoru');
$retval = mysql_query($users, $conn);
$tutorInfo= "CREATE TABLE tutorInfo( id int(11) NOT NULL PRIMARY KEY,
								description text,
								rating int(1),
								price double(4,2));";
mysql_query($tutorInfo,$conn);

$classes = "CREATE TABLE classes(id int(11) NOT NULL, classes text);";
mysql_query($classes,$conn);

if(!$retval){
	die('Could not create table: ' . mysql_error());
}

$review = "CREATE TABLE review(tutorID int(11) NOt NULL, reviewerID int(11) NOT NULL, name varchar(70),
								title varchar(100), review varchar(500), rating float(2,1));";
mysql_query($review, $conn);

$image = "CREATE TABLE image(id int(11), image longblob);";

mysql_query($image, $conn);
								

echo "Table created successfully\n";
mysql_close($conn);
?>
</body>
</html>
