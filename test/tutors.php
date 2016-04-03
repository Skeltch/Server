<?php
	require_once __DIR__ . '/database_handler.php';
	$db = new database_handler();
	
	$sql = "SELECT * FROM USERS";
	$result = mysqli_query($db->con, $sql) or die ("Error in selecting " . mysqli_error($db->con));
	
	$array = array();
	while($row = mysqli_fetch_assoc($result)){
		$array[] = $row;
	}

	echo json_encode($array);
?>