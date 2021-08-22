<?php

class User extends SystemClass {
	
	protected $user_id, $role_id, $created, $active, $email, $school_email, $username, $firstname, $lastname, $password, $preferred_language;
	
	
	function __construct() {
		//Vars which are allowed to be NULL
		$this->NULL_VAR = array("school_email","preferred_language");
		
		//Name of the table
		$this->TABLE_NAME = "user";
		
		//Vars which will be ignored when saving to DB
		$this->IGNORE = array("NULL_VAR","TABLE_NAME","IGNORE");
	}
	
	//check if a password would be valid
	
	static function checkPasswordRequirements($password) {
		if(strlen($password) >= 8 OR strlen($password) <= 20 AND preg_match("/[a-zA-Z]+/",$password) AND preg_match("/[0-9]+/",$password) AND preg_match("/[!?@#$%&*]+/",$password)) {
			return(True);
		}
		return(False);
	}
	
	//save function with included email confirmation
	
	function saveData() {
		//load old user data
		$oldUserData = new User;
		$oldUserData->loadData($this->getID());
		$oldEmail = $oldUserData->getEmail();
		$oldSchoolEmail = $oldUserData->getSchoolEmail();
		
		//save data
		if(Parent::saveData() == True) {
			//create mail confirmation request
			$mailFail = 0;
			if($this->getEMail() != $oldEmail) {
				$emailRequest = new EmailRequest;
				if(!$emailRequest->createEmailConfirmRequest($this->getID(),"email")) {
					$mailFail = 1;
				}
			}
			
			if($this->getSchoolEmail() != $oldSchoolEmail) {
				$emailRequest = new EmailRequest;
				if(!$emailRequest->createEmailConfirmRequest($this->getID(),"schoolEmail")) {
					$mailFail = 1;
				}
			}
			
			if($mailFail == 0) {
				return(True);
			}
		}
		return(False);
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
		$session->setPreferredLanguage($this->preferred_language);
	}

	//get functions
	
	function getID() {
		return($this->user_id);
	}
	
	function getDateCreated($format = "") {
		$created = new DateTime($this->created);
		if(!empty($format)) {
			return($created->format($format));
		}
		return($created->format("d.m.Y"));
	}
	
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
	
	function getUsername() {
		return($this->username);
	}
	
	function getEmail() {
		return($this->email);
	}
	
	function getSchoolEmail() {
		return($this->school_email);
	}
	
	function getPreferredLanguage() {
		return($this->preferred_language);
	}
	
	//set functions
	
	function setFirstname($value) {
		if(!empty($value)) {
			$this->firstname = $value;
			return(True);
		}
		return(False);
	}
	
	function setLastname($value) {
		if(!empty($value)) {
			$this->lastname = $value;
			return(True);
		}
		return(False);
	}
	
	function setUsername($value) {
		//check if username is already used
		$sql = "SELECT ".$this->TABLE_NAME."_id FROM ".$this->TABLE_NAME." WHERE ".$this->TABLE_NAME."_id != ? AND username = ?";
		$sqlType = "is";
		$sqlParams = array($this->getID(),$value);
		$users = pdSelect($sql,"mysqli",$sqlType,$sqlParams);
		
		//check if users were found
		if(count($users) > 0) {
			return("alreadyInUse");
		} else if(empty($value)) {
			return(False);
		} else {
			//save if no users found and not empty
			$this->username = $value;
		}
	}
	
	function setEmail($value) {
		//check if email is already used
		$sql = "SELECT ".$this->TABLE_NAME."_id FROM ".$this->TABLE_NAME." WHERE ".$this->TABLE_NAME."_id != ? AND email = ?";
		$sqlType = "is";
		$sqlParams = array($this->getID(),$value);
		$users = pdSelect($sql,"mysqli",$sqlType,$sqlParams);
		
		//check if users were found
		if(count($users) > 0) {
			return("alreadyInUse");
		} else if(empty($value)) {
			return(False);
		} else {
			//save if no users found and not empty
			$this->email = $value;
		}
	}
	
	function setSchoolEmail($value) {
		//check if email is already used
		$sql = "SELECT ".$this->TABLE_NAME."_id FROM ".$this->TABLE_NAME." WHERE ".$this->TABLE_NAME."_id != ? AND school_email = ?";
		$sqlType = "is";
		$sqlParams = array($this->getID(),$value);
		$users = pdSelect($sql,"mysqli",$sqlType,$sqlParams);
		
		//check if users were found
		if(count($users) > 0) {
			return("alreadyInUse");
		} else if(empty($value)) {
			return(False);
		} else {
			//save if no users found and not empty
			$this->school_email = $value;
		}
	}
	
	function setPassword($value) {
		//check password requirements
		if(User::checkPasswordRequirements($value)) {
			//hash password with php standard algorithm
			$this->password = password_hash($value, PASSWORD_DEFAULT);
			return(True);
		}
		return(False);
	}
	
	function setPreferredLanguage($value) {
		$this->preferred_language = $value;
		return(True);
	}
	
	
}