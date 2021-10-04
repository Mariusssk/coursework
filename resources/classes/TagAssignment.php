<?php

class TagAssignment extends SystemClass {
	
	protected $tag_assignment_id, $tag_id, $attribute_type_id, $attribute_id;
	
	function __construct() {
		//Name of the table
		$this->TABLE_NAME = "tag_assignment";
	}
	
	//Functions
	
	
	//Find all tags for specific attribute type and attribute ID
	static function loadTagsByAttribute($typeID,$attributeID) {
		$assignment = new TagAssignment;
		
		$sql = "SELECT tag_id FROM ".$assignment->TABLE_NAME." WHERE attribute_type_id = ? AND attribute_id = ?";
		$sqlType = "ii";
		$sqlParams = array($typeID,$attributeID);
		
		$tags = pdSelect($sql,"mysqli",$sqlType,$sqlParams);
		
		$tags = $assignment->mergeResult($tags);
		
		return($tags);
	}
	
	//get functions
	
	function getID() {
		return($this->todo_list_entry_id);
	}
	
}