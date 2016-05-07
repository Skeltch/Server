<?php 
/*
Script used to search for tutors
Created and debugged by Samuel Cheung
*/
//$class = $_POST['class'];
require_once __DIR__ .'/database_handler.php';
$db = new database_handler();

$class = $_GET['class'];
if(isset($_POST['rating'])){
	$rating = $_POST['rating'];
}
if(isset($_POST['price'])){
	$price = $_POST['price'];
}
$searchQuery = "SELECT * FROM CLASSES WHERE classes = ?";
if(!$stmt = mysqli_prepare($db->con, $searchQuery)){
	echo "Prepare failed: (" . $db->con->errno . ")" . $db->con->error;
}
if(!$stmt->bind_param("s", $class)){
	echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
}	
if($stmt->execute()){
	$stmt->bind_result($id, $class);
	$classArray = array();
	$idArray = array();
	$idQuery = "(0";
	while($stmt->fetch()){
		array_push($classArray, $class);
		array_push($idArray, $id);
		$idQuery .=',';
		$idQuery .= $id;
	}
	$idQuery.=")";
	$stmt->close();
	$infoQuery = "SELECT USERS.first_name, USERS.last_name, USERS.gpa, USERS.graduation_year, USERS.major,tutorInfo.description, tutorInfo.rating FROM USERS, tutorInfo WHERE USERS.id in " .$idQuery . " and USERS.id=tutorInfo.id";
	/*
	$infoQuery = "SELECT a.first_name, a.last_name, a.gpa, a.graduation_year, a.major, b.description, b.rating
				FROM USERS a, tutorInfo b
				WHERE a.id IN" .$idQuery . "AND b.id IN" .$idQuery;
	*/			
	if(!$infoStmt = $db->con->prepare($infoQuery)){
		echo "Prepare failed: (" . $db->con->errno . ")" . $db->con->error;
	}
	$infoStmt->execute();
	$infoStmt->bind_result($firstName, $lastName, $gpa,
						$gradYear, $major, $description, $rating);
	$i=0;
	while($infoStmt->fetch()){
		$outputInfo[] = array('id'=>$idArray[$i], 'first_name'=>$firstName, 'last_name'=>$lastName, 'gpa'=>$gpa, 
							'graduation_year'=>$gradYear, 'major'=>$major, 'description'=>$description ,'rating'=>$rating);
		$i++;
	}
	$infoStmt->close();		
	echo json_encode(array('info'=>$outputInfo));
}
?>