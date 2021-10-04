<?php

class ToDoListEntry extends SystemClass {
	
	protected $todo_list_entry_id, $todo_list_id, $parent_entry_id, $date_due, $name, $checked;
	
	function __construct() {
		//Name of the table
		$this->TABLE_NAME = "todo_list_entry";
		
		//Vars which are allowed to be NULL
		$this->NULL_VAR = array("parent_entry_id");
	}
	
	//Functions
	
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
	
	function getDateDue($type = "display") {
		if($type == "display") {
			$dueDate = new DateTime($this->date_due);
			return($dueDate->format("d.m.Y"));
		}
	}
	
}