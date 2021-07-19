<?php

class StorageType extends SystemClass {
	
	protected $storage_type_id, $name_en, $name_de;
	
	function __construct() {
		//Name of the table
		$this->TABLE_NAME = "storage_type";
	}
	
	//Functions
	
	//get a list of all storage type IDs
	
	static function getAll() {
		$type = new StorageType;
		//Create SQL Statment
		$sql = "SELECT ".$type->TABLE_NAME."_id FROM ".$type->TABLE_NAME;
		
		//query SQL database
		$types = pdSelect($sql,"mysqli");
		
		//reduce array to one dimension
		return($type->mergeResult($types));
	}
	
	//get a select for countries
	
	public static function getSelect($attributes = array(),$option = "0") {
		//get array of all types
		$types = StorageType::getAll();
		
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
		
		$post .= '<option value="0"> '.STORAGE_OVERVIEW_SEARCH_TYPE.' </option>';
		//set all types as option
		foreach($types as $tmpType) {
			$type = new StorageType;
			if($type->loadData($tmpType)) {
				if($option == $type->getID()) {
					$selected = "selected";
				} else {
					$selected = "";
				}
				
				$post .= '<option value="'.$type->getID().'" '.$selected.'>'.$type->getName().'</option>';
			}
		}
		
		$post .= '</select>';
		
		//return select as HTML
		
		return($post);
		
	}
	
	
	//get functions
	
	function getID() {
		return($this->storage_type_id);
	}
	
	function getName() {
		if(isset($_SESSION['lang']) AND $_SESSION['lang'] == "de") {
			return($this->name_de);
		}
		return($this->name_en);
	}
	
}