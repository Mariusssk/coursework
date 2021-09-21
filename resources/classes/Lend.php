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
	
	function createNewData() {
		if(empty($this->returned)) {
			$this->returned = 0;
		}
		return(Parent::createNewData());
	}
	
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
	
	//calculate the total amount lend for an item
	public static function calculateTotalAmountLend($itemID) {
		$lend = new Lend;
		
		//setup sql
		$sql = "SELECT SUM(amount) FROM ".$lend->TABLE_NAME." WHERE returned = ? AND item_id = ?";
		$sqlType = "iii";
		$sqlParams = array(0,$itemID);
		
		//get amount from SQL
		$amount = pdSelect($sql,"mysqli",$sqlType,$sqlParams);
		
		$amount = $lend->mergeResult($amount)[0];
		
		return($amount);
	}
	

	//load lending data by item and user ID
	function loadDataByItemID($userID, $itemID) {
		$sql = "SELECT ".$this->TABLE_NAME."_id FROM ".$this->TABLE_NAME." WHERE user_id = ? AND item_id = ? AND returned = ?";
		$sqlType = "iii";
		$sqlParams = array($userID, $itemID, 0);
		
		$lend = pdSelect($sql,"mysqli",$sqlType,$sqlParams);
		
		$lend = $this->mergeResult($lend);
		
		if(count($lend) > 0) {
			return($this->loadData($lend[0]));
		} else {
			return(False);
		}
	}
	
	//Get Functions
	
	function getAmount() {
		return($this->amount);
	}
	
	function getReturnDate($type = "display") {
		if(!empty($this->return_date)) {
			$returnDate = new DateTime($this->return_date);
			if($type == "form") {
				return($returnDate->format("Y-m-d"));
			} else {
				return($returnDate->format("d.m.Y"));
			}
		}
		return("");
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
		if(empty($value)) {
			$this->return_date = "";
			return(True);
		} else if(DateTime::createFromFormat('Y-m-d', $value) == True) {
			$this->return_date = $value;
			return(True);
		}
		return(False);
	}
	
	function setReturned($value) {
		if($value == 0 OR $value == 1) {
			$this->returned = $value;
			return(True);
		}
		return(False);
	}
}