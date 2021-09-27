<?php

class ToDoList extends SystemClass {
	
	protected $todo_list_id, $user_id, $event_id, $todo_list_category_id , $created, $name;
	
	protected $entries;
	
	function __construct() {
		//Name of the table
		$this->TABLE_NAME = "todo_list";
		
		//Vars which are allowed to be NULL
		$this->NULL_VAR = array("user_id","event_id","todo_list_category_id");
		
		//Vars which should be ignored when loading or saving to DB
		array_push($this->IGNORE, "entries");
	}
	
	//change load data function to include load entries
	
	function loadData($listID) {
		//Run the load data of parent and if succesful try to load entries
		if(Parent::loadData($listID) === True AND $this->loadEntries() === True) {
			return(True);
		} else {
			return(False);
		}
	}
	
	//function load todo list entries
	function loadEntries() {
		$this->entries = array();
		//check if list ID is set
		if(!empty($this->getID())) {
			//Load all entries
			$entries = ToDoListEntry::loadAllEntriesForList($this->getID());
			if(count($entries) > 0) {
				$this->entries = $entries;
			}
			return(True);
		}
		return(False);
	}
	
	//get functions
	
	function getID() {
		return($this->todo_list_id);
	}
	
	function getName() {
		return($this->name);
	}
	
	function getCategoryID() {
		return($this->todo_list_category_id);
	}
	
}