<?php
	require_once __DIR__ . '/database_handler.php';
	$db = new database_handler();
	
	//Change where id=2 to rating=x
	//Eventually implement a better algorithm
	$info = mysqli_fetch_assoc(mysqli_query($db->con,"SELECT id,username, password, first_name, last_name, email, gpa,
														graduation_year, major
														FROM users WHERE id = 2"));
	$tutorInfo = mysqli_fetch_assoc(mysqli_query($db->con,"SELECT classes, description FROM tutorInfo WHERE id = 2"));
								
	//Some of this is unnecessary and will be taken out in the final product
	$output = array('id'=>$info['id'],
					'first_name'=>$info['first_name'],
					'last_name'=>$info['last_name'],
					'email'=>$info['email'],
					'gpa'=>$info['gpa'],
					'graduation_year'=>$info['graduation_year'],
					'major'=>$info['major'],
					'classes'=>$tutorInfo['classes'],
					'description'=>$tutorInfo['description']);
	echo json_encode($output);
?>