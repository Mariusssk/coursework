<?php

class Lend extends SystemClass {
	
	protected $lend_id, $user_id, $item_id, $amount, $return_date, $returned;
	
	function __construct() {
		//Name of the table
		$this->TABLE_NAME = "lend";
		
		//Vars which are allowed to be NULL
		$this->NULL_VAR = array("return_date");
	}
	
	//Functions
	
	//Load all items lend by user
	public static function getItemsLendByUser($userID) {
		$lend = new Lend;
		
		//setup SQL
		$sql = "SELECT item_id FROM ".$lend->TABLE_NAME." WHERE returned = ? AND user_id = ?";
		$sqlType = "ii";
		$sqlParams = array(0,$userID);
		
		//load items
		$items = pdSelect($sql,"mysqli",$sqlType,$sqlParams);
		
		$items = $lend->mergeResult($items);
		
		return($items);
	}
	
	//calculate the amount a user has lend of one item
	public static function calculateAmountLend($userID, $itemID) {
		$lend = new Lend;
		
		//setup sql
		$sql = "SELECT SUM(amount) FROM ".$lend->TABLE_NAME." WHERE returned = ? AND user_id = ? AND item_id = ?";
		$sqlType = "iii";
		$sqlParams = array(0,$userID,$itemID);
		
		//get amount from SQL
		$amount = pdSelect($sql,"mysqli",$sqlType,$sqlParams);
		
		$amount = $lend->mergeResult($amount)[0];
		
		return($amount);
	}
	

	//load lending data by item and user ID
	function loadDataByItemID($userID, $itemID) {
		$sql = "SELECT ".$this->TABLE_NAME."_id FROM ".$this->TABLE_NAME." WHERE user_id = ? AND item_id = ?";
		$sqlType = "ii";
		$sqlParams = array($userID, $itemID);
		
		$lend = pdSelect($sql,"mysqli",$sqlType,$sqlParams);
		
		$lend = $this->mergeResult($lend);
		
		return($this->loadData($lend[0]));
	}
	
	//Get Functions
	
	function getAmount() {
		return($this->amount);
	}
	
	//set
	
	function setUserID($value) {
		$user = new User;
		if($user->loadData($value)) {
			$this->user_id = $value;
			return(True);
		}
		return(False);
	}
	
	function setItemID($value) {
		$item = new Item;
		if($item->loadData($value)) {
			$this->item_id = $value;
			return(True);
		}
		return(False);
	}
	
	function setAmount($value) {
		if(is_numeric($value)) {
			$this->amount = $value;
			return(True);
		}
		return(False);
	}
	
	function setReturnDate($value) {
		if(DateTime::createFromFormat('Y-m-d H:i:s', $value) == True) {
			$this->return_date = $value;
			return(True);
		}
		return(False);
	}
	
	
}