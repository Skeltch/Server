<?php 
/*
Script used to load a user's reviews
Created and debugged by Samuel Cheung
*/
	require_once __DIR__ . '/database_handler.php';
	$db = new database_handler();
	$id = $_POST['id'];
	$reviewQuery = "SELECT * FROM REVIEW WHERE tutorID = $id ORDER BY date DESC";
	if(!$reviewStmt = $db->con->prepare($reviewQuery)){
		echo "Prepare failed: (" . $db->con->errno . ")" . $db->con->error;
	}
	$reviewStmt->execute();
	$reviewStmt->bind_result($tutorID, $reviewerID, $name, $title, $review, $rating, $date);
	$reviews = array();
	while($reviewStmt->fetch()){
		$reviews[] = array('name'=>$name, 'reviewerID'=>$reviewerID 'title'=>$title, 'review'=>$review, 'rating'=>$rating, 'date'=>$date);
	}
	$reviewStmt->close();
	echo json_encode($reviews);
?>