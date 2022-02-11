<?php
//-----------------New PHP Class File---------------------

class ToDoListEntry extends SystemClass {
	
	protected $todo_list_entry_id, $todo_list_id, $parent_entry_id, $name, $checked;
	
	//Overall Objective 7.2
	
	function __construct() {
		//Name of the table
		$this->TABLE_NAME = "todo_list_entry";
		
		//Vars which are allowed to be NULL
		$this->NULL_VAR = array("parent_entry_id");
	}
	
	//Functions
	
	//overriden create new data to acomplish for checked 0

	function createNewData() {
		if(empty($this->checked)) {
			$this->checked = 0;
		}
		
		return(parent::createNewData());
	}
	
	//load all entries linked to specific todo list
	public static function loadEntriesArray($listID, $displayType = "internal") {
		$entry = new ToDoListEntry;
		//create sql
		$sql = "SELECT ".$entry->TABLE_NAME."_id FROM ".$entry->TABLE_NAME." WHERE todo_list_id = ? AND parent_entry_id IS NULL";
		$sqlType = "i";
		$sqlParams = array($listID);
		//send to DB
		$entries = pdSelect($sql,"mysqli",$sqlType,$sqlParams);
		
		//clear up result array
		$entries = $entry->mergeResult($entries);
		
		$entryArray = array();
		
		foreach($entries as $tmpEntry) {
			array_push($entryArray, ToDoListEntry::generateArrayChilds($tmpEntry, $displayType));
		}
		
		return($entryArray);
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
			$childs = $this->mergeResult($childs);
			return($childs);
		} 
		return(array());
	}
	
	//delete all entries for todo list
	
	static function deleteAllEntriesForList($listID) {
		$entry = new ToDoListEntry;
		$sql = "DELETE FROM ".$entry->TABLE_NAME." WHERE todo_list_id = ?";
		$sqlType = "i";
		$sqlParams = array($listID);
		
		pdInsert($sql, "mysqli", $sqlType, $sqlParams);
	}
	
	
	//Generate recoursive array for children of entries
	static function generateArrayChilds($startNode, $displayType) {
		//Load data of current entry
		$entry = new ToDoListEntry;
		if($entry->loadData($startNode)) {
			//Find all children of array
			$childs = $entry->getChilds();
			if(count($childs) > 0) {
				$children = array();
				foreach($childs as $tmpChild) {
					$childEntry = new ToDoListEntry;
					if($childEntry->loadData($tmpChild)) {
						//Call array function again on children
						array_push($children, ToDoListEntry::generateArrayChilds($childEntry->getID(),$displayType));
					}
				}
				//Add children into array
				if($displayType == "external") {
					return(array("currentID" => $entry->getID(), "name" => $entry->getName(), "checked" => $entry->getChecked(),  "children" => $children));
				} else {
					return(array("currentID" => $entry->getID(), "children" => $children));
				}
			} else {
				//return only currennt ID if no children are found
				
				if($displayType == "external") {
					return(array("currentID" => $entry->getID(),"name" => $entry->getName(), "checked" => $entry->getChecked()));
				} else {
					return(array("currentID" => $entry->getID()));
				}
			}
		}
	}
	
	//get functions
	
	function getID() {
		return($this->todo_list_entry_id);
	}
	
	function getName() {
		return($this->name);
	}
	
	function getChecked() {
		return($this->checked);
	}
	
	function getListID() {
		return($this->todo_list_id);
	}
	
	//set function
	
	function setName($value) {
		if(!empty($value)){
			$this->name = $value;
			return(True);
		}
		return(False);
	}
	
	function setListID($value) {
		$list = new ToDoList;
		if($list->loadData($value)) {
			$this->todo_list_id = $value;
			return(True);
		}
		return(False);
	}
	
	function setParentID($value) {
		$entry = new ToDoListEntry;
		if($entry->loadData($value)) {
			$this->parent_entry_id = $value;
			return(True);
		} else if($value == 0) {
			return(True);
		}
		return(False);
	}
	
	//Objective 7.2.1
	
	function setChecked($value) {
		if($value == 0 OR $value == 1) {
			$this->checked = $value;
			return(True);
		}
		return(False);
	}
	
}?>