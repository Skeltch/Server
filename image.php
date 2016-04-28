<?php 
/*	
	require_once __DIR__ . '/database_handler.php';
	$db = new database_handler();
	$image=mysqli_query($db->con, "select image from image where id=1");
		while($row = mysqli_fetch_assoc($image)){
			$outputImage = $row;
		}
		$output = json_encode(array('image'=>$outputImage['image']]));
		echo $output;
		*/
	$dirname = "uploads/";
	$images = glob($dirname."*.bmp");
	foreach($images as $image) {
	echo '<img src="'.$image.'" /><br />';
}
?>