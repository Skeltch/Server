<?php 
/*
Script used to load a user's reviews
Created and debugged by Samuel Cheung
*/
	require_once __DIR__ . '/database_handler.php';
	$db = new database_handler();
	$id = $_POST['id'];
	$reviewQuery = "SELECT * FROM REVIEW WHERE tutorID = $id ORDER BY commends DESC, date DESC LIMIT ?, ?";
	if(!$reviewStmt = $db->con->prepare($reviewQuery)){
		echo "Prepare failed: (" . $db->con->errno . ")" . $db->con->error;
	}
	$reviewStmt->bind_param("ii",$lower, $upper);
	$loadAmount=2;
	if(isset($_POST['loadNum'])){
		$lower=$_POST['loadNum'];
		$lower=$lower*$loadAmount;
		$upper=$lower+$loadAmount;
		$load="load";
	}
	else{
		$lower=0;
		$upper=$loadAmount;
	}
	$reviewStmt->execute();
	$reviewStmt->bind_result($tutorID, $reviewerID, $name, $title, $review, $rating, $date, $commends, $reports);
	$reviews = array();
	//Create array of commends from the review page that correspond to the user
	//Maybe give each review a unique key
	//SELECT * FROM reviewExtra WHERE tutorID=$tutorID and id=$id
	while($reviewStmt->fetch()){
		$reviews[] = array('name'=>$name, 'reviewerID'=>$reviewerID, 'title'=>$title, 'review'=>$review, 
			'rating'=>$rating, 'date'=>$date, 'commends'=>$commends, 'reports'=>$reports);
	}
	$reviewStmt->close();
	echo json_encode($reviews);
?>