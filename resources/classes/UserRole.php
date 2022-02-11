<?php
//-----------------New PHP Class File---------------------

class UserRole extends ObjectType {
	
	//Overall objective 9.2
	
	protected $user_role_id, $name, $pre_defined;
	
	function __construct() {
		//Vars which are allowed to be NULL
		$this->NULL_VAR = array("pre_defined");
		
		//Name of the table
		$this->TABLE_NAME = "user_role";
	}
	
	//Get list of all rights options and values
	
	function getRightOptions () {
		$sql = "SELECT * FROM ".$this->TABLE_NAME." WHERE ".$this->TABLE_NAME."_id = ?";
		$sqlType = "i";
		$sqlParams = array($this->getID());
		
		$options = pdSelect($sql, "mysqli", $sqlType, $sqlParams);
		
		$options = $options[0];
		
		$post = array();
		
		foreach($options as $key => $value) {
			if(!isset($this->$key)) {
				array_push($post, array("name" => $key, "value" => $value));
			}
		}
		
		return($post);
	}
	
	//check if role is curretly used
	
	function checkIfUsed() {
		return(User::checkIfRoleIsUsed($this->getID()));
	}
	
	//check rights
	
	static function checkRights($roleID = 0,$rightsKey = "") {
		$role = new UserRole;
		if(!empty($rightsKey) AND !empty($roleID)){
			$sql = "SELECT ".$rightsKey." FROM ".$role->TABLE_NAME." WHERE user_role_id = ?";
			$sqlType = "i";
			$sqlParams = array($roleID);
			$rights = pdSelect($sql,"mysqli",$sqlType,$sqlParams);
			if(count($rights) == 1 AND isset($rights[0][$rightsKey]) AND $rights[0][$rightsKey] == 1) {
				return(True);
			}
		}
		return(False);
	}
	
	//create a new role
	
	function createNewRole() {
		$this->setName(ROLE_LIST_TEMPLATE_NEW_ROLE_NAME);
		$this->getPreDefined(0);
		
		return($this->createNewData());
	}
	
	//get functions
	
	function getID() {
		return($this->user_role_id);
	}
	
	function getName() {
		return($this->name);
	}
	
	function getPreDefined() {
		return($this->pre_defined);
	}
	
	//set function
	
	function setName($value) {
		if(!empty($value)) {
			$this->name = $value;
			return(True);
		}
		return(False);
	}
	
	function setPreDefined($value) {
		if($value == 0 OR $value == 1) {
			$this->pre_defined = $value;
			return(True);
		}
		return(False);
	}
}
?>