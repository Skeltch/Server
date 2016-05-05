<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);

function $confEmail($email, $encrypted_password){
		$to = $email;
		$subject = "Confirmation Email for TutorU";
		$txt = "This email is just to notify you that this email has been used for registration. If this was not you click on the link provided and we will remove their account from our database.\n
		tutoru.mooo.com/confirm.php?key=$encrypted_password"."\n\nThanks for registering for TutorU!";
		$headers = "From: RutgersTutorU@gmail.com" . "\r\n" .
		"Reply-To: RutgersTutorU@gmail.com" . "\r\n" .
		"X-Mailer: PHP/" . phpversion();
		
		if(mail($to,$subject,$txt,$headers)){
				echo "success";
		}
		else{
				echo "failed";
		}
		exit;
}		
?>
