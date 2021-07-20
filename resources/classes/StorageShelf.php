<?php

class StorageShelf extends Storage {
	
	//get childs without position 
	
	function getChildsWithoutPosition() {
		$childs = array();
		
		$sql = "SELECT ".$this->TABLE_NAME."_id FROM ".$this->TABLE_NAME." WHERE ".$this->TABLE_NAME."_parent_id = ? AND (size_x is NULL OR size_y is NULL OR size_x > ? OR size_y > ? OR size_x <= 0 OR size_y <= 0)"; 
		$sqlType = "iii";
		$sqlParams = array($this->getID(),$this->getX(),$this->getY());
		
		$childs = pdSelect($sql,"mysqli",$sqlType,$sqlParams);
		
		return($this->mergeResult($childs));
	}

}

