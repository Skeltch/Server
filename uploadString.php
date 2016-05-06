<?php 
error_reporting(E_ALL);
ini_set('display_errors',1);
require_once __DIR__ . '/database_handler.php';
$db = new database_handler();

//Upload image as a string

$target_path = 'uploads/';
$id = $_POST['id'];
$target_path = $target_path . $id . ".bmp";
$data = $_POST['image'];
$data = base64_decode($data);
$imageQuery = "INSERT INTO IMAGE (id, image) VALUES(?, ?) ON DUPLICATE KEY UPDATE image=?";
if(!$stmt = $db->con->prepare($imageQuery)){
	echo "Prepare failed: (" .$db->con->errno . ")" . $db->con->error;
}
if(!$stmt->bind_param("iss", $id, $data, $data)){
	echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
}
if(!$stmt->execute()){
	echo "Execute failed: (" .$stmt->errno . ") " . $stmt->error;
}
echo "success";

/*
//This is used if we want to store the image on the server and the location to the image in the database 
//This is ideal for scaling however storing largeblobs on a smaller scale is not an issue
$image = imagecreatefromstring($data);
if(imagepng($image,$target_path)){
	echo "success";
}
else{
	echo "failed";
}
*/

?>
