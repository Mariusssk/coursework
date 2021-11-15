<?php

class Comment extends SystemClass {
	
	protected $comment_id , $user_id, $attribute_type_id, $attribute_id, $data, $posting_date;
	
	function __construct() {
		//Name of the table
		$this->TABLE_NAME = "comment";
		
		//Vars which are allowed to be NULL
		$this->NULL_VAR = array("");
	}
	
	//load all comments for type and attributeID
	
	public static function loadCommentsForTypeAndAttribute($type, $attributeID) {
		$comment = new Comment;
		$sql = "SELECT ".$comment->TABLE_NAME."_id FROM ".$comment->TABLE_NAME." WHERE attribute_type_id = ? AND attribute_id = ?";
		$sqlType = "ii";
		$sqlParams = array($type, $attributeID);
		
		$comments = pdSelect($sql, "mysqli", $sqlType, $sqlParams);
		
		$comments = $comment->mergeResult($comments);
		
		return($comments);
	}
	
	//Create new comment
	
	function createNewComment($type, $attributeID, $comment, $userID) {
		$this->attribute_type_id = $type;
		$this->attribute_id = $type;
		$this->user_id = $type;
		$this->data = htmlspecialchars($comment);
		
		$now = new DateTime;
		$this->posting_date = $now->format("Y-m-d h:i:s");
		
		return($this->createNewData());
	}
	
	//Get functions
	
	
}