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
	$id = intval($_POST['id']);
	$tutorQuery = "SELECT * FROM tutorInfo WHERE id = '$id'";
	$tutorResult = mysqli_query($db->con, $tutorQuery) or die ("Error in select " . mysqli_error($db->con));
	..$tutorInfo = mysqli_fetch_assoc($tutorResult);
	if(mysqli_num_rows($tutorResult)>0){
		mysqli_query($db->con, "DELETE FROM tutorInfo WHERE id = '$id'");
	}

	if(!$stmt = $db->con->prepare("INSERT INTO tutorInfo (id, description) VALUES (?, ?)" /*or
									$stmt1 = "UPDATE USERS  SET
											password
											email
											gpa
											graduation_year
											major*/)){
		echo "Prepare failed: (" .$db->con->errno . ")" . $db->con->error;
	}
	if(!$stmt->bind_param("is", $id, $description)){
		echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
	}
	/*
	if(!$stmt1->bind_param("ssdis", $password, $email, $gpa, $gradYear, $major)){
		echo "Binding parameters failed: (" . $stmt1->errno . ") " . $stmt1->error;
	}
	*/
	if(!$stmt1 = $db->con->prepare("INSERT INTO classes (id, classes) VALUES (?,?)")){
		echo "Prepare failed: (" .$db->con->errno . ")" . $stmt1->error;
	}
	if(!$stmt1->bind_param("is",$id,$classes)){
		echo "Binding parameters failed: (" . $stmt1->errno . ") " . $stmt1->error;
	}
	
	$classes = $_POST['classes'];
	$description = $_POST['description'];
	
	$password=$_POST['password'];
	$email=$_POST['email'];
	$gpa=$_POST['graduation_year'];
	$major=$_POST['major'];
	
	if(!$stmt->execute()){
		echo "Execute failed: (" .$stmt->errno . ") " . $stmt->error;
	}
	else if(!$stmt1->execute()){
		echo "Execute failed: (" .$stmt1->rrno . ") " . $stmt1->error;
	}

	else{
		echo "success";
		exit;
	}
?>