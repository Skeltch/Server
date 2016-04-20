<?php 

$thread = $_POST['thread'];
$error = $_POST['exception'];
$msg = "Error in thread: " . $thread . " with throwable " . $error;
error_log($msg);

?>