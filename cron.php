<?php
/*
Script called by CRON
Created and debugged by Samuel Cheung
*/
require_once __DIR__ .'/database_handler.php';
$db = new database_handler();

//This code is run every 2 hours on the droplet by CRON

//Select the id and time that the user registered
if(!$timeStmt = $db->con->prepare("Select id, time FROM temp")){
        echo "Prepare failed: (" . $db->con->errno . ")" . $db->con->error;
}
if(!$timeStmt->execute()){
        echo "Execute failed: (" . $timeStmt->errno . ") " . $timeStmt->error;
}

$timeStmt->bind_result($id, $time);
//Since 0 is not used its simiply a placeholder
$idQuery = "(0";
while($timeStmt->fetch()){
        $to_time = $time;
		//Sync php to mysql server
        date_default_timezone_set('America'/'New_York');
        $from_time = date("H:i:s");
		//Find if 2 hours have passed
		//We have a second check in case the scheduled event in the server is thrown off sync (server restart)
        $diff= abs($to_time - $from_time);
        if($diff>1){
			//Add to idQuery
			$idQuery .=',';
			$idQuery .= $id;
        }
}
//Finish query
$idQuery.=")";
$timeStmt->close();
$query = "DELETE FROM USERS WHERE id IN ".$idQuery;
mysqli_query($db->con, $query);
mysqli_query($db->con, "DELETE FROM temp where id IN ".$idQuery);
?>
