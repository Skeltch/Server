<?php 

//Script to log errors send by applications
//This will be useful if we send the app out to other people so we can debug easily
$thread = $_POST['thread'];
$error = $_POST['exception'];
$msg = "Error in thread: " . $thread . " with throwable " . $error;
error_log($msg);

?>