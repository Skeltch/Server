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

	if(!$infoStmt->bind_param("dis", $gpa, $gradYear, $major)){
		echo "Binding parameters failed (" . $infoStmt->errno . ") " . $infoStmt->error;
	}
	
	//Variable set
	$classes='';
	$description=null;
	$gpa=null;
	$major=null;
	$price=null;
	if(isset($_POST['description'])){
		echo "error";
		$description = $_POST['description'];
	}	
	else{
		$description=null;
	}
	if(isset($_POST['price'])){
		echo "price";
		$price = $_POST['price'];
	}
	else{
		$price=null;
	}
	if(isset($_POST['graduation_year'])){
		echo "grad";
		$gradYear=$_POST['graduation_year'];
	}
	else{
		$gradYear=null;
	}
	if(isset($_POST['gpa'])){
		echo "gpa";
		$gpa=$_POST['gpa'];
	}
	else{
		$gpa=null;
	}
	if(isset($_POST['major'])){
		$major=$_POST['major'];
	}
	else{
		$major=null;
	}
	if (!$infoStmt->execute()){
		echo "Execute failed: (" .$infoStmt->errno . ") " . $infoStmt->error;
	}
	
	//Tutor
	if(!$tutorStmt = $db->con->prepare("UPDATE tutorInfo
									SET 
									description = COALESCE(?, description),
									price = COALESCE(?, price)
									WHERE id = '$id'")){
		echo "Prepare failed: (" . $db->con->errno . ")" . $db->con->error;
	}	
	//BIND_PARAM
	if(!$tutorStmt->bind_param("ss", $description, $price)){
		echo "Binding parameters failed: (" . $tutorStmt->errno . ") " . $tutorStmt->error;
	}
	//EXECUTE
	if(!$tutorStmt->execute()){
		echo "Execute failed: (" .$stmt1->errno . ") " . $tutorStmt->error;
	}
	else{
		if($gpa=="NULL"){
			mysqli_query($db->con, "UPDATE USERS SET gpa=NULL WHERE id = $id");
		}
		if($gradYear=="NULL"){
			mysqli_query($db->con, "UPDATE USERS SET graduation_year=NULL WHERE id = $id");
		}
		if($price=="NULL"){
			echo "Update Price";
			mysqli_query($db->con, "UPDATE tutorInfo SET price=NULL WHERE id = $id");
		}
	}
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
	echo "success";
	exit;
}
	
?>