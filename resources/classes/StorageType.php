<?php

class StorageType extends ObjectType {
	
	protected $storage_type_id, $name_en, $name_de;
	
	function __construct() {
		//Name of the table
		$this->TABLE_NAME = "storage_type";
	}
	
	//Functions
	
	//get a list of all storage type IDs
	
	static function getAll($object = "") {
		if(empty($object)) {
			$object = new StorageType;
		}
		return(Parent::getAll($object));
	}
	
	//get a select for types
	
	public static function getSelect($attributes = array(),$option = "0",$placeholder = "") {
		return(parent::getSelect($attributes,$option,$placeholder));
		
	}
	
	
	//get functions
	
	function getID() {
		return($this->storage_type_id);
	}
	
	function getName() {
		if(isset($_SESSION['lang']) AND $_SESSION['lang'] == "de") {
			return($this->name_de);
		}
		return($this->name_en);
	}
	
}