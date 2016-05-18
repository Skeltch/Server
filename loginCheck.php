<?php
/*
Script used to login from the mainscreenactivity
Created and debugged by Samuel Cheung
*/
error_reporting(E_ALL);
ini_set('display_errors',1);
//Checking credentials

if(isset($_POST['id'])){
	
	require_once __DIR__ . '/database_handler.php';
	
	$db = new database_handler();
	$id=$_POST['id'];
	$checkQuery = "SELECT username FROM USERS where id = $id";
	
	if(!$checkStmt = $db->con->prepare($checkQuery)){
		echo "Prepare failed: (" . $db->con->errno . ")" . $db->con->error;
	}
	$checkStmt->execute();
	$checkStmt->bind_result($username);
	$checkStmt->fetch();
	$checkStmt->close();
	if($username==""){
		echo json_encode(array('result'=>'Check Failed'));
	}
	else{
		echo json_encode(array('result'=>'Check'));
	}
	//echo $username;
}
?>