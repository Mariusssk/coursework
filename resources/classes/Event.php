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
	
	//create array of all events by time
	
	public static function createTimeListAllEvents() {
		$eventList = array("running" => array(), "soon" => array(), "future" => array(), "past" => array(), "uncategorized" => array());
		
		$events = Event::getALL();
		
		foreach($events as $tmpEvent) {
			$event = new Event;
			if($event->loadData($tmpEvent)) {
				$now = new DateTime();
				
				$startTime = new DateTime($event->getTime("start"));
				$endTime = new DateTime($event->getTime("end"));
				
				$soonTime = new DateTime();
				$soonTime->modify("+14 days");
				
				$type = "";
				
				if($now > $endTime) {
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
	
	function getTime($timeType) {
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
		
		return($time->format("d.m.Y H:i"));
	}
	
	function getDisplayTime($timeType) {
		if($timeType == "start" OR $timeType == "end") {
			$time = new DateTime($this->getTime($timeType));
			return($time->format("d.m.Y H:i"));
		} 
		return("");
	}
}