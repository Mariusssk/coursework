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
	
	//komplex function displaying grid of shelf
	function displayGrid() {
		$post = "";
		//check if storage is valid as grid generator
		if($this->getTypeID() == 2) {
			$sizeX = $this->getX();
			$sizeY = $this->getY();
			
			//check if size is valid
			if($sizeX > 0 AND $sizeY > 0) {
				
				//get all childs which are in grid
				$sql = "SELECT ".$this->TABLE_NAME."_id as ID,size_x as x,size_y as y,name FROM ".$this->TABLE_NAME." WHERE ".$this->TABLE_NAME."_parent_id = ? AND size_x <= ? AND size_y <= ? AND size_x IS NOT NULL AND size_x > 0 AND size_y IS NOT NULL and size_y > 0 ORDER BY size_y ASC, size_x ASC ";
				$sqlType = "iii";
				$sqlParams = array($this->getID(),$sizeX,$sizeY);
				
				$childs = pdSelect($sql,"mysqli",$sqlType,$sqlParams);
				
				//generate grid
				for($y = 1;$y <= $sizeY;$y++) {
					$post .= '<div class="gridRow">';
					for($x = 1;$x <= $sizeX;$x++) {
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
		return($post);
	}

}

