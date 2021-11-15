<?php

class Notification extends SystemClass{
	
	protected $notification_id, $notification_request_id , $email_sent, $seen;
	
	function __construct() {
		//Name of the table
		$this->TABLE_NAME = "notification";
	}
	
	//count open notifications
	
	public static function countUnreadNotifications($userID) {
		$notifications = Notification::getAllNotificationsForUser($userID, True);
		return(count($notifications));
	}
	
	//get all personal notifications for userID
	
	public static function getAllNotificationsForUser($userID, $onlyUnread = False) {
		$notificationsRequests = NotificationRequest::loadRequestsByUserID($userID);
		
		if(count($notificationsRequests) > 0) {
			
			$notification = new Notification;
			
			$sql = "SELECT ".$notification->TABLE_NAME."_id FROM ".$notification->TABLE_NAME." WHERE ";
			$sqlType = "";
			$sqlParams = array();
			
			//check if only unread should be selectred
			
			if($onlyUnread == True) {
				$sqlType .= "i";
				array_push($sqlParams, "0");
				$sql .= " seen = ? ";
			}
			
			$whereRequest = "";
			
			//add options for request ID
			
			foreach($notificationsRequests as $tmpRequest) {
				if(!empty($whereRequest)){$whereRequest.= " OR ";}
				$whereRequest .= "notification_request_id = ?";
				$sqlType .= "i";
				array_push($sqlParams, $tmpRequest);
			}
			
			//check if where is empty or not
			if(count($sqlParams) != count($notificationsRequests)) {
				$sql .= " AND ";
			}
			
			$sql .= "(".$whereRequest.")";
			
			$sql .= " ORDER BY ".$notification->TABLE_NAME."_id ASC";
			
			$notifications = pdSelect($sql,"mysqli",$sqlType, $sqlParams);
			
			$notifications = $notification->mergeResult($notifications);
			
			return($notifications);
		}
		return(array());
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
	
	function getRequestID() {
		return($this->notification_request_id);
	}
	
	function getAttributeID() {
		$request = new NotificationRequest;
		if($request->loadData($this->getRequestID())) {
			return($request->getAttributeID());
		}
	}
	
	function getAttributeTypeID() {
		$request = new NotificationRequest;
		if($request->loadData($this->getRequestID())) {
			return($request->getAttributeTypeID());
		}
	}
	
	//Set functions
	
	function setSeen($value) {
		if($value == 1 OR $value == 0) {
			$this->seen = $value;
			return(true);
		} 
		return(False);
	}

	
}