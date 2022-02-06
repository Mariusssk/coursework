<?php

//-----------------New PHP Class File---------------------

class Comment extends SystemClass {
	
	protected $comment_id , $user_id, $attribute_type_id, $attribute_id, $data, $posting_date;
	
	function __construct() {
		//Name of the table
		$this->TABLE_NAME = "comment";
		
		//Vars which are allowed to be NULL
		$this->NULL_VAR = array("");
	}
	
	//count all comments for one user
	
	public static function countUserComments($userID) {
		$comment = new Comment;
		$sql = "SELECT ".$comment->TABLE_NAME."_id FROM ".$comment->TABLE_NAME." WHERE user_id = ?";
		$sqlType = "i";
		$sqlParams = array($userID);
		
		$comments = pdSelect($sql, "mysqli", $sqlType, $sqlParams);
		
		$comments = $comment->mergeResult($comments);
		
		return(count($comments));
	}
	
	//load all comments for type and attributeID
	
	public static function loadCommentsForTypeAndAttribute($type, $attributeID) {
		$comment = new Comment;
		$sql = "SELECT ".$comment->TABLE_NAME."_id FROM ".$comment->TABLE_NAME." WHERE attribute_type_id = ? AND attribute_id = ? ORDER BY posting_date ASC";
		$sqlType = "ii";
		$sqlParams = array($type, $attributeID);
		
		$comments = pdSelect($sql, "mysqli", $sqlType, $sqlParams);
		
		$comments = $comment->mergeResult($comments);
		
		return($comments);
	}
	
	//Create new comment
	
	function createNewComment($type, $attributeID, $comment, $userID) {
		$this->attribute_type_id = $type;
		$this->attribute_id = $attributeID;
		$this->user_id = $userID;
		$this->setData($comment);
		
		$now = new DateTime;
		$this->posting_date = $now->format("Y-m-d H:i:s");
		
		if($this->createNewData() AND $this->createNotifications($userID)) {
			return(true);
		}
	}
	
	//Create notificiations for new comments
	
	function createNotifications($userID) {
		$requests = NotificationRequest::getRequestsForTypeAndAttribute($this->getTypeID(), $this->getAttributeID());
		
		foreach($requests as $tmpRequest) {
			$request = new NotificationRequest;
			if($request->loadData($tmpRequest) AND $request->getUserID() != $userID) {
				$notificiation = new Notification;
				$notificiation->createNewNotification($request->getID());
			}
		}
		
		return(True);
	}
	
	//Get functions
	
	function getID() {
		return($this->comment_id);
	}
	
	function getUserID() {
		return($this->user_id);
	}
	
	function getData() {
		return($this->data);
	}
	
	function getTypeID() {
		return($this->attribute_type_id);
	}
	
	function getAttributeID() {
		return($this->attribute_id);
	}
	
	function getTimestamp() {
		$timestamp = new DateTime($this->posting_date);
		return($timestamp->format("d.m.Y H:i"));
	}
	
	function getUsername() {
		$user = new User;
		if($user->loadData($this->getUserID())) {
			return($user->getUsername());
		}
		return("");
	}
	
	//set function
	
	function setData($value) {
		if(!empty($value)) {
			$this->data = htmlspecialchars($value);
			return(True);
		}
		return(False);
	}
}

?>