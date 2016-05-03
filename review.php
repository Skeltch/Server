<?php 

if(isset($_POST['title']) and isset($_POST['review']) and isset($_POST['rating'])){	
	require_once __DIR__ . '/database_handler.php';
	$db = new database_handler();
	
	if(!$stmt = $db->con->prepare("INSERT INTO REVIEW (tutorID, reviewerID, name, title, review, rating) 
									VALUES (?, ?, ?, ?, ?, ?)")){
		echo "Prepare failed: (" .$db->con->errno . ")" . $db->con->error;
	}
	if(!$stmt->bind_param("iisssd", $tutorID, $reviewerID, $name, $title, $review, $rating)){
		echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
	}

	$tutorID = intval($_POST['tutorID']);
	$reviewerID = intval($_POST['reviewerID']);
	$name = $_POST['name'];
	$title = $_POST['title'];
	$review = $_POST['review'];
	$rating = floatval($_POST['rating']);
	
	if(!$stmt->execute()){
		echo "Execute failed: (" .$stmt->errno . ") " . $stmt->error;
	}
	else{
		//Temporary
	$reviewQuery = "SELECT rating FROM REVIEW WHERE tutorID='$tutorID'";
	if(!$reviewStmt = $db->con->prepare($reviewQuery)){
		echo "Prepare failed: (" . $db->con->errno . ")" . $db->con->error;
	}
	$size=0;
	$sum=0;
	$reviewStmt->execute();
	$reviewStmt->bind_result($ratings);
	while($reviewStmt->fetch()){
		$sum+=$ratings;
		$size++;
	}
	$average = $sum/$size;
	$reviewStmt->close();
	$query = "UPDATE tutorInfo SET rating=? WHERE id='$tutorID'";
	if(!$stmt = $db->con->prepare($query)){
	echo "Prepare failed: (" .$db->con->errno . ")" . $db->con->error;
	}
	if(!$stmt->bind_param("d",$average)){
		echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
	}
	if(!$stmt->execute()){
		echo "Execute failed: (" .$stmt->errno . ") " . $stmt->error;
	}
	
	echo json_encode(array('activity'=>"review"));
		//Add averaging the ratings and inserting into tutorInfo
	}
}
else if (isset($_POST['reviewerID']) and isset($_POST['tutorID'])){
	require_once __DIR__ . '/database_handler.php';
	$db = new database_handler();
	$review = "SELECT * FROM REVIEW WHERE reviewerID=?";
	if(!$stmt = $db->con->prepare($review)){
		echo "Prepare failed: (" . $db->con->errno . ")" . $db->con->error;
	}
	if(!$stmt->bind_param("s", $reviewerID)){
		echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
	}
	$reviewerID = $_POST['reviewerID'];
	if(!$stmt->execute()){
		echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
	}
	if(mysqli_stmt_get_result($stmt)->num_rows>0){
		echo json_encode(array('activity'=>"redirect"));
	}
	else{
		require_once __DIR__ .'/getImage.php';
		$id = $_POST['tutorID'];
		$imageString = getImage($id);
		echo json_encode(array('imageString'=>$imageString));
	}
}



?>