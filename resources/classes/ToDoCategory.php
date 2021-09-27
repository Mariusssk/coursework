<?php

class ToDoCategory extends ObjectType {
	
	protected $todo_list_category_id, $user_id, $name;
	
	function __construct() {
		//Name of the table
		$this->TABLE_NAME = "todo_list_category";
	}
	
	//Functions
	
	//get a list of all storage type IDs
	
	static function getAll($object = "") {
		if(empty($object)) {
			$object = new ToDoCategory;
		}
		return(Parent::getAll($object));
	}
	
	//get a select for types
	
	public static function getSelect($attributes = array(),$option = "0",$placeholder = "") {
		return(parent::getSelect($attributes,$option,$placeholder));
		
	}
	
	
	//get functions
	
	function getID() {
		return($this->todo_list_category_id);
	}
	
	function getName() {
		return($this->name);
	}
	
	function getUserID() {
		return($this->user_id);
	}
	
}