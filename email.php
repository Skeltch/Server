<?php
/*
Only used to test mailing service
Created and debugged by Samuel Cheung
*/
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);

 $to = "scheung901@gmail.com";
 $subject = "My subject";
 $txt = "Hello world!";
 $headers = "From: RutgersTutorU@gmail.com" . "\r\n" .
 "Reply-To: RutgersTutorU@gmail.com" . "\r\n" .
 "X-Mailer: PHP/" . phpversion();
 print phpinfo();
 if(mail($to,$subject,$txt,$headers)){
         echo "success";
 }
 else{
         echo "failed";
 }
?>
