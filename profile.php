<?php
	require_once __DIR__ . '/database_handler.php';
	$db = new database_handler();
	
	$id = $_POST['id'];
	
	$info = mysqli_fetch_assoc(mysqli_query($db->con,"SELECT username, password, first_name, last_name, email, gpa,
														graduation_year, major
														FROM users WHERE id = '$id'"));
	//Method for multiple classes
	//$tutorInfo = mysqli_fetch_assoc(mysqli_query($db->con,"SELECT * FROM tutorInfo WHERE id = '$id'"));
														
	$output1 = array('username'=>$info['username'],'password'=>$info['password'],'first_name'=>$info['first_name'],
					'last_name'=>$info['last_name'],'email'=>$info['email'],'gpa'=>$info['gpa'],
					'graduation_year'=>$info['graduation_year'],'major'=>$info['major']);
	$sql ="SELECT * FROM tutorInfo WHERE id = '$id'";
	$result = mysqli_query($db->con, $sql) or die ("Error in selecting " . mysqli_error($db->con));
	$output2 = array();
	while($row = mysqli_fetch_assoc($result)){
		$output2[] = $row;
	}
	//$output2 = array('classes'=>$tutorInfo['classes'],'description'=>$tutorInfo['description']);

	$output = array('info'=>$output1,'tutorInfo'=>$output2);
	echo json_encode($output);
?>