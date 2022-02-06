<?php
//-----------------New PHP Class File---------------------

class ToDoList extends SystemClass {
	
	protected $todo_list_id, $event_id, $user_id, $todo_list_category_id , $created, $name;
	
	protected $entries;
	
	function __construct() {
		//Name of the table
		$this->TABLE_NAME = "todo_list";
		
		//Vars which are allowed to be NULL
		$this->NULL_VAR = array("user_id","event_id","todo_list_category_id");
		
		//Vars which should be ignored when loading or saving to DB
		array_push($this->IGNORE, "entries");
	}
	
	//create new template todo list
	
	function createNewList() {
		$this->setDateCreated();
		$this->setName(TODO_LISTS_OVERVIEW_NEW_LIST_NAME);
		return($this->createNewData());
	}
	
	//find all todo list for category
	
	static function findListByCategory($category = 0, $userID = 0) {
		$list = new ToDoList;
		$sql = "SELECT ".$list->TABLE_NAME."_id FROM ".$list->TABLE_NAME;
		$sqlType = "";
		$sqlParams = array();
		
		if($category == "uncategorized") {
			$sql .= " WHERE event_id IS NULL AND todo_list_category_id IS NULL AND user_id IS NULL";
		} else if($category == "uncategorizedPersonal") {
			$sql .= " WHERE event_id IS NULL AND todo_list_category_id IS NULL AND user_id = ?";
			$sqlType .= "i";
			array_push($sqlParams, $userID);
		} else if($category == "event") {
			$sql .= " WHERE event_id IS NOT NULL AND todo_list_category_id IS NULL";
		} else if(is_int($category) AND $category > 0){
			$sql .= " WHERE todo_list_category_id = ?";
			$sqlType .= "i";
			array_push($sqlParams, $category);
		} else {
			return(0);
		}
		
		$lists = pdSelect($sql,"mysqli",$sqlType,$sqlParams);
		
		$lists = $list->mergeResult($lists);
		
		return($lists);
	}
	
	//check rights
	
	function checkRights($rightType, $session) {
		if($rightType == "view") {
			$category = new ToDoCategory;
			if(empty($this->getCategoryID())) {
				if($this->getUserID() == $session->getSessionUserID() AND $session->checkRights('edit_personal_todo_list')) {
					return(True);
				} else if($session->checkRights('view_all_todo_lists') == True) {
					return(True);
				}
			} else if($category->loadData($this->getCategoryID())) {
				if(($category->getGlobal() == True AND $session->checkRights('view_all_todo_lists') == True) OR ($category->getGlobal() == False AND $session->checkRights('edit_personal_todo_list') == True)) {
					return(True);
				}
			}
		} else if($rightType == "edit") {
			$category = new ToDoCategory;
			if(empty($this->getCategoryID())) {
				if($this->getUserID() == $session->getSessionUserID() AND $session->checkRights('edit_personal_todo_list')) {
					return(True);
				} else if($session->checkRights('edit_all_todo_lists') == True) {
					return(True);
				}
			} else if($category->loadData($this->getCategoryID())) {
				if(($category->getGlobal() == True AND $session->checkRights('edit_all_todo_lists')) OR ($category->getGlobal() == False AND $session->checkRights('edit_personal_todo_list'))) {
					return(True);
				}
			}
		} 
		return(False);
	}
	
	//check if list has tag
	
	function checkIfListHasTag($tagID) {
		return(TagAssignment::checkIfAttributeHasTag(3,$this->getID(),$tagID));
	}
	
	//change load data function to include load entries
	function loadData($listID = 0) {
		//Run the load data of parent and if succesful try to load entries
		if(Parent::loadData($listID) === True AND $this->loadEntries() === True) {
			return(True);
		} else {
			return(False);
		}
	}
	
	
	//function load todo list entries
	function loadEntries() {
		$this->entries = array();
		//check if list ID is set
		if(!empty($this->getID())) {
			//Load all entries
			$entries = ToDoListEntry::loadEntriesArray($this->getID());
			if(count($entries) > 0) {
				$this->entries = $entries;
			}
			return(True);
		}
		return(False);
	}
	
	
	//load todo list tags
	function loadTags() {
		$tags = array();
		//check if list ID is set
		if(!empty($this->getID())) {
			//Load all entries
			$tags = TagAssignment::loadTagsByAttribute(3,$this->getID());
		}
		return($tags);
	}
	
	//delete todo list and the connected data
	
	function deleteList() {
		
		$entries = ToDoListEntry::loadEntriesArray($this->getID());
		
		foreach($entries as $tmpEntry){
			$this->recursiveDeleteEntry($tmpEntry);
		}
		
		//Delete tag
		
		TagAssignment::deleteTagsForAttribute(3,$this->getID());
		
		
		//Delete comments
		
		$requests = NotificationRequest::getRequestsForTypeAndAttribute(3,$this->getID());
		
		foreach($requests as $tmpRequest) {
			$request = new NotificationRequest;
			if($request->loadData($tmpRequest)) {
				$request->deleteAllNotifications();
				$request->deleteData();
			}
		}
		
		//Comments 
		
		$comments = Comment::loadCommentsForTypeAndAttribute(3,$this->getID());
		
		foreach($comments as $tmpComment) {
			$comment = new Comment;
			if($comment->loadData($tmpComment)) {
				$comment->deleteData();
			}
		}
		
		return($this->deleteData());
	}
	
	//create link to todo list
	
	function getURL() {
		if(empty($this->getUserID())) {
			return(URL."/todo/global/".$this->getID());
		} else {
			return(URL."/todo/personal/".$this->getID());
		}
	}
	
	//recursiveley delete todo lis entries
	
	function recursiveDeleteEntry($tmpArray) {
		if(isset($tmpArray['children']) AND count($tmpArray['children']) > 0) {
			foreach($tmpArray['children'] as $tmpChildren) {
				$this->recursiveDeleteEntry($tmpChildren);
			}
		}
		$entry = new ToDoListEntry;
		if($entry->loadData($tmpArray['currentID'])) {
			$entry->deleteData();
		}
		return;
	}
	
	//get functions
	
	function getID() {
		return($this->todo_list_id);
	}
	
	function getName() {
		return($this->name);
	}
	
	function getUserID() {
		return($this->user_id);
	}
	
	function getEventID() {
		return($this->event_id);
	}
	
	function getCategoryID() {
		return($this->todo_list_category_id);
	}
	
	function getEntries() {
		return($this->entries);
	}
	
	// set function
	
	function setUserID($value) {
		$user = new User;
		if($user->loadData($value)) {
			$this->user_id = $value;
			return(True);
		}
		return(False);
	}
	
	function setCategoryID($value) {
		$category = new ToDoCategory;
		if($category->loadData($value)) {
			$this->todo_list_category_id = $value;
			return(True);
		} else if($value == 0) {
			$this->todo_list_category_id = NULL;
			return(True);
		}
		return(False);
	}
	
	function setDateCreated() {
		$now = new DateTime;
		$this->created = $now->format("Y-m-d H:i:s");
	}
	
	function setName($value) {
		if(!empty($value)) {
			$this->name = $value;
			return(True);
		}
		return(False);
	}
	
}?>