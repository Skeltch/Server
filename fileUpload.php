<?php 
/*
Test file not currently used
Created and debugged by Samuel Cheung
*/
error_reporting(E_ALL);
ini_set('display_errors',1);
	require_once __DIR__ . '/database_handler.php';
	$db = new database_handler();
if(!isset($_POST['submit'])){
if(isset($_FILES['bitmap']['name'])){
	//THIS IS SET
	//$target_path = "c:\\";
	//$target_path = $target_path . basename($_FILES['uploaded_file']['name']);
	echo getcwd();
	echo "\nError num" . $_FILES['bitmap']['error'];

	//$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
	//echo "File is an image - " . $check["mime"] . ".";

	$target_path = 'uploads/';
	$target_path = $target_path . basename($_FILES["bitmap"]["name"]);
	echo "\n extension " . pathinfo($target_path,PATHINFO_EXTENSION);
	echo "\n type " . $_FILES['bitmap']['type'];
	
	if(file_exists($target_path)){
		echo "\nexists";
	}
	if($_FILES["bitmap"]['size']>2500000){
		echo "\ntoo large";
	}
	$data = file_get_contents($_FILES['bitmap']['tmp_name']);	
	if(move_uploaded_file($_FILES['bitmap']['tmp_name'], $target_path)){
		echo "\nmoved";
	}
	else{
		echo "\nmove failed";
	}
}
else{
	echo "file not found";
}
}

if(isset($_POST['submit'])){
	echo "Error num\n";
	echo $_FILES['bitmap']['error'];
	$target_path = 'uploads/';
	$target_path = $target_path . basename($_FILES["bitmap"]["name"]);
	
	/*
	$data = file_get_contents($_FILES['bitmap']['tmp_name']);
	$image = imagecreatefromstring($data);
	imagepng($image,$target_path);
	*/
	if(file_exists($target_path)){
		echo "\nexists";
	}
	
		$image = fopen($_FILES['bitmap']['tmp_name'], 'rb');
		/*
		mysqli_query($db->con,"INSERT INTO image(id, image) VALUES (1, '$image')") 
			or die ("Error in selecting " . mysqli_error($db->con));
			*/
	$stmt = $db->con->prepare("Insert into image(id, image) values (?, ?)");
	$id=1;
	$stmt->bind_param(1, $id);
	$stmt->bind_param(2, $image, PDO::PARAM_LOB);
	$db->beginTransaction();
	$stmt->execute();
	$db->commit();
	if(move_uploaded_file($_FILES['bitmap']['tmp_name'], $target_path)){
		echo "\nmoved";
	}
	
	else{
		echo "\nnot moved";
	}
}
//sleep(10);
?>

<html>
<body>

<form action="fileUpload.php" method="post" enctype="multipart/form-data">
    Select image to upload:
    <input type="file" name="bitmap" id="bitmap">
    <input type="submit" value="Upload Image" name="submit">
</form>

</body>
</html>