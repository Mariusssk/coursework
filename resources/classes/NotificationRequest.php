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
	
	//Delete all notifications for specific request
	
	function deleteAllNotifications() {
		return(Notification::deleteAllNotificationsForRequest($this->getID()));
	}
	
	//load request by attribute and user
	
	function loadDataByAttributeAndUser($userID, $typeID, $attributeID) {
		
		$request = new NotificationRequest;
		
		$sql = "SELECT ".$request->TABLE_NAME."_id FROM ".$request->TABLE_NAME." WHERE user_id = ? AND attribute_type_id = ? AND attribute_id = ?";
		$sqlType = "iii";
		$sqlParams = array($userID, $typeID, $attributeID);
		
		$requests = pdSelect($sql,"mysqli",$sqlType,$sqlParams);
		
		$requests = $request->mergeResult($requests);
		
		if(count($requests) == 1) {
			return($this->loadData($requests[0]));
		}
		return(False);
	}
	
	//get all requests for specific attribute and typeID
	
	public static function getRequestsForTypeAndAttribute($type, $attributeID) {
		$request = new NotificationRequest;
		
		$sql = "SELECT ".$request->TABLE_NAME."_id FROM ".$request->TABLE_NAME." WHERE attribute_type_id = ? and attribute_id = ?";
		$sqlType = "ii";
		$sqlParams = array($type, $attributeID);
		
		$requests = pdSelect($sql,"mysqli",$sqlType,$sqlParams);
		
		$requests = $request->mergeResult($requests);
		
		return($requests);	
	}
	
	//check if request is in place
	
	public static function checkIfRequestActivated($userID, $typeID, $attributeID) {
		$request = new NotificationRequest;
		
		$sql = "SELECT ".$request->TABLE_NAME."_id FROM ".$request->TABLE_NAME." WHERE user_id = ? AND attribute_type_id = ? AND attribute_id = ?";
		$sqlType = "iii";
		$sqlParams = array($userID, $typeID, $attributeID);
		
		$requests = pdSelect($sql,"mysqli",$sqlType,$sqlParams);
		
		$requests = $request->mergeResult($requests);
		
		if(count($requests) > 0) {
			return(True);
		}
		return(False);
	}
	
	//Create a new notification request
	
	public static function createRequest($userID, $typeID, $attributeID) {
		if(NotificationRequest::checkIfRequestActivated($userID, $typeID, $attributeID) == False) {
			$request = new NotificationRequest;
			
			$sql = "
			INSERT INTO ".$request->TABLE_NAME."
			(`user_id`, `email_update`, `daily_update`, `attribute_type_id`, `attribute_id`) 
			VALUES (?,?,?,?,?)
			";
			
			$sqlType = "iiiii";
			$sqlParams = array($userID, 0, 0, $typeID, $attributeID);
			
			pdInsert($sql,"mysqli",$sqlType,$sqlParams);
			
			return(True);
		}
		return(False);
	}
	
	//delete existing notification request
	
	public static function deleteRequest($userID, $typeID, $attributeID) {
		$request = new NotificationRequest;
		if(
			NotificationRequest::checkIfRequestActivated($userID, $typeID, $attributeID) == True AND
			$request->loadDataByAttributeAndUser($userID, $typeID, $attributeID)
		) {
			if($request->deleteAllNotifications()) {
				$request = new NotificationRequest;
				
				$sql = "
				DELETE FROM ".$request->TABLE_NAME." WHERE user_id = ? AND attribute_type_id = ? AND attribute_id = ?
				";
				
				$sqlType = "iii";
				$sqlParams = array($userID, $typeID, $attributeID);
				
				pdInsert($sql,"mysqli",$sqlType,$sqlParams);
				
				return(True);
			}
		}
		return(False);
	}
	
	//Update function
	
	function updateEmailRequest($value) {
		if($value == 1 OR $value == 0) {
			return($this->updateData("email_update",$value));
		}
		return(False);
	}
	
	function updateDailyRequest($value) {
		if($value == 1 OR $value == 0) {
			return($this->updateData("daily_update",$value));
		}
		return(False);
	}
	
	//get functions
	
	function getID() {
		return($this->notification_request_id);
	}

	function getAttributeID() {
		return($this->attribute_id);
	}
	
	function getAttributeTypeID() {
		return($this->attribute_type_id);
	}
	
	function getEmailUpdate() {
		return($this->email_update);
	}
	
	function getDailyUpdate() {
		return($this->daily_update);
	}
	
	function getUserID() {
		return($this->user_id);
	}
	
	function getAttributeName() {
		if($this->getAttributeTypeID() == 3) {
			$todo = new ToDoList;
			if($todo->loadData($this->getAttributeID())) {
				return($todo->getName());
			}
		} else if($this->getAttributeTypeID() == 1) {
			$event = new Event;
			if($event->loadData($this->getAttributeID())) {
				return($event->getName());
			}
		}
		return("");
	}
}