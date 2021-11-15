<?php

class NotificationRequest extends SystemClass {
	
	protected $notification_request_id, $user_id, $email_update, $daily_update, $attribute_type_id, $attribute_id;
	
	function __construct() {
		//Name of the table
		$this->TABLE_NAME = "notification_request";
	}
	
	//get all requests for specific user_error
	
	public static function loadRequestsByUserID($userID) {
		$request = new NotificationRequest;
		
		$sql = "SELECT ".$request->TABLE_NAME."_id FROM ".$request->TABLE_NAME." WHERE user_id = ?";
		$sqlType = "i";
		$sqlParams = array($userID);
		
		$requests = pdSelect($sql,"mysqli",$sqlType,$sqlParams);
		
		$requests = $request->mergeResult($requests);
		
		return($requests);
	}
	
	//get functions
	
	function getID() {
		return($this->notification_request);
	}

	function getAttributeID() {
		return($this->attribute_id);
	}
	
	function getAttributeTypeID() {
		return($this->attribute_type_id);
	}
}