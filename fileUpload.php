<?php 
error_reporting(E_ALL);
ini_set('display_errors',1);
if(isset($_FILES['image']['bitmap.bmp'])){
	echo "file";
}
else if(isset($_FILES['image']['bitmap'])){
	echo "bitmap";
}
else if(isset($_POST['bitmap.bmp'])){
	echo "POST";
}
else if(isset($_FILES['image/jpeg']['name'])){
	echo "name";
}
else if(isset($_FILES['bitmap']['name'])){
	//THIS IS SET
	//$target_path = "c:\\";
	//$target_path = $target_path . basename($_FILES['uploaded_file']['name']);
	echo getcwd();
	echo "Error num" . $_FILES['bitmap']['error'];

	//$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
	//echo "File is an image - " . $check["mime"] . ".";

	$target_path = 'uploads/';
	$target_path = $target_path . basename($_FILES["bitmap"]["name"]);
	echo "\n extension " . pathinfo($target_path,PATHINFO_EXTENSION);
	echo "\n type " . $_FILES['bitmap']['type'];
	
	if(file_exists($target_path)){
		echo "exists";
	}
	if($_FILES["bitmap"]['size']>2500000){
		echo "too large";
	}
	$data = file_get_contents($_FILES['bitmap']['tmp_name']);
	echo $data;
	$data = base64_decode($data);
	$image = imagecreatefromstring($data);
	imagepng($image,$target_path);
	/*
	if(move_uploaded_file($_FILES['bitmap']['tmp_name'], $target_path)){
		echo "\nmoved";
	}
	else{
		echo "\nmove failed";
	}
	*/
}
else if(isset($_FILES['file']['name'])){
	echo "file name";
}
else if($_FILES){
	//echo "$_FILES";
}
else{
	//echo "FUCK1";
}

if(isset($_POST['submit'])){
	echo "Error num";
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