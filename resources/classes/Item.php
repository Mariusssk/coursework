<?php

class Item extends SystemClass {
	
	protected $item_id, $item_type_id, $storage_id, $name, $lenght, $description, $amount;
	
	function __construct() {
		//Vars which are allowed to be NULL
		$this->NULL_VAR = array("storage_id","lenght","description");
		
		//Name of the table
		$this->TABLE_NAME = "item";
	}
	
	
	//Functions
	
	//get all items
	
	static function getAll($object = "") {
		if(empty($object)) {
			$object = new Item;
		}
		return(Parent::getAll($object));
	}
	
	//get actual amount of items in storage
	
	function getActualAmount() {
		return($this->getAmount() - Lend::calculateTotalAmountLend($this->getID()));
	}
	
	//generate key/url for qr code
	
	function generateQrCodeURL() {
		return(URL."/scan/item/".$this->getID());
	}
	
	function generateQrCodeKey() {
		return("Item#".$this->getID());
	}
	
	
	//get Functions
	
	function getID() {
		return($this->item_id);
	}
	
	function getName() {
		return($this->name);
	}
	
	function getAmount() {
		return($this->amount);
	}
	
	function getLength() {
		return($this->lenght);
	}
		
	function getTypeID() {
		return($this->item_type_id);
	}
	
	function getStorageID() {
		return($this->storage_id);
	}
	
	function getTypeName() {
		$itemType = new ItemType;
		if($itemType->loadData($this->getTypeID())) {
			return($itemType->getName());
		}
		return("");
	}
	
	function getStorageName() {
		$storage = new Storage;
		if($storage->loadData($this->getStorageID())) {
			return($storage->getName());
		}
		return("");
	}
	
	function getStorageString() {
		$storage = new Storage;
		if($storage->loadData($this->getStorageID())) {
			return($storage->createStorageString());
		}
		return("");
	}

	function getConsumeable() {
		$itemType = new ItemType;
		if($itemType->loadData($this->getTypeID())) {
			return($itemType->getConsumable());
		}
		return(0);
	}
	
	function getDescription() {
		return($this->description);
	}
	
	//set Functions
	
	function setName($value) {
		if(!empty($value)) {
			$this->name = $value;
			return(True);
		}
		return(False);
	}
	
	function setLength($value) {
		if(is_numeric($value) or empty($value)) {
			$this->lenght = $value;
			return(True);
		}
		return(False);
	}
	
	function setAmount($value) {
		if(is_numeric($value) AND !empty($value)) {
			$this->amount = $value;
			return(True);
		} else if(empty($value)) {
			$this->amount = "0";
			return(True);
		}
		return(False);
	}
	
	function setDescription($value) {
		$this->description = $value;
		return(True);
	}
	
	function setTypeID($value) {
		$itemType = new itemType;
		if($itemType->loadData($value)) {
			$this->item_type_id = $value;
			return(True);
		}
		return(False);
	}
	
	function setStorageID($value) {
		$storage = new Storage;
		if($storage->loadData($value)) {
			$this->storage_id = $value;
			return(True);
		} else if(empty($value) OR $value == 0) {
			$this->storage_id = "";
			return(True);
		}
		return(False);
	}
	
	
}