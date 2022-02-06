<?php

//-----------------New PHP Class File---------------------

class AttributeType {
	
	//create select for all attribute types
	
	public static function getSelect($attributes = array(),$option = "0",$placeholder = "Select Type") {
		
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

		$post .= '<option value="3"> '.WORD_TODO_LIST.' </option>';
		$post .= '<option value="1"> '.WORD_EVENT.' </option>';
		
		$post .= '</select>';
		
		//return select as HTML
		
		return($post);
		
	}
}

?>