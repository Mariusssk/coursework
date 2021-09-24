<?php

class EmailRequest extends SystemClass {
	
	protected $email_request_id, $email_request_type_id , $user_id, $code, $request_created, $request_expiry, $verified;
	
	function __construct() {
		//Name of the table
		$this->TABLE_NAME = "email_request";
	}
	
	//Functions
	
	//check if request is open
	
	public static function checkOpenRequest($userID, $requestType = "personalEmail") {
		$request = new EmailRequest;
		$sql = "SELECT ".$request->TABLE_NAME."_id FROM ".$request->TABLE_NAME." WHERE verified = ? AND user_id = ? AND ".$request->TABLE_NAME."_type_id = ?";
		$sqlType = "ii";
		$sqlParams = array(0,$userID);
		
		if($requestType == "password") {
			$sqlType .= "i";
			array_push($sqlParams, 3);
		} else if($requestType == "schoolEmail") {
			$sqlType .= "i";
			array_push($sqlParams, 2);
		} else {
			$sqlType .= "i";
			array_push($sqlParams, 1);
		}
		
		$openRequests = pdSelect($sql,"mysqli", $sqlType, $sqlParams);

		if(count($openRequests) >= 1) {
			return(True);
		}
		return(False);
	
	}
	
	//create new email request
	
	function createNewRequest() {
		$now = new DateTime;
		$this->request_created = $now->format("Y-m-d H-i-s");
		$this->verified = 0;
		
		if(empty($this->request_expiry)) {
			$tomorrow = $now->modify("+1 day");
			$this->request_expiry = $now->format("Y-m-d H-i-s");
		}
		
		if($this->createNewData()) {
			return(True);
		}
		return(False);
	}
	
	//create request for confirming changed email
	
	function createEmailConfirmRequest($user_id,$emailType = "email") {
		//set basic data
		if($emailType == "email") {
			$this->email_request_type_id = 1;
			$emailType = "confirmEmail";
		} else if($emailType == "schoolEmail") {
			$this->email_request_type_id = 2;
			$emailType = "confirmSchoolEmail";
		} else {
			$this->email_request_type_id = 1;
			$emailType = "confirmEmail";
		}
		
		$this->user_id = $user_id;
		
		
		//create unique code
		$this->createCode();
		
		if($this->createNewRequest()) {
			//Delete all old requests
			$this->deleteOpenRequests($this->getUserID(), $this->getTypeID(), $this->getID());
			
			//create email with confirmation link
			$attributes = array("verify_link" => URL."/verify","code"=>$this->getCode());
			
			$email = new Email;
			
			if($email->createEmail($emailType,$this->getUserID(),$attributes)) {
				return(True);
			}
		}
		return(False);
	}
	
	//create request for password reset
	
	function createPasswordResetRequest($user_id) {
		//set basic data
		$this->email_request_type_id = 3;
		$emailType = "resetPassword";
		
		$this->user_id = $user_id;
		
		
		//create unique code
		$this->createCode();
		
		if($this->createNewRequest()) {
			//Delete all old requests
			$this->deleteOpenRequests($this->getUserID(), $this->getTypeID(), $this->getID());
			
			//create email with confirmation link
			$attributes = array("verify_link" => URL."/verify","code"=>$this->getCode());
			
			$email = new Email;
			
			if($email->createEmail($emailType,$this->getUserID(),$attributes)) {
				return($this->getCode());
			}
		}
		return(False);
	}
	
	//create request code 
	function createCode() {
		$codeCreated = False;
		
		while($codeCreated == False) {
			$this->code = strtoupper(bin2hex(random_bytes(20)));
			//check if code is unique
			$sql = "SELECT ".$this->TABLE_NAME."_id FROM ".$this->TABLE_NAME." WHERE code = ?";
			$sqlType = "s";
			$sqlParams = array($this->code);
			$requests = pdSelect($sql,"mysqli",$sqlType,$sqlParams);
			
			//if code is unique stop loop
			if(count($requests) == 0) {
				$codeCreated = True;
			}
		}
		return(True);
	}
	
	//delete all existing requests
	
	function deleteOpenRequests($userID, $typeID, $newRequest = 0) {
		//Delete all old requests
		$sql = "DELETE FROM ".$this->TABLE_NAME." WHERE email_request_type_id = ? AND user_id = ? AND ".$this->TABLE_NAME."_id != ? AND verified = 0";
		$sqlType = "iii";
		$sqlParams = array($typeID,$userID,$newRequest);
		pdInsert($sql,"mysqli",$sqlType,$sqlParams);
		
		return(True);
	}
	
	//find email request ID by code
	
	function loadDataByCode($code) {
		//create SQL statment
		$sql = "SELECT ".$this->TABLE_NAME."_id FROM ".$this->TABLE_NAME." WHERE code = ?";
		$sqlType = "s";
		$sqlParams = array($code);
		
		//query SQL database
		$requests = pdSelect($sql,"mysqli",$sqlType,$sqlParams);
		
		//check if single request was found
		if(count($requests) == 1) {
			if($this->loadData($requests[0][$this->TABLE_NAME."_id"])) {
				return(True);
			}
		}
		return(False);
	}
	
	
	//check if request has exired
	
	function isValid() {
		$now = new DateTime;
		$expiry = new DateTime($this->request_expiry);
		if($now < $expiry AND $this->verified == 0) {
			return(True);
		}
		return(False);
	}
	
	//verify request
	
	function verify() {
		if($this->isValid()) {
			$this->verified = 1;
			if($this->saveData()) {
				return(True);
			}
		}
		return(False);
	}
	
	//get functions
	
	function getID() {
		return($this->email_request_id);
	}
	
	function getTypeID() {
		return($this->email_request_type_id);
	}
	
	function getUserID() {
		return($this->user_id);
	}
	
	function getCode() {
		return($this->code);
	}
	
}