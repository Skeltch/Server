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
	//Create upperbound for number of classes to avoid abuse (20? Example error mesage:
	//"To keep things fair the limit for classes you can tutor in is x")
	//Method to remove rows (Possibly delete all rows and reinsert them)
	$id = intval($_POST['id']);
	$tutorQuery = "SELECT * FROM tutorInfo WHERE id = '$id'";
	$tutorResult = mysqli_query($db->con, $tutorQuery) or die ("Error in select " . mysqli_error($db->con));
	$tutorInfo = mysqli_fetch_assoc($tutorResult);
	if(mysqli_num_rows($tutorResult)>0){
		mysqli_query($db->con, "DELETE FROM tutorInfo WHERE id = '$id'");
	}
	
	//Email and password require more verification to change
	//PREPARE
	$infoQuery = "UPDATE users
					SET gpa = COALESCE(?, gpa),
					graduation_year = COALESCE(?, graduation_year),
					major = COALESCE(?, major)
					WHERE id = '$id'";
	if(!$infoStmt = $db->con->prepare($infoQuery)){
		echo "Prepare failed : (" .$db->con->errno . ")" . $db->con->error;
	}
	//Update instead
	if(!$stmt = $db->con->prepare("UPDATE tutorInfo
									SET description = COALESCE(?, description)
									WHERE id = '$id'")){
		echo "Prepare failed: (" . $db->con->errno . ")" . $db->con->error;
	}
	if(!$tutorStmt = $db->con->prepare("INSERT INTO tutorInfo (id, description) VALUES (?, ?)" /*or
									$stmt1 = "UPDATE USERS  SET
											password
											email
											gpa
											graduation_year
											major*/)){
		echo "Prepare failed: (" .$db->con->errno . ")" . $db->con->error;
	}
	if(isset($_POST['classes'])){
		$classesJSON = json_decode($_POST['classes']);
		//count($classes) is length
		//One approach is to include the entire life of the prepared statements in a loop in this if statement
		//This is costly as it is many insert statements
		for($i=0; $i<count($classesJSON); $i++){
		//$classQuery .= "INSERT INTO classes (id, classes) VALUES (?,?);"
			$id = strval(i);
			$classes = $classesJSON->{strval(i)};
			if(!$classesStmt = $db->con->prepare("INSERT INTO classes (id, classes) VALUES (?,?)")){
				echo "Prepare failed: (" .$db->con->errno . ")" . $db->con->error;
			}
			if(!$classesStmt->bind_param("is",$id,$classes)){
				echo "Binding parameters failed: (" . $classesStmt->errno . ") " . $classesStmt->error;
			}
			if(!$classesStmt->execute()){
				echo "Execute failed: (" .$stmt->errno . ") " . $classesStmt->error;
			}
		}
	}


	//BIND_PARAM
	if(!$tutorStmt->bind_param("is", $id, $description)){
		echo "Binding parameters failed: (" . $tutorStmt->errno . ") " . $tutorStmt->error;
	}
	//Add email and password change support
	if(!$infoStmt->bind_param("dis", $gpa, $gradYear, $major)){
		echo "Binding parameters failed (" . $infoStmt->errno . ") " . $infoStmt->error;
	}
	/*
	if(!$infoStmt->bind_param("ssdis", $password, $email, $gpa, $gradYear, $major)){
		echo "Binding parameters failed: (" . $infoStmt->errno . ") " . $infoStmt->error;
	}
	*/

	//Variable set
	$classes='';
	$description='';
	$password='';
	$email='';
	$gpa='';
	$major='';
	if(isset($_POST['description'])){
		$description = $_POST['description'];
	}	
	/*
	if(isset($_POST['password'])){
		$password=$_POST['password'];
	}
	$email=$_POST['email'];
	*/
	if(isset($_POST['graduation_year'])){
		$gradYear=$_POST['graduation_year'];
	}
	if(isset($_POST['gpa'])){
		$gpa=$_POST['gpa'];
	}
	if(isset($_POST['major'])){
		$major=$_POST['major'];
	}
	
	//EXECUTE
	if(!$tutorStmt->execute()){
		echo "Execute failed: (" .$stmt1->errno . ") " . $tutorStmt->error;
	}
	else if (!$infoStmt->execute()){
		echo "Execute failed: (" .$infoStmt->errno . ") " . $infoStmt->error;
	}
	else{
		echo "success";
		exit;
	}
?>