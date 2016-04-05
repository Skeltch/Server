<?php
	require_once __DIR__ . '/database_handler.php';
	$db = new database_handler();
	
	//Add WHERE rating>=x
	$idResult = mysqli_fetch_assoc(mysqli_query($db->con, "SELECT id FROM tutorInfo WHERE rating>=3 ORDER BY RAND() LIMIT 1"));
	$id = $idResult['id'];
	
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
?>