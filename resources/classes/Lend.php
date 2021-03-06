<?php
//-----------------New PHP Class File---------------------

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
	//Objective 4.5
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
	
	//calculate the total amount lend for an item to change actual amount in storage
	//Objective 4.5.1.3 / 4.5.2
	public static function calculateTotalAmountLend($itemID) {
		$lend = new Lend;
		
		//setup sql
		$sql = "SELECT SUM(amount) FROM ".$lend->TABLE_NAME." WHERE returned = ? AND item_id = ?";
		$sqlType = "ii";
		$sqlParams = array(0,$itemID);
		
		//get amount from SQL
		$amount = pdSelect($sql,"mysqli",$sqlType,$sqlParams);
		
		$amount = $lend->mergeResult($amount)[0];
		
		return($amount);
	}
	

	//load lending data by item and user ID
	//Objective 4.5.1
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
	
	//check overdue
	//Objective 4.5.1.4
	function checkOverdue() {
		if(!empty($this->getReturnDate("form"))) {
			$today = new DateTime();
			$returnDate = new DateTime($this->getReturnDate("form"));
			if($today > $returnDate) {
				return(True);
			}
		}
		return(False);
	}
	
	//days until return date
	//Objective 4.5.1.4
	function getDaysUntilReturn() {
		if(!empty($this->getReturnDate("form"))) {
			$today = new DateTime();
			$difference = $today->diff(new DateTime($this->getReturnDate("form")))->days;
			if($this->checkOverdue()) {
				$difference = $difference * -1;
			} else {
				$difference += 1;
			}
			return($difference);
		}
		return(False);
	}
	
	//Get Functions
	
	function getAmount() {
		return($this->amount);
	}
	
	function getItemID() {
		return($this->item_id);
	}
	
	//Return return date either in format for display or in format to process further
	
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
	
	function getItemName() {
		$item = new Item;
		if($item->loadData($this->getItemID())) {
			return($item->getName());
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
	
	//Put return date into uniform format before saving
	
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
?>