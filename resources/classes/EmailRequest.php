<?php

class EmailRequest extends SystemClass {
	
	protected $email_request_id, $email_request_type_id , $user_id, $code, $request_created, $request_expiry, $verified;
	
	function __construct() {
		//Name of the table
		$this->TABLE_NAME = "email_request";
	}
	
	//Functions
	
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
	
	function createEmailConfirmRequest($user_id) {
		//set basic data
		$this->email_request_type_id = 1;
		$this->user_id = $user_id;
		
		
		//create unique code
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
		
		if($this->createNewRequest()) {
			//Delete all old requests
			$sql = "DELETE FROM ".$this->TABLE_NAME." WHERE email_request_type_id = ? AND user_id = ? AND ".$this->TABLE_NAME."_id != ? AND verified = 0";
			$sqlType = "iii";
			$sqlParams = array($this->getTypeID(),$this->getUserID(),$this->getID());
			pdInsert($sql,"mysqli",$sqlType,$sqlParams);
			
			//create email with confirmation link
			$attributes = array("verify_link" => URL."/verify","code"=>$this->getCode());
			
			$email = new Email;
			if($email->createEmail("confirmEmail",$this->getUserID(),$attributes)) {
				return(True);
			}
		}
		return(False);
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