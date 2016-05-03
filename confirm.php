<?php 

$key = $_GET['key'];
require_once __DIR__ . '/database_handler.php';

$db = new database_handler();
$query = "DELETE FROM USERS WHERE encrypted_password = ?";
	if(!$stmt = $db->con->prepare($query)){
		echo "Prepare failed: (" . $db->con->errno . ")" . $db->con->error;
	}
	if(!$stmt->bind_param("s", $key)){
		echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
	}	
	$stmt->execute();
	echo "Account removed successfully.";
?>