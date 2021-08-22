<?php

class Storage extends SystemClass {
	
	protected $item_id, $item_type_id, $storage_id, $name, $lenght, $description, $amount;
	
	function __construct() {
		//Vars which are allowed to be NULL
		$this->NULL_VAR = array("storage_id","lenght");
		
		//Name of the table
		$this->TABLE_NAME = "storage";
	}
	
	
	//Functions
	
	
}