<?php
/*
Test file currently not being used
Created and debugged by Samuel Cheung
*/
	require_once __DIR__ . '/database_handler.php';
	$db = new database_handler();

	$id = $_POST['id'];
	$infoQuery = "SELECT a.*, b.*
					FROM users a, tutorInfo b
					WHERE a.id=b.id";
	$infoResult = mysqli_query($db->con, $infoQuery);
	$info = mysqli_fetch_assoc($infoResult);
	$outputInfo = array('username'=>$info['username'],'password'=>$info['password'],'first_name'=>$info['first_name'],
					'last_name'=>$info['last_name'],'email'=>$info['email'],'gpa'=>$info['gpa'],
					'graduation_year'=>$info['graduation_year'],'major'=>$info['major'], 'description'=>$info['description']);
					
	$classesQuery ="SELECT * FROM CLASSES WHERE id = '$id' ORDER BY CLASSES";
	$resultClasses = mysqli_query($db->con, $classesQuery) or die ("Error in selecting " . mysqli_error($db->con));
	$outputClasses = array();
	while($row = mysqli_fetch_assoc($resultClasses)){
		$outputClasses[] = $row;
	}
	$output = array('info'=>$outputInfo, 'classes'=>$outputClasses);
	echo json_encode($output);
	//Change to join
	/*
	$infoResult = mysqli_query($db->con,"SELECT username, password, first_name, last_name, email, gpa,
										graduation_year, major FROM users WHERE id = '$id'") 
										or die ("Error in selecting " . mysqli_error($db->con));
	$info = mysqli_fetch_assoc($infoResult);
														
	$outputInfo = array('username'=>$info['username'],'password'=>$info['password'],'first_name'=>$info['first_name'],
					'last_name'=>$info['last_name'],'email'=>$info['email'],'gpa'=>$info['gpa'],
					'graduation_year'=>$info['graduation_year'],'major'=>$info['major']);
	$classesQuery ="SELECT * FROM CLASSES WHERE id = '$id' ORDER BY CLASSES";
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