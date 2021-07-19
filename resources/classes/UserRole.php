<?php

class UserRole extends SystemClass {
	
	protected $user_role_id, $name, $pre_defined;
	
	function __construct() {
		//Vars which are allowed to be NULL
		$this->NULL_VAR = array("pre_defined");
		
		//Name of the table
		$this->TABLE_NAME = "user_role";
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
	
}