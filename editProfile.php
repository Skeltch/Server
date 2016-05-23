<?php
/*
Script used to update user profile information
Created and debugged by Samuel Cheung
*/
	require_once __DIR__ . '/database_handler.php';
	$db = new database_handler();
	$id = intval($_POST['id']);
	
	//PREPARE
	$infoQuery = "UPDATE USERS
					SET 
					gpa = COALESCE(?, gpa),
					graduation_year = COALESCE(?, graduation_year),
					major = COALESCE(?, major)
					WHERE id = '$id'";
	if(!$infoStmt = $db->con->prepare($infoQuery)){
		echo "Prepare failed : (" .$db->con->errno . ")" . $db->con->error;
	}

	if(!$tutorStmt = $db->con->prepare("UPDATE tutorInfo
									SET 
									description = COALESCE(?, description),
									price = COALESCE(?, price)
									WHERE id = '$id'")){
		echo "Prepare failed: (" . $db->con->errno . ")" . $db->con->error;
	}
	/*
	if(!$tutorStmt = $db->con->prepare("INSERT INTO tutorInfo (id, description) VALUES (?, ?)" )){
		echo "Prepare failed: (" .$db->con->errno . ")" . $db->con->error;
	}
	*/
	if(isset($_POST['classes'])){
		$classesJSON = json_decode($_POST['classes']);
		//One approach is to include the entire life of the prepared statements in a loop in this if statement
		//This is costly as it is many insert statements
		mysqli_query($db->con, "DELETE FROM CLASSES  WHERE id = '$id'") or die ("Error in selecting " . mysqli_error($db->con));
		//Can be changed to inserting many at once ex: VALUES (?,?),(?,?) ...
		for($i=0; $i<count($classesJSON); $i++){
			//$classes = $classesJSON->{strval($i)};
			$classes = $classesJSON[$i];
			//error_log($classes);
			//error_log(count($classesJSON));
			//error_log(json_encode($_POST['classes']));
			if(!$classesStmt = $db->con->prepare("INSERT INTO CLASSES (id, classes) VALUES (?,?)")){
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
	if(!$tutorStmt->bind_param("ss", $description, $price)){
		echo "Binding parameters failed: (" . $tutorStmt->errno . ") " . $tutorStmt->error;
	}
	if(!$infoStmt->bind_param("dis", $gpa, $gradYear, $major)){
		echo "Binding parameters failed (" . $infoStmt->errno . ") " . $infoStmt->error;
	}
	//Variable set
	$classes='';
	//$description='';
	//$gpa='';
	$major='';
	//$price='';
	if(isset($_POST['description'])){
		$description = $_POST['description'];
	}	
	if(isset($_POST['price'])){
		$price = $_POST['price'];
	}
	
	if(isset($_POST['password'])){
		$password=$_POST['password'];
	}

	if(isset($_POST['graduation_year'])){
		$gradYear=$_POST['graduation_year'];
	}
	if(isset($_POST['gpa'])){
		$gpa=$_POST['gpa'];
	}
	if(isset($_POST['major'])){
		$major=$_POST['major'];
	}
	if(isset($_POST['price'])){
		$price=$_POST['price'];
	}
	
	//EXECUTE
	if(!$tutorStmt->execute()){
		echo "Execute failed: (" .$stmt1->errno . ") " . $tutorStmt->error;
	}
	else if (!$infoStmt->execute()){
		echo "Execute failed: (" .$infoStmt->errno . ") " . $infoStmt->error;
	}
	else{
		if($gpa=="NULL"){
			mysqli_query($db->con, "UPDATE USERS SET gpa=NULL WHERE id = $id");
		}
		if($gradYear=="NULL"){
			mysqli_query($db->con, "UPDATE USERS SET graduation_year=NULL WHERE id = $id");
		}
		if($price=="NULL"){
			mysqli_query($db->con, "UPDATE USERS SET price=NULL WHERE id = $id");
		}
		echo "success";
		exit;
	}
	
?>