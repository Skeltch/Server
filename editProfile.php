<?php
	require_once __DIR__ . '/database_handler.php';
	$db = new database_handler();
	
	//Inserting into original user table
	/*
	while($tutorInfo = mysqli_fetch_assoc(mysqli_query($db->con,"SELECT classes, description FROM tutorInfo WHERE id = '$id'"))){
			$row[]=$tutorInfo;
	}
	foreach($rows as $arrays){
		if($arrays['classes']!=$_POST['classes']){
			$insert[]=$arrays['classes'];
		}
	}
	*/
	//Create upperbound for number of classes to avoid abuse (10? Example error mesage:
	//"To keep things fair the limit for classes you can tutor in is x")
	//Method to remove rows (Possibly delete all rows and reinsert them)
	if(!$stmt = $db->con->prepare("INSERT INTO tutorInfo (id, classes, description) VALUES (?, ?, ?)" /*or
									$stmt1 = "UPDATE USERS  SET
											password
											email
											gpa
											graduation_year
											major*/)){
		echo "Prepare failed: (" .$db->con->errno . ")" . $db->con->error;
	}
	if(!$stmt->bind_param("iss", $id, $classes, $description)){
		echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
	}
	/*
	if(!$stmt1->bind_param("ssdis", $password, $email, $gpa, $gradYear, $major)){
		echo "Binding parameters failed: (" . $stmt1->errno . ") " . $stmt1->error;
	}
	*/
	$id = intval($_POST['id']);
	$classes = $_POST['classes'];
	$description = $_POST['description'];
	
	$password=$_POST['password'];
	$email=$_POST['email'];
	$gpa=$_POST['graduation_year'];
	$major=$_POST['major'];
	
	if(!$stmt->execute()){
		echo "Execute failed: (" .$stmt->errno . ") " . $stmt->error;
	}
	/*
	else if(!$stmt1->execute){
		echo "Execute failed: (" . $stmt1->errno .") " . $stmt1->error;
	}
	*/
	else{
		echo "success";
		exit;
	}
?>