<?php

class Session {
	
	//Check if user is logged in
	
	protected $current_user;
	
	function __construct() {
		$this->current_user = new User;
		$this->current_user->loadData($this->getSessionUserID());
	}
	
	//check if user is logged in
	
	public function loggedIn() {
		if($this->current_user->loadData($this->getSessionUserID()) == True) {
			return(True);
		}
		return(False);
	}
	
	//return the session user idate
	
	function getSessionUserID() {
		if(isset($_SESSION['user_id']) AND !empty($_SESSION['user_id'])) {
			return($_SESSION['user_id']);
		}
		return(0);
	}
	
		
	//Login
	
	function login($username, $password) {
		if($this->current_user->loadUserByUsername($username)) {
			if($this->current_user->checkPassword($password)) {
				$this->current_user->setSessionData();
				return(True);
			}
		}
		return(False);
	}
	
	//logout
	
	function logout() {
		session_destroy();
		session_start();
	}
	
	//set user ID in session var
	
	function setUserID($userID = 0) {
		$_SESSION['user_id'] = $userID;
	}
	
	function setPreferredLanguage($lang) {
		$_SESSION['lang'] = $lang;
	}
	
		
	//User functions
	
	function getUserFirstname() {
		return($this->current_user->getName(1,0));
	}
	
	function getUserLastname() {
		return($this->current_user->getName(0,1));
	}
	
	function checkRights($key = "") {
		return($this->current_user->checkRights($key));
	}
}