<?php
	require_once __DIR__ . '/database_handler.php';
	$db = new database_handler();
	
	//Add WHERE rating>=x
	$idResult = mysqli_fetch_assoc(mysqli_query($db->con, "SELECT id FROM tutorInfo WHERE rating>=3 ORDER BY RAND() LIMIT 1"));
	$id = $idResult['id'];
	
	$infoQuery = "SELECT a.first_name, a.last_name, a.gpa, a.graduation_year, a.major, b.description
					FROM users a, tutorInfo b
					WHERE a.id='$id' AND b.id='$id'";
					
	if(!$infoStmt = $db->con->prepare($infoQuery)){
		echo "Prepare failed: (" . $db->con->errno . ")" . $db->con->error;
	}
	$infoStmt->execute();
	$infoStmt->bind_result($firstName, $lastName, $gpa,
						$gradYear, $major, $description);
	$infoStmt->fetch();
	$outputInfo = array('id'=>$id, 'first_name'=>$firstName, 'last_name'=>$lastName, 'gpa'=>$gpa, 
						'graduation_year'=>$gradYear, 'major'=>$major, 'description'=>$description);
	$infoStmt->close();		
	
	$classesQuery ="SELECT * FROM CLASSES WHERE id = '$id' ORDER BY CLASSES";
	$resultClasses = mysqli_query($db->con, $classesQuery) or die ("Error in selecting " . mysqli_error($db->con));
	$outputClasses = array();
	while($row = mysqli_fetch_assoc($resultClasses)){
		$outputClasses[] = $row;
	}
	$output = array('info'=>$outputInfo, 'classes'=>$outputClasses);
	echo json_encode($output);
	/*
	$infoResult = mysqli_query($db->con,"SELECT username, password, first_name, last_name, email, gpa,
										graduation_year, major FROM users WHERE id = '$id'") 
										or die ("Error in selecting " . mysqli_error($db->con));
	$info = mysqli_fetch_assoc($infoResult);
														
	$outputInfo = array('id'=>$id,'username'=>$info['username'],'password'=>$info['password'],'first_name'=>$info['first_name'],
					'last_name'=>$info['last_name'],'email'=>$info['email'],'gpa'=>$info['gpa'],
					'graduation_year'=>$info['graduation_year'],'major'=>$info['major']);
	$classesQuery ="SELECT * FROM CLASSES WHERE id = '$id'";
	$resultClasses = mysqli_query($db->con, $classesQuery) or die ("Error in selecting " . mysqli_error($db->con));
	$outputClasses = array();
	while($row = mysqli_fetch_assoc($resultClasses)){
		$outputClasses[] = $row;
	}
	$tutorQuery = "SELECT * FROM tutorInfo WHERE id = '$id'";
	$tutorResult = mysqli_query($db->con, $tutorQuery) or die ("Error in select " . mysqli_error($db->con));
	$tutorInfo = mysqli_fetch_assoc($tutorResult);
	$outputTutor = array('description'=>$tutorInfo['description'], 'rating'=>$tutorInfo['rating'], 'price'=>$tutorInfo['price']);

	$output = array('info'=>$outputInfo,'classes'=>$outputClasses,'tutorInfo'=>$outputTutor);
	
	echo json_encode($output);
	*/
?>