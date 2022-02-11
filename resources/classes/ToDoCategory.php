<?php
//-----------------New PHP Class File---------------------

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
	//Objective 7.3
	static function getUserCategories($userID) {
		$category = new ToDoCategory;
		$sql = "SELECT ".$category->TABLE_NAME."_id FROM ".$category->TABLE_NAME." WHERE user_id = ? OR user_id IS NULL";
		$sqlType = "i";
		$sqlParams = array($userID);
		
		$categories = pdSelect($sql,"mysqli",$sqlType, $sqlParams);
		
		$categories = $category->mergeResult($categories);
		
		return($categories);
	}
	
	//get all personal categories for specific user
	//Objective 7.3.3
	static function getPersonalCategories($userID) {
		$category = new ToDoCategory;
		$sql = "SELECT ".$category->TABLE_NAME."_id FROM ".$category->TABLE_NAME." WHERE user_id = ?";
		$sqlType = "i";
		$sqlParams = array($userID);
		
		$categories = pdSelect($sql,"mysqli",$sqlType, $sqlParams);
		
		$categories = $category->mergeResult($categories);
		
		return($categories);
	}
	
	//get all global categories
	//Objectives 7.3.2
	static function getGlobalCategories() {
		$category = new ToDoCategory;
		$sql = "SELECT ".$category->TABLE_NAME."_id FROM ".$category->TABLE_NAME." WHERE user_id IS NULL";
		
		$categories = pdSelect($sql,"mysqli");
		
		$categories = $category->mergeResult($categories);
		
		return($categories);
	}
	
	//get a select for types
	
	public static function getSelect($attributes = array(),$option = "0",$placeholder = "") {
		return(parent::getSelect($attributes,$option,$placeholder));
		
	}
	
	//set functions
	
	function setName($value) {
		if(!empty($value)) {
			$this->name = $value;
			return(True);
		}
		return(False);
	}
	
	//check if user is existing
	
	function setUserID($value = "") {
		$user = new User;
		if(!empty($value) AND $user->loadData($value)) {
			$this->user_id = $value;
			return(True);
		} else if(empty($value)) {
			$this->user_id = "";
			return(True);
		}
		return(False);
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
	
	function getGlobalChecked() {
		if(empty($this->getUserID())) {
			return("checked");
		}
		return("");
	}
	
	function getGlobal() {
		if(empty($this->getUserID())) {
			return(True);
		}
		return(False);
	}
	
}?>