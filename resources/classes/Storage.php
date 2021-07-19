<?php

class Storage extends SystemClass {
	
	protected $storage_id, $storage_parent_id, $storage_type_id, $name, $size_x, $size_y;
	
	function __construct() {
		//Vars which are allowed to be NULL
		$this->NULL_VAR = array("storage_parent_id","size_x","size_y");
		
		//Name of the table
		$this->TABLE_NAME = "storage";
	}
	
}