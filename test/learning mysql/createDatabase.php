<html>
<head>
<title>Creating MySQL Database</title>
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

echo ' Connected successfully<br />';
$sql = "CREATE DATABASE TutorU";
$retval = mysql_query($sql, $conn);
if(!$retval){
	die('Could not create database: ' . mysql_error());
}

echo "Database TutorU created successfully\n";
mysql_close($conn);
?>
</body>
</html>