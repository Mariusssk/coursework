<?php

class Lend extends ObjectType {
	
	protected $lend_id, $user_id, $item_id, $amount, $return_date, $returned;
	
	function __construct() {
		//Name of the table
		$this->TABLE_NAME = "lend";
		
		//Vars which are allowed to be NULL
		$this->NULL_VAR = array("return_date");
	}
	
	//Functions
	
	public static function getItemsLendByUser($userID) {
		$lend = new Lend;
		$sql = "SELECT item_id FROM ".$lend->TABLE_NAME." WHERE returned = ? user_id = ?";
		$sqlType = "ii";
		$sqlParams = array(0,$userID);
		
		$items = pdSelect($sql,"mysqli",$sqlType,$sqlParams);
	}
	
	
}