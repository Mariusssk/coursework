<?php
//-----------------New PHP Class File---------------------

class Notification extends SystemClass{
	
	protected $notification_id, $notification_request_id , $email_sent, $seen, $time_posted;
	
	function __construct() {
		//Name of the table
		$this->TABLE_NAME = "notification";
	}
	
	//count open notifications
	//Objective 11
	public static function countUnreadNotifications($userID) {
		$notifications = Notification::getAllNotificationsForUser($userID, True);
		return(count($notifications));
	}
	
	//get all personal notifications for userID
	//Objective 11
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
			
			$sql .= " ORDER BY time_posted DESC";
			
			$notifications = pdSelect($sql,"mysqli",$sqlType, $sqlParams);
			
			$notifications = $notification->mergeResult($notifications);
			
			return($notifications);
		}
		return(array());
	}
	
	//detele all notifications for sepcific request
	
	public static function deleteAllNotificationsForRequest($requestID) {
		$notification = new Notification;
		$sql = "DELETE FROM ".$notification->TABLE_NAME." WHERE notification_request_id = ?";
		$sqlType = "i";
		$sqlParams = array($requestID);
		
		pdInsert($sql, "mysqli", $sqlType, $sqlParams);
		
		return(True);
	}
	
	//Create a new notification
	//Objective 11
	function createNewNotification($requestID) {
		$request = new NotificationRequest;
		if($request->loadData($requestID)) {
			$this->setRequestID($request->getID());
			$this->setSeen(0);
			
			$this->setTimePostedNow();
			
			if($request->getEmailUpdate() == 1) {
				$this->setEmailSent(0);
			} else {
				$this->setEmailSent(1);
			}
			
			if($this->createNewData()) {
				return(True);
			}
		}
		return(False);
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
	
	function getEmailSent() {
		return($this->email_sent);
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
	
	function getTimePosted($type) {
		if($type == "external") {
			$posted = new DateTime($this->time_posted);
			return($posted->format("d.m.Y H:i"));
		}
	}
	
	//update
	
	//set if email was send if requested
	//Objective 11.4
	
	function updateEmailSent($value) {
		if($value == 0 OR $value == 1) {
			if($this->updateData("email_sent",$value)) {
				return(True);
			}
		}
		return(False);
	}
	
	//Set functions
	
	function setEmailSent($value) {
		if($value == 1 OR $value == 0) {
			$this->email_sent = $value;
			return(true);
		} 
		return(False);
	}
	
	function setRequestID($value) {
		$this->notification_request_id = $value;
		return(True);
	}
	
	function setSeen($value) {
		if($value == 1 OR $value == 0) {
			$this->seen = $value;
			return(true);
		} 
		return(False);
	}
	
	function setTimePostedNow() {
		$now = new DateTime();
		$this->time_posted = $now->format("Y-m-d H:i:s");
	}
}
?>