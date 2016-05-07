<?php 
/*
Generic function to get profile picture
Created and debugged by Samuel Cheung
*/
function getImage($id){
	require_once __DIR__ . '/database_handler.php';
	$db = new database_handler();
	//$id = $_POST['id'];
	$imageQuery = "SELECT image from IMAGE where id = '$id'";
	$imageString="";
	if(!$imageStmt = $db->con->prepare($imageQuery)){
			echo "Prepare failed: (" . $db->con->errno . ")" . $db->con->error;
		}
		$imageStmt->execute();
		$imageStmt->bind_result($imageString);
		$imageStmt->fetch();
		$imageStmt->close();
		$imageString = base64_encode($imageString);
		return $imageString;
}
?>