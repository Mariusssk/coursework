<?php
//-----------------New PHP Class File---------------------

class EventLocation extends ObjectType {
	
	protected $event_location_id, $name, $description;
	
	function __construct() {
		//Name of the table
		$this->TABLE_NAME = "event_location";
		
		array_push($this->NULL_VAR, "description");
	}
	
	//Functions

	//get a select for types
	
	public static function getSelect($attributes = array(),$option = "0",$placeholder = "") {
		return(parent::getSelect($attributes,$option,$placeholder));
		
	}
	
	//check if event client is used
	//Objective 6.5.3
	
	function checkIfUsed() {
		if(count(Event::selectAllEventsWithLocation($this->getID())) > 0) {
			return(True);
		}
		return(False);
	}
	
	//get functions
	
	function getID() {
		return($this->event_location_id);
	}
	
	function getName() {
		return($this->name);
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
}
?>