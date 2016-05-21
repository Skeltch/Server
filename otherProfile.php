<?php 

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
		
		//Are prepared statements necessary here as all classes will be strings that we decide
		$classesQuery ="SELECT classes FROM CLASSES WHERE id = '$id' ORDER BY CLASSES";
		
		$resultClasses = mysqli_query($db->con, $classesQuery) or die ("Error in selecting " . mysqli_error($db->con));
		while($row = mysqli_fetch_assoc($resultClasses)){
			$outputClasses[] = $row;
		}
	
	}
	else{
		$descripton="null";
		$rating="null";
		$price="null";
	}
	$outputInfo = array('first_name'=>$firstName, 'last_name'=>$lastName, 'role'=>$role, 'gpa'=>$gpa, 'graduation_year'=>$gradYear,
	'major'=>$major, 'dob'=>$dob, 'description'=>$description, 'rating'=>$rating, 'price'=>$price);
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

?>