<?php 
/*
Script called when user confirms their email
Created and debugged by Samuel Cheung
*/
$key = $_GET['key'];
require_once __DIR__ . '/database_handler.php';

$db = new database_handler();
//Remove user from temp so scheduled task does not delete their account
$query = "DELETE FROM temp WHERE `key` = ?";
	if(!$stmt = $db->con->prepare($query)){
		echo "Prepare failed: (" . $db->con->errno . ")" . $db->con->error;
	}
	if(!$stmt->bind_param("s", $key)){
		echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
	}	
	if($stmt->execute()){
		echo "Account successfully confirmed.";
	}
	else{
		echo "Failed";
	}
?>