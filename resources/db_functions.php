<?php

//-----------------Config File---------------------
//DB Config File
//File managing requests to database and preventing sql injections
//-----------------Config File---------------------

function pdSelect($sql,$mysqli,$type = "",$params_a = array()) {
	
	try {
		if(strlen($type) != count($params_a)) {
			throw new Exception("Type and Params must be same lenght!");
		}
	}
	
	catch(Exception $e) {
		echo 'SQL Error: ' .$e->getMessage();
		die();
	}
	
	if($mysqli == "mysqli") {
		global $mysqli;
	} elseif ($mysqli == "mysqli2") {
		global $mysqli2;
		$mysqli = $mysqli2;
	} 

	if (!($stmt = $mysqli->prepare($sql))) {
		echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
	}

	if(!empty($type)) {
		$stmt->bind_param($type, ...$params_a);
	}

	if (!$stmt->execute()) {
		echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
	} else {
		
		

		$stmt->store_result();

		$meta = $stmt->result_metadata();

		while ($field = $meta->fetch_field())
		{
			$params[] = &$row[$field->name];
		}

		call_user_func_array(array($stmt, 'bind_result'), $params);

		while ($stmt->fetch()) {
			foreach($row as $key => $val)
			{
				$c[$key] = $val;
			}
			$result[] = $c;
		}
		

		if(isset($result)) {

			$return = $result;

			unset($result);
			unset($c);
		} else {
			$return = [];
		}
	
		return($return);
	}
}

function pdInsert($sql,$mysqli,$type = "",$params_a = array()) {
	
	try {
		if(strlen($type) != count($params_a)) {
			throw new Exception("Type and Params must be same lenght!");
		}
	}
	
	catch(Exception $e) {
		echo 'SQL Error: ' .$e->getMessage();
		die();
	}
	
	if($mysqli == "mysqli") {
		global $mysqli;
	} elseif ($mysqli == "mysqli2") {
		global $mysqli2;
	} 
	
	if (!($stmt = $mysqli->prepare($sql))) {
		echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
	}

	if(!empty($type)) {
		$stmt->bind_param($type, ...$params_a);
	}

	if (!$stmt->execute()) {
		echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
	} else {
		return(True);
	}
}

function pdLastID($mysqli) {
	if($mysqli == "mysqli") {
		global $mysqli;
	} elseif ($mysqli == "mysqli2") {
		global $mysqli2;
	} 
	
	$lastID = $mysqli->insert_id;
	
	return($lastID);
	
}?>