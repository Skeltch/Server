<?php
/*
Script used for main page to show a featured tutor
Created and debugged by Samuel Cheung
*/
	require_once __DIR__ . '/database_handler.php';
	$db = new database_handler();
	
	$idResult = mysqli_fetch_assoc(mysqli_query($db->con, "SELECT id FROM tutorInfo WHERE rating>=3 ORDER BY RAND() LIMIT 1"));
	$id = $idResult['id'];
	//Retrieve information from user and tutorinfo
	$infoQuery = "SELECT a.first_name, a.last_name, a.gpa, a.graduation_year, a.major, b.description, b.rating, b.price
					FROM USERS a, tutorInfo b
					WHERE a.id='$id' AND b.id='$id'";
					
	if(!$infoStmt = $db->con->prepare($infoQuery)){
		echo "Prepare failed: (" . $db->con->errno . ")" . $db->con->error;
	}
	$infoStmt->execute();
	$infoStmt->bind_result($firstName, $lastName, $gpa,
						$gradYear, $major, $description, $rating, $price);
	$infoStmt->fetch();
	$outputInfo = array('id'=>$id, 'first_name'=>$firstName, 'last_name'=>$lastName, 'gpa'=>$gpa, 
						'graduation_year'=>$gradYear, 'major'=>$major, 'description'=>$description ,'rating'=>$rating, 'price'=>$price);
	$infoStmt->close();		
	
	$classesQuery ="SELECT classes FROM CLASSES WHERE id = ? ORDER BY CLASSES";
	/*
	$resultClasses = mysqli_query($db->con, $classesQuery) or die ("Error in selecting " . mysqli_error($db->con));
	$outputClasses = array();
	while($row = mysqli_fetch_assoc($resultClasses)){
		$outputClasses[] = $row;
	}
	*/
	$stmt=$db->con->prepare($classesQuery);
	$stmt->bind_param("i",$id);
	$stmt->execute();
	$stmt->bind_result($classes);
	$outputClasses= array();
	while($stmt->fetch()){
		$outputClasses[] = $classes;
	}
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