<?php
//-----------------New PHP Class File---------------------

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
	
	//check if event client is used
	//Objective 6.6.4
	
	function checkIfUsed() {
		if(count(Event::selectAllEventsWithClient($this->getID())) > 0) {
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
	
	//set functions
	
	function setName($value) {
		if(!empty($value)) {
			$this->name = $value;
			return(True);
		}
		return(False);
	}
	
	function setDescription($value) {
		$this->description = $value;
		return(True);
	}
	
	//Objective 6.6.2
	
	function setExternal($value) {
		if($value == 0 OR $value == 1) {
			$this->external = $value;
			return(True);
		}
		return(False);
	}
	
}
?>