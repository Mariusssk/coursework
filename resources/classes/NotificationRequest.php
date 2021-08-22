<?php

class NotificationRequest extends SystemClass {
	
	protected $notification_request_id, $user_id, $email_update, $daily_update, $attribute_type_id, $attribute_id;
	
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