<?php

class ToDoCategory extends ObjectType {
	
	protected $todo_list_category_id, $user_id, $name;
	
	function __construct() {
		//Name of the table
		$this->TABLE_NAME = "todo_list_category";
		
		array_push($this->NULL_VAR, "user_id");
	}
	
	//Functions
	
	//get a list of all storage type IDs
	
	static function getAll($object = "") {
		if(empty($object)) {
			$object = new ToDoCategory;
		}
		return(Parent::getAll($object));
	}
	
	//get all categories for specific user
	
	static function getUserCategories($userID) {
		$category = new ToDoCategory;
		$sql = "SELECT ".$category->TABLE_NAME."_id FROM ".$category->TABLE_NAME." WHERE user_id = ? OR user_id IS NULL";
		$sqlType = "i";
		$sqlParams = array($userID);
		
		$categories = pdSelect($sql,"mysqli",$sqlType, $sqlParams);
		
		$categories = $category->mergeResult($categories);
		
		return($categories);
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