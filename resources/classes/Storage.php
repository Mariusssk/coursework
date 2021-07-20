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
		
		$post .= '<option value="0"> '.STORAGE_PLACEHOLDER_PARENT.' </option>';
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
	
	//komplex function displaying grid of shelf
	function displayGrid() {
		$post = "";
		//check if storage is valid as grid generator
		if($this->getTypeID() == 2 OR ($this->getTypeID() == 3 AND !empty($this->getParentID()))) {
			//set the grid matching shelf
			if($this->getTypeID() == 3) {
				$gridOwner = $this->getParentID();
			} else {
				$gridOwner = $this->getID();
			}
			
			$gridStorage = new Storage();
			
			//load data of shelf
			if($gridStorage->loadData($gridOwner)) {
				$sizeX = $gridStorage->getX();
				$sizeY = $gridStorage->getY();
				
				//check if size is valid
				if($sizeX > 0 AND $sizeY > 0) {
					
					//get all childs which are in grid
					$sql = "SELECT ".$this->TABLE_NAME."_id as ID,size_x as x,size_y as y,name FROM ".$this->TABLE_NAME." WHERE ".$this->TABLE_NAME."_parent_id = ? AND size_x <= ? AND size_y <= ? AND size_x IS NOT NULL AND size_x > 0 AND size_y IS NOT NULL and size_y > 0 ORDER BY size_x ASC,size_y ASC";
					$sqlType = "iii";
					$sqlParams = array($gridStorage->getID(),$sizeX,$sizeY);
					
					$childs = pdSelect($sql,"mysqli",$sqlType,$sqlParams);
					
					//generate grid
					for($x = 1;$x <= $sizeX;$x++) {
						$post .= '<div class="gridRow">';
						for($y = 1;$y <= $sizeY;$y++) {
							$post .= '
							<div class="gridBox" data-grid-x="'.$x.'" data-grid-y="'.$y.'">
							';
							// check if a storage box is assigned to this place
							if(count($childs) > 0 AND $childs[0]['x'] == $x AND $childs[0]['y'] == $y) {
								$post .= '<div class="option" data-storage-id="'.$childs[0]['ID'].'"> '.$childs[0]['name'].' </div>';
								array_splice($childs,0,1);
							}
							$post .= '</div>';
						}
						$post .= '</div>';
					}
				}
			}
		}
		return($post);
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