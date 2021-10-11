<?php

class Tag extends ObjectType {
	
	protected $tag_id, $name, $colour;
	
	function __construct() {
		//Name of the table
		$this->TABLE_NAME = "tag";
	}
	
	//Functions
	


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
	
}