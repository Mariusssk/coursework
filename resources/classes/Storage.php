<?php

class Storage extends SystemClass {
	
	protected $storage_id, $storage_parent_id, $storage_type_id, $name, $size_x, $size_y;
	
	function __construct() {
		//Vars which are allowed to be NULL
		$this->NULL_VAR = array("storage_parent_id","size_x","size_y");
		
		//Name of the table
		$this->TABLE_NAME = "storage";
	}
	
	//get a select for storages
	
	public static function getSelect($attributes = array(),$option = "0") {
		//get array of all storages
		$storages = Storage::getAll();
		
		//create html select
		$post = "";
		$post .= '<select ';
		//set select element attributes
		foreach($attributes as $key => $tmpAttribute) {
			if($key == "id") {
				$post .= ' id="'.$tmpAttribute.'"';
			}else if($key == "class") {
				$post .= ' class="'.$tmpAttribute.'"';
			}else if($key == "data" AND is_array($tmpAttribute) AND count($tmpAttribute) == 2) {
				$post .= ' data-'.$tmpAttribute[0].'="'.$tmpAttribute[1].'" ';
			}
		}
		
		$post .= '>';
		
		//set placeholder
		
		$post .= '<option value="0"> '.STORAGE_PLACEHOLDER_SELECT.' </option>';
		//set all storages as option
		foreach($storages as $tmpStorage) {
			$storage = new Storage;
			if($storage->loadData($tmpStorage)) {
				if($option == $storage->getID()) {
					$selected = "selected";
				} else {
					$selected = "";
				}
				
				$post .= '<option value="'.$storage->getID().'" '.$selected.'>'.$storage->getName().'</option>';
			}
		}
		
		$post .= '</select>';
		
		//return select as HTML
		
		return($post);
		
	}
	
	//check if storage has childs before deleting
	
	function checkForChilds() {
		//prepare SQL query
		$sql = "SELECT ".$this->TABLE_NAME."_id FROM ".$this->TABLE_NAME." WHERE ".$this->TABLE_NAME."_parent_id = ?";
		$sqlType = "i";
		$sqlParams = array($this->storage_id);
		
		//send SQL query
		$childs = pdSelect($sql,"mysqli",$sqlType,$sqlParams);
		
		
		//check if childs are found
		if(count($childs) == 0) {
			return(False);
		}
		return(True);
	}
	
	
	
	//Functions
	
	static function getAll($object = "") {
		if(empty($object)) {
			$object = new Storage;
		}
		return(Parent::getAll($object));
	}
	
	function getID() {
		return($this->storage_id);
	}
	
	function getName() {
		return($this->name);
	}
	
	function getParentName() {
		$storage = new Storage;
		if(!empty($this->storage_parent_id) AND $storage->loadData($this->storage_parent_id)) {
			return($storage->getName());
		}
		return("");
	}
	
	function getParentID() {
		return($this->storage_parent_id);
	}
	
	function getTypeName() {
		$storageType = new StorageType;
		if(!empty($this->storage_type_id) AND $storageType->loadData($this->storage_type_id)) {
			return($storageType->getName());
		}
		return("");
	}
	
	function getTypeID() {
		return($this->storage_type_id);
	}
	
	function getX() {
		return($this->size_x);
	}
	
	function getY() {
		return($this->size_y);
	}
	
	//set functions
	
	function setName($value) {
		if(!empty($value)) {
			$this->name = $value;
			return(True);
		}
		return(False);
	}
	
	function setX($value) {
		if(is_numeric($value) OR empty($value)) {
			$this->size_x = $value;
			return(True);
		}
		return(False);
	}
	
	function setY($value) {
		if(is_numeric($value) OR empty($value)) {
			$this->size_y = $value;
			return(True);
		}
		return(False);
	}
	
	function setTypeID($value) {
		$storageType = new StorageType;
		if($storageType->loadData($value)) {
			$this->storage_type_id = $value;
			return(True);
		}
		return(False);
	}
	
	function setParentID($value) {
		$storage = new Storage;
		if($storage->loadData($value)) {
			$this->storage_parent_id = $value;
			return(True);
		} else if(empty($value) OR $value == 0) {
			$this->storage_parent_id = "";
			return(True);
		}
		return(False);
	}
	
}