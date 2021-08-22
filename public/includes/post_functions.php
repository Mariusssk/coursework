<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

ob_start();

include "../../resources/config.php";

if(isset($_POST['requestType']) AND !empty($_POST['requestType'])) {
	$request = $_POST['requestType'];
	
	//check status of user login
	
	if($request == "checkUserLoginStatus") {
		$session = new Session;
		if($session->loggedIn() == True) {
			echo "true";
		} else {
			echo "false";
		}
	}
	
	//login user
	
	else if($request == "login") {
		if(isset($_POST['username']) AND !empty($_POST['username']) AND isset($_POST['userPassword']) AND !empty($_POST['userPassword'])) {
			if($session->login($_POST['username'],$_POST['userPassword'])) {
				echo "success";
			} else {
				echo "loginWrong";
			}
		}
	}
	
	//logout user
	
	else if($request == "logout") {
		$session->logout();
	}
	
	//reset password of user
	
	else if($request == "resetPassword") {
		if(isset($_POST['passwordA']) AND !empty($_POST['passwordA']) AND isset($_POST['passwordB']) AND !empty($_POST['passwordB'])) {
			if($_POST['passwordA'] == $_POST['passwordB']) {
				if(User::checkPasswordRequirements($_POST['passwordA'])) {
					$emailRequest = new EmailRequest;
					if(isset($_POST['code']) AND $emailRequest->loadDataByCode($_POST['code']) AND $emailRequest->isValid()) {
						$user = new User;
						if($user->loadData($emailRequest->getUserID()) AND $user->setPassword($_POST['passwordA']) AND $user->saveData())  {
							echo "success";
							$emailRequest->verify();
						}
					}
				} else {
					echo "requirementsFailed";
				}
			}
		}
	}
	
	//set session lang
	
	else if($request == "setLanguage" AND isset($_POST['lang']) AND !empty($_POST['lang'])) {
		if(strlen($_POST['lang']) == 2) {
			$session->setPreferredLanguage($_POST['lang']);
		}
	}
	
}

ob_flush();
