<?php
//-----------------New PHP Class File---------------------

class Storage extends SystemClass {
	
	protected $storage_id, $storage_parent_id, $storage_type_id, $name, $size_x, $size_y;
	
	function __construct() {
		//Vars which are allowed to be NULL
		$this->NULL_VAR = array("storage_parent_id","size_x","size_y");
		
		//Name of the table
		$this->TABLE_NAME = "storage";
	}
	
	//get a select for storages
	//Select "parent" storage for upper level
	//Objective 5.1
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
	
	//create list with all sub storages
	//Objctive 5.1
	function loadSubStorages() {
		$children = $this->getListOfChildren();
		
		$childrenList = array();
		
		$childrenList = array_merge($childrenList, $children);
		
		if(count($children) > 0) {
			foreach($children as $tmpChildren) {
				$tmpStorage = new Storage;
				if($tmpStorage->loadData($tmpChildren)) {
					if(count($tmpStorage->getListOfChildren()) > 0) {
						$childrenList = array_merge($childrenList , $tmpStorage->loadSubStorages());
					}
				}
			}
		}
		
		return($childrenList);
	}
	
	//create list of all direct children
	//Objective 5.1
	function getListOfChildren() {
		//prepare SQL query
		$sql = "SELECT ".$this->TABLE_NAME."_id FROM ".$this->TABLE_NAME." WHERE ".$this->TABLE_NAME."_parent_id = ?";
		$sqlType = "i";
		$sqlParams = array($this->storage_id);
		
		//send SQL query
		$children = pdSelect($sql,"mysqli",$sqlType,$sqlParams);
		
		$children = $this->mergeResult($children);
		
		return($children);
	}
	
	//check if storage has childs before deleting
	//Objective 5.1
	function checkForChilds() {
		//check if childs are found
		if(count($this->getListOfChildren()) == 0) {
			return(False);
		}
		return(True);
	}
	
	//generate key/url for qr code
	
	function generateQrCodeURL() {
		return(URL."/scan/storage/".$this->getID());
	}
	
	function generateQrCodeKey() {
		return("Storage#".$this->getID());
	}
	
	//Create string of storages for easlier location finding
	//Objective 5.4.2
	
	function createStorageString() {
		$post = "";

		if($this->getTypeID() == 3 OR $this->getTypeID() == 2) {
			$parent = new Storage;
			if($parent->loadData($this->getParentID())) {
				if($this->getTypeID() == 3) {
					$parentL2 = new Storage;
					if($parentL2->loadData($parent->getParentID())) {
						$post .= $parentL2->getName(). " > ";
					}
				}
				$post .= $parent->getName(). " > ";
			}
		}
		
		$post .= $this->getName();
		
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
	
	//check that parent is either existing or not set
	
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
?>