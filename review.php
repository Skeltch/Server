<?php 
/*
Script used to deal with the review activity
Initially checks to see whether the user has left a review before or not and load the profile picture 
Inserts review into the review table when submitted
Created and debugged by Samuel Cheung
*/
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
	//Inserting has not failed so update the tutor's average rating
	else{
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
	}
}
//This is when the user first enters the activity so we need to retrieve the information, i.e. the tutor's profile picture
//This also does a check to see if the user has left a review before, if they have it will redirect them 
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
		$id = $_POST['tutorID'];
		$reviewerQuery = "SELECT IMAGE.image, USERS.first_name, USERS.last_name from IMAGE, USERS where IMAGE.id = '$id' and USERS.id='$id'";
		$imageString="";
		if(!$imageStmt = $db->con->prepare($reviewerQuery)){
				echo "Prepare failed: (" . $db->con->errno . ")" . $db->con->error;
			}
		$imageStmt->execute();
		$imageStmt->bind_result($imageString, $firstName, $lastName);
		$imageStmt->fetch();
		$imageStmt->close();
		$imageString = base64_encode($imageString);
		return $imageString;
		echo json_encode(array('imageString'=>$imageString, 'first_name'=>$firstName, 'last_name'=>$lastName));
	}
}



?>