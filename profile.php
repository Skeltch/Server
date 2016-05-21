<?php
/*
Script used to load own user's profile page
Created and debugged by Samuel Cheung
*/
	require_once __DIR__ . '/database_handler.php';
	$db = new database_handler();

	$id = $_POST['id'];
	$infoQuery = "SELECT * FROM USERS WHERE id = '$id'";
	if(!$infoStmt = $db->con->prepare($infoQuery)){
		echo "Prepare failed: (" . $db->con->errno . ")" . $db->con->error;
	}
	$infoStmt->execute();
	$infoStmt->bind_result($id, $username, $password, $email, $role, $gpa,
						$firstName, $lastName, $gradYear, $dob,
						$major);
	$infoStmt->fetch();
	$infoStmt->close();
	$outputClasses = array();
	$outputInfo = array('username'=>$username, /*'password'=>$password,*/ 'first_name'=>$firstName,
		'last_name'=>$lastName, 'email'=>$email, 'gpa'=>$gpa, 'graduation_year'=>$gradYear,
		'major'=>$major, 'dob'=>$dob);
	if($role!="Tutee"){
		$tutorQuery ="SELECT description, rating, price FROM tutorInfo WHERE id = '$id'";
		if(!$tutorStmt = $db->con->prepare($tutorQuery)){
			echo "Prepare failed: (" . $db->con->errno . ")" . $db->con->error;
		}
		$tutorStmt->execute();
		$tutorStmt->bind_result($description, $rating, $price);
		$tutorStmt->fetch();
		$tutorStmt->close();
		//Overwrite if tutor
		$outputInfo = array('username'=>$username, /*'password'=>$password,*/ 'first_name'=>$firstName,
			'last_name'=>$lastName, 'email'=>$email, 'gpa'=>$gpa, 'graduation_year'=>$gradYear,
			'major'=>$major, 'dob'=>$dob, 'description'=>$description, 'rating'=>$rating, 'price'=>$price);
		
		$classesQuery ="SELECT classes FROM CLASSES WHERE id = ? ORDER BY CLASSES";
		$stmt=$db->con->prepare($classesQuery);
		$stmt->bind_param("i",$id);
		$stmt->execute();
		$stmt->bind_result($classes);
		$outputClasses= array();
		while($stmt->fetch()){
			$outputClasses[] = $classes;
		}
		/*
		$resultClasses = mysqli_query($db->con, $classesQuery) or die ("Error in selecting " . mysqli_error($db->con));
		while($row = mysqli_fetch_assoc($resultClasses)){
			$outputClasses[] = $row;
		}
		*/
	}
	//Creating a table just for images so select * statements are not affected by blob types
	$imageQuery = "SELECT image from IMAGE where id = '$id'";
	$imageString="";
	
	if(!$imageStmt = $db->con->prepare($imageQuery)){
			echo "Prepare failed: (" . $db->con->errno . ")" . $db->con->error;
		}
		$imageStmt->execute();
		$imageStmt->bind_result($imageString);
		$imageStmt->fetch();
		$imageStmt->close();
		$imageString = base64_encode($imageString);
		$output = array('info'=>$outputInfo, 'classes'=>$outputClasses, 'imageString'=>$imageString);
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