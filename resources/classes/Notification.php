<?php

class Notification extends SystemClass {
	
	protected $notification_id, $notification_request_id , $email_sent, $seen;
	
	function __construct() {
		//Name of the table
		$this->TABLE_NAME = "notification";
	}
	
	//get functions
	
	function getID() {
		return($this->notification_id);
	}
	
	function getSeenAsBoolean() {
		if($this->seen == 1) {
			return(True);
		}
		return(False);
	}
	
	
}