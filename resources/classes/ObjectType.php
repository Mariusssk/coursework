<?php

class ObjectType extends SystemClass {
	
	//Functions

	//get a list of all storage type IDs
	
	static function getAll($object = "") {
		if(empty($object)) {
			$objectClass = static::class;
			$object = new $objectClass;
		}
		return(Parent::getAll($object));
	}
	
	//get a select for types
	
	public static function getSelect($attributes = array(),$option = "0",$placeholder = "Select Type") {
		//get array of all types
		$types = static::class::getAll();
		
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
		
		$objectClass = static::class;
		
		$post .= '<option value="0"> '.$placeholder.' </option>';
		//set all types as option
		foreach($types as $tmpType) {
			$type = new $objectClass;
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
	
}
		
	