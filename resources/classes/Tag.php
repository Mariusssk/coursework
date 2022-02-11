<?php
//-----------------New PHP Class File---------------------

class Tag extends ObjectType {
	
	protected $tag_id, $name, $colour;
	
	//Overall objective 8
	
	function __construct() {
		//Name of the table
		$this->TABLE_NAME = "tag";
	}
	
	//Functions
	

	//check if tag is currently used
	
	function checkIfUsed() {
		return(TagAssignment::checkIfTagUsed($this->getID()));
	}

	//get functions
	
	function getID() {
		return($this->tag_id);
	}
	
	function getName() {
		return($this->name);
	}
	
	function getColour() {
		return($this->colour);
	}
	
	//Set functions
	
	function setName($value) {
		if(!empty($value)) {
			$this->name = $value;
			return(True);
		}
		return(False);
	}
	
	function setColor($value) {
		if(!empty($value)) {
			$this->colour = $value;
			return(True);
		}
		return(False);
	}
	
}?>