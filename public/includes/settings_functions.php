<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

ob_start();

include "../../resources/config.php";

if(isset($_POST['requestType']) AND !empty($_POST['requestType'])) {
	$request = $_POST['requestType'];
	
	
	if($session->loggedIn() == True) {
		//save user personal data
		if($request == "saveUserPersonalData") {
			//check if data was transmitted correctly
			if(isset($_POST['personalData']) AND count($_POST['personalData']) >= 4) {
				$personalData = $_POST['personalData'];
				
				//set up instance of user class
				$user = new User;
				if($user->loadData($session->getSessionUserID())) {
					
					$emptyField = 0;
					
					if(!isset($personalData['firstname']) OR $user->setFirstname($personalData['firstname']) == False) {
						$emptyField = 1;
					} 
					
					if(!isset($personalData['lastname']) OR $user->setLastname($personalData['lastname']) == False) {
						$emptyField = 1;
					}
					
					if(isset($personalData['username']) AND !empty($personalData['username'])) {
						if($user->setUsername($personalData['username']) == "alreadyInUse") {
							echo "usernameAlreadyUsed";
							$emptyField = 2;
						} else {
							if(isset($personalData['email']) AND !empty($personalData['email'])) {
								if($user->setEmail($personalData['email']) == "alreadyInUse") {
									echo "emailAlreadyUsed";
									$emptyField = 2;
								} else {
									if(isset($personalData['schoolEmail'])) {
										if($user->setSchoolEmail($personalData['schoolEmail']) == "alreadyInUse") {
											echo "schoolEmailAlreadyUsed";
											$emptyField = 2;
										}
									}
								}
							} else {
								$emptyField = 1;
							}
						}
					} else {
						$emptyField = 1;
					}
					
					
					
					
					
					if($emptyField == 1) {
						echo "dataMissing";
					} else if($emptyField == 0){
						if($user->saveData()) {
							echo "success";
						}
						
					}
				}
			}
		}
	}
		
	//verify code in email
	
	if($request == "verifyCode") {
		//check if data was transmitted correctly
		if(isset($_POST['code']) AND !empty($_POST['code'])) {
			$emailRequest = new EmailRequest;
			//try to find email request by code
			if($emailRequest->loadDataByCode($_POST['code']) AND $emailRequest->isValid()) {
				//return response based on request type
				if($emailRequest->getTypeID() == 1 OR $emailRequest->getTypeID() == 2) {
					if($emailRequest->verify()) {
						echo "emailVerified";
					}
				} else if($emailRequest->getTypeID() == 3) {
					echo "passwordReset";
				}
			} else {
				echo "codeNotFound";
			}
		}
	}
}

ob_flush();
ob_end_clean();
