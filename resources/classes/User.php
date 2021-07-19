<?php

class User extends SystemClass {
	
	protected $user_id, $role_id, $created, $active, $email, $school_email, $username, $firstname, $lastname, $password;
	
	
	function __construct() {
		//Vars which are allowed to be NULL
		$this->NULL_VAR = array("school_email");
		
		//Name of the table
		$this->TABLE_NAME = "user";
		
		//Vars which will be ignored when saving to DB
		$this->IGNORE = array("NULL_VAR","TABLE_NAME","IGNORE");
	}
	
	
	//Find user by email for login
	
	function loadUserByUsername($username = "") {
		$sql = "SELECT ".$this->TABLE_NAME."_id FROM ".$this->TABLE_NAME." WHERE username = ? OR email = ? OR school_email = ?";
		$sqlType = "sss";
		$sqlParams = array($username,$username,$username);
		
		$users = pdSelect($sql,"mysqli",$sqlType,$sqlParams);
		
		if(count($users) == 1) {
			if($this->loadData($users[0][$this->TABLE_NAME."_id"])) {
				return(True);
			}
		}
		return(False);
	}
	
	//Check user password
	
	function checkPassword($password = "") {
		if(password_verify($password, $this->password)) {
			return(True);
		}
		return(False);
	}
	
	//check user rights based on user role and rights key
	
	function checkRights($key = "") {
		return(UserRole::checkRights($this->role_id,$key));
	}
	
	//set session data
	
	function setSessionData() {
		$session = new Session;
		$session->setUserID($this->{$this->TABLE_NAME."_id"});
	}

	//get functions
	
	function getName($firstname = 1,$lastname = 1) {
		$name = "";
		if($firstname == 1) {
			$name .= $this->firstname;
		}
		
		if($lastname == 1) {
			if(!empty($name)) {
				$name .= " ";
			}
			$name .= $this->lastname;
		}
		return($name);
	}
	
	
}