<?php
//-----------------New PHP Class File---------------------

class TagAssignment extends SystemClass {
	
	protected $tag_assignment_id, $tag_id, $attribute_type_id, $attribute_id;
	
	function __construct() {
		//Name of the table
		$this->TABLE_NAME = "tag_assignment";
	}
	
	//Functions
	
	//Create new tag for todo list
	function createTagForList($listID, $tagID) {
		$tag = new Tag();
		if($tag->loadData($tagID)) {
			$this->tag_id = $tagID;
			$this->attribute_id = $listID;
			$this->attribute_type_id = 3;
			if($this->createNewData()) {
				return(True);
			}
		}
		return(False);
	}
	
	//Create new tag for event
	function createTagForEvent($eventID, $tagID) {
		$tag = new Tag();
		if($tag->loadData($tagID)) {
			$this->tag_id = $tagID;
			$this->attribute_id = $eventID;
			$this->attribute_type_id = 1;
			if($this->createNewData()) {
				return(True);
			}
		}
		return(False);
	}
	
	//check if tags is used
	static function checkIfTagUsed($tagID) {
		$assignment = new TagAssignment;
		
		$sql = "SELECT tag_id FROM ".$assignment->TABLE_NAME." WHERE tag_id = ? ";
		$sqlType = "i";
		$sqlParams = array($tagID);
		
		$tags = pdSelect($sql,"mysqli",$sqlType,$sqlParams);
		
		$tags = $assignment->mergeResult($tags);
		
		if(count($tags) > 0) {
			return(True);
		}
		return(False);
	}
	
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
	
	//delete all tags for specific attribute type and attribute ID
	static function deleteTagsForAttribute($typeID,$attributeID) {
		$assignment = new TagAssignment;
		
		$sql = "DELETE FROM ".$assignment->TABLE_NAME." WHERE attribute_type_id = ? AND attribute_id = ?";
		$sqlType = "ii";
		$sqlParams = array($typeID,$attributeID);
		
		pdInsert($sql,"mysqli",$sqlType,$sqlParams);
		
		return(True);
	}
	
	//check if attribute has tag
	
	static function checkIfAttributeHasTag($typeID,$attributeID,$tagID) {
		$assignment = new TagAssignment;
		
		$sql = "SELECT tag_id FROM ".$assignment->TABLE_NAME." WHERE attribute_type_id = ? AND attribute_id = ? AND tag_id = ?";
		$sqlType = "iii";
		$sqlParams = array($typeID,$attributeID,$tagID);
		
		$tag = pdSelect($sql,"mysqli",$sqlType,$sqlParams);
		
		if(count($tag) > 0) {
			return(True);
		}
		return(False);
	}
	
	//Load assignment on tagID combined with attribute
	
	function loadDataOnTagAndAttributeID($tagID, $attributeID, $attributeTypeID) {
		//Find tag assignment
		$sql = "SELECT ".$this->TABLE_NAME."_id FROM ".$this->TABLE_NAME." WHERE tag_id = ? AND attribute_id = ? AND attribute_type_id = ?";
		$sqlType = "iii";
		$sqlParams = array($tagID, $attributeID, $attributeTypeID);
		
		$assignments = pdSelect($sql,"mysqli",$sqlType,$sqlParams);
		
		$assignments = $this->mergeResult($assignments);
		
		//Load data of assignment
		if(count($assignments) == 1) {
			return($this->loadData($assignments[0]));
		}
		return(False);
	}
	
	
	
	//get functions
	
	function getID() {
		return($this->tag_assignment_id);
	}
	
}
?>