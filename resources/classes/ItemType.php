<?php

class ItemType extends ObjectType {
	
	protected $item_type_id, $name_en, $name_de, $consumable;
	
	function __construct() {
		//Name of the table
		$this->TABLE_NAME = "item_type";
	}
	
	//Functions
	
	//get functions
	
	function getID() {
		return($this->item_type_id);
	}
	
	function getName() {
		if(isset($_SESSION['lang']) AND $_SESSION['lang'] == "de") {
			return($this->name_de);
		}
		return($this->name_en);
	}
	
	function getConsumable() {
		return($this->consumable);
	}
	
}