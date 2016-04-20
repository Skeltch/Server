<?php 

if(isset($_POST['title'] and $_POST['review'] and $_POST['rating'])){	
	require_once __DIR__ . '/database_handler.php';
	$db = new database_handler();
	
	if(!$stmt = $db->con->prepare("INSERT INTO REVIEW (tutorID, reviewerID, name, title, review, rating)) 
									VALUES (?, ?, ?, ?, ?, ?)")){
		echo "Prepare failed: (" .$db->con->errno . ")" . $db->con->error;
	}
	if(!$stmt->bind_param("iisssf", $tutorID, $reviewerID, $name, $title, $review, $rating)){
		echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
	}

	$tutorID = $_POST['tutorID'];
	$reviewerID = $_POST['reviewerID'];
	$name = $_POST['name'];
	$title = $_POST['title'];
	$review = $_POST['review'];
	$rating = $_POST['rating'];
	
	if(!$stmt->execute()){
		echo "Execute failed: (" .$stmt->errno . ") " . $stmt->error;
	}
	else{
		//Temporary
		echo "success"
	}
}



?>