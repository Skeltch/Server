<?php 
error_reporting(E_ALL);
ini_set('display_errors',1);

	$target_path = 'uploads/';
	$id = $_POST['id'];
	$target_path = $target_path . $id . ".bmp";
	$data = $_POST['image'];
	echo $data;
	$data = base64_decode($data);
	echo "\n" . $data;
	$image = imagecreatefromstring($data);
	imagepng($image,$target_path);
?>
