<?php
//-----------------New PHP Class File---------------------

class EventResponsible extends SystemClass {
	
	protected $event_responsible_id , $user_id, $event_id;
	
	function __construct() {
		//Name of the table
		$this->TABLE_NAME = "event_responsible";
	}
	
	//Functions
	
	
	//find all responsibles for specific event
	static function loadResponsibleForEvent($eventID) {
		$responsible = new EventResponsible;
		
		$sql = "SELECT user_id FROM ".$responsible->TABLE_NAME." WHERE event_id = ?";
		$sqlType = "i";
		$sqlParams = array($eventID);
		
		$user = pdSelect($sql,"mysqli",$sqlType,$sqlParams);
		
		$user = $responsible->mergeResult($user);
		
		return($user);
	}
	
	//delete all event responsibles for one event
	static function deleteEventResponsibles($eventID) {
		$responsible = new EventResponsible;
		
		$sql = "DELETE FROM ".$responsible->TABLE_NAME." WHERE event_id = ?";
		$sqlType = "i";
		$sqlParams = array($eventID);
		
		pdInsert($sql,"mysqli",$sqlType,$sqlParams);
		
		return(True);
	}
	
	//check if user is responsible for event
	
	static function checkIfIsResponsible($userID,$eventID) {
		$responsible = new EventResponsible;
		
		$sql = "SELECT user_id FROM ".$responsible->TABLE_NAME." WHERE user_id = ? AND event_id = ?";
		$sqlType = "ii";
		$sqlParams = array($userID,$eventID);
		
		$user = pdSelect($sql,"mysqli",$sqlType,$sqlParams);
		
		if(count($user) > 0) {
			return(True);
		}
		return(False);
	}
	
	//Load responsible data on user and event id
	
	function loadDataOnUserAndEventID($userID, $eventID) {
		//Find tag assignment
		$sql = "SELECT ".$this->TABLE_NAME."_id FROM ".$this->TABLE_NAME." WHERE user_id = ? AND event_id = ?";
		$sqlType = "ii";
		$sqlParams = array($userID, $eventID);
		
		$responsible = pdSelect($sql,"mysqli",$sqlType,$sqlParams);
		
		$responsible = $this->mergeResult($responsible);
		
		//Load data of assignment
		if(count($responsible) == 1) {
			return($this->loadData($responsible[0]));
		}
		return(False);
	}
	
	//get functions
	
	function getID() {
		return($this->event_responsible);
	}
	
	//set functions
	
	function setUserID($value) {
		$user = new User;
		if($user->loadData($value)) {
			$this->user_id = $value;
			return(True);
		}
		return(False);
	}
	
	function setEventID($value) {
		$event = new Event;
		if($event->loadData($value)) {
			$this->event_id = $value;
			return(True);
		}
		return(False);
	}
}
?>