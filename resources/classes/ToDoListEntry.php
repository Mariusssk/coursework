<?php

class ToDoListEntry extends SystemClass {
	
	protected $todo_list_entry_id, $todo_list_id, $parent_entry_id, $date_due, $name;
	
	function __construct() {
		//Name of the table
		$this->TABLE_NAME = "todo_list_entry";
		
		//Vars which are allowed to be NULL
		$this->NULL_VAR = array("parent_entry_id");
	}
	
	//Functions
	
	//load all entries linked to specific todo list
	public static function loadAllEntriesForList($listID) {
		$entry = new ToDoListEntry;
		//create sql
		$sql = "SELECT ".$entry->TABLE_NAME."_id FROM ".$entry->TABLE_NAME." WHERE todo_list_id = ?";
		$sqlType = "i";
		$sqlParams = array($listID);
		//send to DB
		$entries = pdSelect($sql,"mysqli",$sqlType,$sqlParams);
		
		//clear up result array
		$entries = $entry->mergeResult($entries);
		return($entries);
	}
	
	
	//get all child elemnts of this entry
	
	function getChilds() {
		if(!empty($this->getID())) {
			//create sql
			$sql = "SELECT ".$this->TABLE_NAME."_id FROM ".$this->TABLE_NAME." WHERE parent_entry_id = ?";
			$sqlType = "i";
			$sqlParams = array($this->getID());
			
			//send to DB
			$childs = pdSelect($sql, "mysqli", $sqlType, $sqlParams);
			
			//merge resultsn array
			$childs = $this->mergeResults($childs);
			return($childs);
		} 
		return(array());
	}
	
	//get functions
	
	function getID() {
		return($this->todo_list_entry_id);
	}
	
	function getName() {
		return($this->name);
	}
	
	function getDateDue($type = "display") {
		if($type == "display") {
			$dueDate = new DateTime($this->date_due);
			return($dueDate->format("d.m.Y"));
		}
	}
	
}