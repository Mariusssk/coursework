<?php


class SystemClass {
	
	public $NULL_VAR = array();
	public $TABLE_NAME = "";
	public $IGNORE = array("NULL_VAR","TABLE_NAME","IGNORE");
	
	//get all variables of an object
	
	public function getObjectVars() {
		return(get_object_vars($this));
	}
	
	//check if ID is numeric
	
	function isValidID($ID) {
		if (preg_match('/^[0-9]+$/', $ID) === 1) {
			return(True);
		}
		return(False);
	}
	
	//load data from database
	
	function loadData($ID = 0) {
		if(SystemClass::isValidID($ID)) {
			
			//create SQL statment
			
			$sql = "SELECT * FROM ".$this->TABLE_NAME." WHERE ".$this->TABLE_NAME."_id = ?";
		
			$sqlType = "i";
			$sqlParams = array($ID);
			
			//run SQL statment
			
			$data = pdSelect($sql, "mysqli",$sqlType,$sqlParams);
			
			//load data returned from SQL databse

			if(count($data) == 1) {
				$data = $data[0];
				$vars = $this->getObjectVars();
				foreach($vars as $varKey => $tmpVar) {
					
					if(isset($data[$varKey])) {
						$this->$varKey= htmlspecialchars($data[$varKey]);
					}
				}
				
				//return True if data is succesfully loaded

				if($this->dataFullyLoaded()) {
					return(True);
				}
			}
		}
		return(False);
	}
	
	//check if all needed data is loaded succesfully
	
	public function dataFullyLoaded() {
		try {
			$fail = 0;
			$vars = $this->getObjectVars();
			//run through every var of an object
			
			foreach($vars as $tmpVar => $value) {
				//check if var can be empty
				if(!isset($this->IGNORE) OR !in_array($tmpVar, $this->IGNORE) AND !in_array($tmpVar,$this->NULL_VAR)) {
					if((!isset($this->$tmpVar) OR (empty($this->$tmpVar) AND $this->$tmpVar !== "0" AND $this->$tmpVar !== 0))) {
						$fail += 1;
						//if var is empty throw excemption
						throw new Exception($tmpVar." is not allowed to be empty or NULL!");
					}
				}
			}
			
			if($fail > 0) {
				return(False);
			} else {
				return(True);
			}
		}
		
		catch(Exception $e) {
			echo 'Error: ' .$e->getMessage();
		}
	}
	
	//save all data of an object to DB
	
	function saveData() {
		if($this->dataFullyLoaded()) {
			//create SQL statment
			$sql = "UPDATE  ".$this->TABLE_NAME." SET ";
			$sqlSet = "";
			$sqlType = "";
			$sqlParams = array();
			
			$vars = $this->getObjectVars();
			
			//add all vars of an object which are not ignored
			
			foreach($vars as $varKey => $tmpVar) {
				if(!isset($this->IGNORE) OR !in_array($varKey, $this->IGNORE)) {
					if(isset($this->$varKey)  AND (!empty($this->$varKey) OR $this->$varKey === "0" OR $this->$varKey === 0)) {
						if(!empty($sqlSet)) { $sqlSet.= ",";}
						$sqlType .= "s";
						array_push($sqlParams,$this->$varKey);
						$sqlSet .= $varKey." = ? ";
					} else if(empty($this->$varKey) AND in_array($varKey,$this->NULL_VAR)) {
						if(!empty($sqlSet)) { $sqlSet.= ",";}
						$sqlSet .= $varKey." = NULL ";
					}
				}
			}
			
			//run SQL statment
			
			if(!empty($sqlSet) AND strlen($sqlType) == count($sqlParams)) {
				$sql .= $sqlSet. " WHERE ".$this->TABLE_NAME."_id = ?";
				$sqlType .= "i";
				array_push($sqlParams,$this->id);
				pdInsert($sql, "mysqli",$sqlType,$sqlParams);
				
				return(True);
			}
			
		}
		return(False);
	}
	
	//create new DB entry based on object instance
	
	function createNewData() {
		if($this->dataFullyLoaded()) {
			//create SQL statment
			$sql = "INSERT INTO ".$this->TABLE_NAME." ";
			$sqlSet = "";
			$sqlValues = "";
			$sqlType = "";
			$sqlParams = array();
			
			//add all vars which are not ignored to sql statment
			
			$vars = $this->getObjectVars();
			foreach($vars as $varKey => $tmpVar) {
				if(!isset($this->IGNORE) OR !in_array($varKey, $this->IGNORE)) {
					if(isset($this->$varKey)  AND (!empty($this->$varKey) AND $this->$varKey !== "0" AND $this->$varKey !== 0)) {
						if(!empty($sqlSet)) { $sqlSet.= ",";}
						if(!empty($sqlValues)) { $sqlValues.= ",";}
						$sqlType .= "s";
						array_push($sqlParams,$this->$varKey);
						$sqlSet .= $varKey;
						$sqlValues .= "?";
					}
				}
			}
			
			//run SQL statment
			
			if(!empty($sqlSet) AND strlen($sqlType) == count($sqlParams)) {
				
				$sql .= "(".$sqlSet.") VALUES (".$sqlValues.")";
				pdInsert($sql, "mysqli",$sqlType,$sqlParams);
				$lastID = pdLastID("mysqli");
				$this->id = $lastID;
				return(True);
			}
			
		}
		return(False);
	}
	
	//Update single values of object to DB
	
	protected function updateData($cellName,$value) {
		if($this->dataFullyLoaded()){
			//check if data is or can be NULL
			
			if($value != "NULL" AND !in_array($cellName,$this->NULL_VAR)) {
				$sql = "UPDATE ".$this->TABLE_NAME." SET ".$cellName." = ? WHERE ".$this->TABLE_NAME."_id = ?";
				$sqlType = "";
				if(is_numeric($value)) {
					$sqlType .= "i";
				} else {
					$sqlType .= "s";
				}
				$sqlType .= "i";
				$sqlParams = array($value,$this->id);
			} else if(in_array($cellName,$this->NULL_VAR)){
				$sql = "UPDATE ".$this->TABLE_NAME." SET ".$cellName." = NULL WHERE ".$this->TABLE_NAME."_id = ?";
				$sqlType = "i";
				$sqlParams = array($this->ID);
			} else {
				return(False);
			}
			pdInsert($sql,"mysqli",$sqlType,$sqlParams);
			return(True);
		}
	}
	
	//delete corresponding entry from DB
	
	public function deleteData() {
		if(isset($this->id) AND !empty($this->id)) {
			$sql = "DELETE FROM ".$this->TABLE_NAME." WHERE ".$this->TABLE_NAME."_id = ?";
			$sqlType = "i";
			$sqlParams = array($this->id);
			
			pdInsert($sql,"mysqli",$sqlType,$sqlParams);
			return(True);
		}
		return(False);
	}
	
	
	//reduce SQL database result to one dimension array
	
	function mergeResult($result) {
		for($i = 0; $i < count($result);$i++) {
			$result[$i] = array_values($result[$i])[0];
		}
		return($result);
	}
}