<?php
//-----------------New PHP Class File---------------------

class Session {
	
	//Class dealing with the session, so the login and current user data
	
	protected $current_user;
	
	//Function on construct
	
	function __construct() {
		$this->loadCurrentUserData();
	}
	
	//load data of current user
	
	function loadCurrentUserData() {
		$this->current_user = new User;
		$this->current_user->loadData($this->getSessionUserID());
	}
	
	//check if user is logged in
	//Objective 3.3
	public function loggedIn() {
		if($this->current_user->loadData($this->getSessionUserID()) == True) {
			return(True);
		}
		return(False);
	}
	
	//return the session user data
	
	function getSessionUserID() {
		if(isset($_SESSION['user_id']) AND !empty($_SESSION['user_id'])) {
			return($_SESSION['user_id']);
		}
		return(0);
	}
		
	//Login
	//Objective 3.3
	function login($username, $password) {
		if($this->current_user->loadUserByUsername($username)) {
			if($this->current_user->checkPassword($password)) {
				$this->current_user->setSessionData();
				return(True);
			}
		}
		return(False);
	}
	
	//Count notifications
	
	function getNotificationCount() {
		$count = 0;
		$systemNotifications = new SystemNotifications($this->getSessionUserID());
		$count += Notification::countUnreadNotifications($this->getSessionUserID());
		$count += $systemNotifications->countNotifications();
		return($count);
	}
	
	//logout
	//Objective 3.3
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
?>