<?php 
/*
Script used to deal with the review activity
Initially checks to see whether the user has left a review before or not and load the profile picture 
Inserts review into the review table when submitted
Created and debugged by Samuel Cheung
*/
require_once __DIR__ . '/database_handler.php';
$db = new database_handler();
if(isset($_POST['title']) and isset($_POST['review']) and isset($_POST['rating'])){	
	
	$tutorID = intval($_POST['tutorID']);
	$reviewerID = intval($_POST['reviewerID']);
	//This is because there is no unique key in REVIEW, it is easier to just change the query a little
	if(isset($_POST['edit'])){
			$reviewQuery="UPDATE REVIEW SET tutorID = COALESCE(?, tutorID),
					reviewerID = COALESCE(?,reviewerID),  name = COALESCE(?, name), 
					title = COALESCE(?,title), review = COALESCE(?,review), 
					rating = COALESCE(?,rating), date = CURDATE()
					WHERE tutorID=$tutorID AND reviewerID=$reviewerID";
	}
	else{
		$reviewQuery="INSERT INTO REVIEW (tutorID, reviewerID, name, title, review, rating, date, commends, reports) 
										VALUES (?, ?, ?, ?, ?, ?, CURDATE(), 0, 0)";
	}		
	if(!$stmt = $db->con->prepare($reviewQuery)){
		echo "Prepare failed: (" .$db->con->errno . ")" . $db->con->error;
	}
	if(!$stmt->bind_param("iisssd", $tutorID, $reviewerID, $name, $title, $review, $rating)){
		echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
	}
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
else if (isset($_POST['delete'])){
	$tutorID = $_POST['tutorID'];
	$reviewerID = $_POST['reviewerID'];
	mysqli_query($db->con, "DELETE FROM REVIEW WHERE tutorID=$tutorID AND reviewerID=$reviewerID");
	echo json_encode(array('activity'=>"review"));
}
//Must change commend and report to update in a separate table 
//that contains their ids to prevent multiple commends/reports from the same account
else if (isset($_POST['commend'])){
	$tutorID=$_POST['tutorID'];
	$reviewerID=$_POST['commend'];
	mysqli_query($db->con, "UPDATE REVIEW SET commends=commends+1 WHERE tutorID=$tutorID and reviewerID=$reviewerID");
}
else if (isset($_POST['report'])){
	$tutorID=$_POST['tutorID'];
	$reviewerID=$_POST['report'];
	mysqli_query($db->con, "UPDATE REVIEW SET reports=reports+1 WHERE tutorID=$tutorID and reviewerID=$reviewerID");
}
//This is when the user first enters the activity so we need to retrieve the information, i.e. the tutor's profile picture
//This also does a check to see if the user has left a review before, if they have it will redirect them 
else if (isset($_POST['reviewerID']) and isset($_POST['tutorID'])){
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
	$stmt->bind_result($tutorID, $reviewerID, $name, $title, $review, $rating, $date, $commends, $reports);
	
	$activity="";
	if($stmt->fetch()){
		//echo json_encode(array('activity'=>"edit", 'title'=>$title, 'review'=>$review, 'rating'=>$rating));
		$activity="edit";
	}
	$stmt->close();
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
	echo json_encode(array('activity'=>$activity, 'title'=>$title, 'review'=>$review, 'rating'=>$rating,
		'imageString'=>$imageString, 'first_name'=>$firstName, 'last_name'=>$lastName));
}
?>