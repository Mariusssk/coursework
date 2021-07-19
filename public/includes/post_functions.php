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
	
}

ob_flush();
