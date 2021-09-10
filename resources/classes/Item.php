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
	
	static function getAll($object = "") {
		if(empty($object)) {
			$object = new Item;
		}
		return(Parent::getAll($object));
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
		
	function getTypeID() {
		return($this->item_type_id);
	}
	
	function getTypeName() {
		$itemType = new ItemType;
		if($itemType->loadData($this->getTypeID())) {
			return($itemType->getName());
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
	
	
}