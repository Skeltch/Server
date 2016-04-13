<?php
	require_once __DIR__ . '/database_handler.php';
	$db = new database_handler();

	//This php script was created for security. There is no reason to send the password
	//And other personal information
	$id = $_POST['id'];
	
	//Include email?
	$infoQuery = "SELECT a.username, a.first_name, a.last_name, a.gpa, a.graduation_year, a.major, b.description
					FROM users a, tutorInfo b
					WHERE a.id='$id' AND b.id='$id'";
	if(!$infoStmt = $db->con->prepare($infoQuery)){
		echo "Prepare failed: (" . $db->con->errno . ")" . $db->con->error;
	}
	$infoStmt->execute();
	$infoStmt->bind_result($username, $firstName, $lastName, $gpa,
						$gradYear, $major, $description);
	$infoStmt->fetch();
	$outputInfo = array('username'=>$username, 'first_name'=>$firstName,
						'last_name'=>$lastName, 'gpa'=>$gpa, 'graduation_year'=>$gradYear,
						'major'=>$major, 'description'=>$description);
	$infoStmt->close();
					
	$classesQuery ="SELECT * FROM CLASSES WHERE id = '$id' ORDER BY CLASSES";
	$resultClasses = mysqli_query($db->con, $classesQuery) or die ("Error in selecting " . mysqli_error($db->con));
	$outputClasses = array();
	while($row = mysqli_fetch_assoc($resultClasses)){
		$outputClasses[] = $row;
	}
	$output = array('info'=>$outputInfo, 'classes'=>$outputClasses);
	echo json_encode($output);
?>