<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

ob_start();

include "../../resources/config.php";

if(isset($_POST['requestType']) AND !empty($_POST['requestType'])) {
	$request = $_POST['requestType'];
	
	
	if($session->loggedIn() == True) {
		
		//resend email to confirm email
		
		if($request == "sendConfirmEmail") {
			
			//check which type of email needs to be confirmed
			if(isset($_POST['type']) AND $_POST['type'] == "personalEmail") {
				$emailRequest = new EmailRequest;
				if($emailRequest->createEmailConfirmRequest($session->getSessionUserID(), "email")) {
					echo "success";
				} else {echo "error";}
			} else if(isset($_POST['type']) AND $_POST['type'] == "schoolEmail") {
				$emailRequest = new EmailRequest;
				if($emailRequest->createEmailConfirmRequest($session->getSessionUserID(), "schoolEmail")) {
					echo "success";
				} else {echo "error";}
			} else {
				echo "error";
			}
		}	
	} 
}

ob_flush();
