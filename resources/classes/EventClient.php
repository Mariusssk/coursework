<?php

class EventClient extends ObjectType {
	
	protected $event_client_id, $name, $description, $external;
	
	function __construct() {
		//Name of the table
		$this->TABLE_NAME = "event_client";
		
		array_push($this->NULL_VAR, "description");
	}
	
	//Functions

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

	
	
	//get functions
	
	function getID() {
		return($this->event_client_id);
	}
	
	function getName() {
		return($this->name);
	}

	function getExternal() {
		return($this->external);
	}
	
	function getDescription() {
		return($this->description);
	}
	
}