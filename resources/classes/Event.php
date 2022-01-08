<?php

class Event extends ObjectType {
	
	protected $event_id, $name, $event_client_id, $event_location_id, $start_time, $end_time, $description;
	
	function __construct() {
		//Name of the table
		$this->TABLE_NAME = "event";
		
		array_push($this->NULL_VAR, "event_client_id", "event_location_id", "end_time", "description");
	}
	
	//Functions

	//get all events with specific client
	
	public static function selectAllEventsWithClient($clientID) {
		$event = new Event;
		$sql = "SELECT ".$event->TABLE_NAME."_id FROM ".$event->TABLE_NAME." WHERE event_client_id = ?";
		$sqlType = "i";
		$sqlParams = array($clientID);
		
		$events = pdSelect($sql, "mysqli", $sqlType, $sqlParams);
		
		$events = $event->mergeResult($events);
		
		return($events);
	}
	
	//get all events with specific location
	
	public static function selectAllEventsWithLocation($clientID) {
		$event = new Event;
		$sql = "SELECT ".$event->TABLE_NAME."_id FROM ".$event->TABLE_NAME." WHERE event_location_id = ?";
		$sqlType = "i";
		$sqlParams = array($clientID);
		
		$events = pdSelect($sql, "mysqli", $sqlType, $sqlParams);
		
		$events = $event->mergeResult($events);
		
		return($events);
	}
	
	//load a list of all tags
	
	function getTags() {
		$tags = TagAssignment::loadTagsByAttribute(1,$this->getID());
		return($tags);
	}
	
	//load a list of responsible user
	
	function getResponsible() {
		$user = EventResponsible::loadResponsibleForEvent($this->getID());
		return($user);
	}
	
	//create array of all events by time
	
	public static function createTimeListAllEvents($session) {
		$event = new Event;
		$eventList = array("running" => array(), "soon" => array(), "future" => array(), "past" => array(), "uncategorized" => array());
		
		$sql = "SELECT ".$event->TABLE_NAME."_id FROM ".$event->TABLE_NAME." ORDER BY start_time ASC";
		
		$events = pdSelect($sql, "mysqli");
		
		$events = $event->mergeResult($events);
		
		foreach($events as $tmpEvent) {
			$event = new Event;
			if($event->loadData($tmpEvent) AND $event->checkRights($session, "view")) {
				$now = new DateTime();
				
				$startTime = new DateTime($event->getTime("start"));
				$endTime = new DateTime($event->getTime("end"));
				
				$soonTime = new DateTime();
				$soonTime->modify("+14 days");
				
				$type = "";
				
				if($startTime->format("Y") == 1000) {
					$type = "uncategorized";
				} else if($now > $endTime) {
					$type = "past";
				} else if($now > $startTime AND $now < $endTime) {
					$type = "running";
				} else if($startTime < $soonTime) {
					$type = "soon";
				} else if($startTime > $soonTime) {
					$type = "future";
				} else {
					$type = "uncategorized";
				}
				
				
				array_push($eventList[$type], $event->getID());
			}
		}
		
		return($eventList);
	}
	
	//check event specific rights
	
	function checkRights($session, $rightType) {
		$eventResponsible = EventResponsible::checkIfIsResponsible($session->getSessionUserID(),$this->getID());
		if($rightType == "view") {
			if(
				$session->checkRights("view_all_events") == True OR
				($session->checkRights("view_own_events") == True AND $eventResponsible)
			) {
				return(True);
			}
		} else if($rightType == "edit") {
			if(
				$session->checkRights("edit_all_events") == True OR
				($session->checkRights("edit_own_events") == True AND $eventResponsible)
			) {
				return(True);
			}
		} else if($rightType == "delete") {
			if(
				$session->checkRights("delete_all_events") == True OR
				($session->checkRights("delete_own_events") == True AND $eventResponsible)
			) {
				return(True);
			}
		}
		return(False);
	}
	
	//delete event data and attributes as tags
	
	function deleteEvent() {
		TagAssignment::deleteTagsForAttribute(1,$this->getID());
		
		//delete responsibles
		
		if($this->deleteData()) {
			return(True);
		}
		return(False);
	}
	
	//load all tags for this event
	
	function loadTags() {
		$tags = TagAssignment::loadTagsByAttribute(1, $this->getID());
		return($tags);
	}
	
	//get functions
	
	function getID() {
		return($this->event_id);
	}
	
	function getName() {
		return($this->name);
	}
	
	function getLocationID() {
		return($this->event_location_id);
	}
	
	//load location to get location name
	
	function getLocationName() {
		$location = new EventLocation;
		if(!empty($this->getLocationID()) AND $location->loadData($this->getLocationID())) {
			return($location->getName());
		}
		return("");
	}
	
	function getClientID() {
		return($this->event_client_id);
	}
	
	//load client to get client name
	
	function getClientName() {
		$client = new EventClient;
		if(!empty($this->getClientID()) AND $client->loadData($this->getClientID())) {
			return($client->getName());
		}
		return("");
	}
	
	//load client to get client extern
	
	function getClientExternal() {
		$client = new EventClient;
		if(!empty($this->getClientID()) AND $client->loadData($this->getClientID())) {
			return($client->getExternal());
		}
		return("");
	}
	
	//get start/end time of event
	//generate end time if not set
	
	function getTime($timeType,$format = "display") {
		if($timeType == "start") {
			$time = new DateTime($this->start_time);
		} else if($timeType == "end") {
			if(empty($this->end_time)) {
				$time = new DateTime($this->start_time);
				$timeString = $time->format("Y-m-d")." 23:59";
				$time = new DateTime($timeString);
			} else {
				$time = new DateTime($this->end_time);
			}
		} else {
			return("");
		}
		
		if($format == "formDate") {
			return($time->format("Y-m-d"));
		} else if($format == "formTime") {
			return($time->format("H:i"));
		}
		
		return($time->format("d.m.Y H:i"));
	}
	
	function getDisplayTime($timeType) {
		if($timeType == "start" OR $timeType == "end") {
			$time = new DateTime($this->getTime($timeType));
			return($time->format("d.m.Y H:i"));
		} 
		return("");
	}
	
	function getDescription() {
		return($this->description);
	}
	
	//create url
	
	function getURL() {
		return(URL."/events/edit/".$this->getID());
	}
	
	//set functions
	
	
	function setName($value) {
		if(!empty($value)) {
			$this->name = $value;
			return(True);
		}
		return(False);
	}
	
	function setLocation($value) {
		$location = new EventLocation;
		if($location->loadData($value) OR $value === "") {
			$this->event_location_id = $value;
			return(True);
		} else if(empty($value) OR $value == 0) {
			$this->event_location_id = "";
			return(True);
		}
		return(False);
	}
	
	function setClient($value) {
		$client = new EventClient;
		if($client->loadData($value) OR $value === "") {
			$this->event_client_id = $value;
			return(True);
		} else if(empty($value) OR $value == 0) {
			$this->event_client_id = "";
			return(True);
		}
		return(False);
	}
	
	function setStartTime($date,$time) {
		if(!empty($date) AND !empty($time)) {
			$date = new DateTime($date." ".$time);
			$this->start_time = $date->format("Y-m-d H:i:s");
			return(True);
		}
		return(False);
	}
	
	function setEndTime($date,$time) {
		if(!empty($date) AND !empty($time)) {
			$date = new DateTime($date." ".$time);
			$this->end_time = $date->format("Y-m-d H:i:s");
			return(True);
		} else if(empty($date) AND empty($time)) {
			$this->end_time = "";
			return(True);
		}
		return(False);
	}
	
	function setDescription($value) {
		$this->description = $value;
		return(True);
	}
}