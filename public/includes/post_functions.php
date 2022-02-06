<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

ob_start();

include "../../resources/config.php";

if(isset($_POST['requestType']) AND !empty($_POST['requestType'])) {
	$request = $_POST['requestType'];
	
	//check status of user login
	//Objective 3.3
	if($request == "checkUserLoginStatus") {
		$session = new Session;
		if($session->loggedIn() == True) {
			echo "true";
		} else {
			echo "false";
		}
	}
	
	//login user
	//Objective 3.3
	else if($request == "login") {
		if(isset($_POST['username']) AND !empty($_POST['username']) AND isset($_POST['userPassword']) AND !empty($_POST['userPassword'])) {
			if($session->login($_POST['username'],$_POST['userPassword'])) {
				echo "success";
			} else {
				echo "loginWrong";
			}
		}
	}
	
	//logout user
	//Objective 3.3
	else if($request == "logout") {
		$session->logout();
	}
	
	//reset password of user
	//Objective 9.7
	else if($request == "resetPassword") {
		if(isset($_POST['passwordA']) AND !empty($_POST['passwordA']) AND isset($_POST['passwordB']) AND !empty($_POST['passwordB'])) {
			if($_POST['passwordA'] == $_POST['passwordB']) {
				if(User::checkPasswordRequirements($_POST['passwordA'])) {
					$emailRequest = new EmailRequest;
					if(isset($_POST['code']) AND $emailRequest->loadDataByCode($_POST['code']) AND $emailRequest->isValid()) {
						$user = new User;
						if($user->loadData($emailRequest->getUserID()) AND $user->setPassword($_POST['passwordA']) AND $user->saveData())  {
							echo "success";
							$emailRequest->verify();
						}
					}
				} else {
					echo "requirementsFailed";
				}
			}
		}
	}
	
	//request password reset
	//Objective 9.7
	else if($request == "requestPasswordReset") {
		if(isset($_POST['username']) AND !empty($_POST['username'])) {
			$user = new User;
			if($user->loadUserByUsername($_POST['username'])) {
				$emailRequest = new EmailRequest;
				$emailRequest->createPasswordResetRequest($user->getID());
			}
		} else {
			echo "empty";
		}
	}
	
	//set session lang
	//Objective 3.2
	else if($request == "setLanguage" AND isset($_POST['lang']) AND !empty($_POST['lang'])) {
		if(strlen($_POST['lang']) == 2) {
			$session->setPreferredLanguage($_POST['lang']);
		}
	}
	
	//load all tags
	//Objective 8
	else if($request == "loadTags") {
		echo json_encode(Tag::getSelect(array("class" => "generalSelect")));
	}
	
	//load comments for specific attribute type and attribute
	//Objective 6.3/7.5
	else if($request == "loadComments") {
		if(
			isset($_POST['type']) AND isset($_POST['attributeID'])
		) {
			if($_POST['type'] == "todoList") {
				$type = 3;
			} else if($_POST['type'] == "event") {
				$type = 1;
			} else {
				$type = 0;
			}
			
			$todo = new ToDoList;
			$event = new Event;
			
			if(
				$type != 0 AND
				(
					($type == 3 AND $todo->loadData($_POST['attributeID'])) OR 
					($type == 1 AND $event->loadData($_POST['attributeID']))
				)
			) {
				$comments = Comment::loadCommentsForTypeAndAttribute($type, $_POST['attributeID']);
				
				$post = array();
				$post['comments'] = array();
				
				foreach($comments as $tmpComment) {
					$comment = new Comment;
					if($comment->loadData($tmpComment)) {
						$tmpCommentArray = array();
						
						$tmpCommentArray['commentID'] = $comment->getID();
						
						if($comment->getUserID() == $session->getSessionUserID()) {
							$tmpCommentArray['edit'] = True;
						} else {
							$tmpCommentArray['edit'] = False;
						}
						
						$tmpCommentArray['data'] = $comment->getData();
						$tmpCommentArray['timestamp'] = $comment->getTimestamp();
						$tmpCommentArray['username'] = $comment->getUsername();
						
						array_push($post['comments'], $tmpCommentArray);
					}
				}
				
				echo json_encode($post);
			} else {
				echo "error";
			}
		} else {
			echo "error";
		}
	}
	
	//Save new comment
	//Objective 6.3/7.5
	else if($request == "saveNewComment") {
		$todo = new ToDoList;
		$event = new Event;
		
		$type = 0;

		if(isset($_POST['type'])) {

			if($_POST['type'] == "todoList") {
				$type = 3;
			}else if($_POST['type'] == "event") {
				$type = 1;
			}
		
		}
		
		if(
			isset($_POST['attributeID']) AND
			(
				($type == 3 AND $todo->loadData($_POST['attributeID'])) OR 
				($type == 1 AND $event->loadData($_POST['attributeID']))
			) AND
			isset($_POST['comment']) AND !empty($_POST['comment'])
		) {
			$comment = new Comment;
			if($comment->createNewComment($type, $_POST['attributeID'], $_POST['comment'], $session->getSessionUserID())) {
				echo "success";
			} else {
				echo "error";
			}
		} else {
			echo "error";
		}
	}
	
	//Save edited comment
	//Objective 6.3/7.5
	else if($request == "saveEditedComment") {
		$comment = new Comment;
		if(
			isset($_POST['commentID']) AND $comment->loadData($_POST['commentID']) AND
			isset($_POST['comment']) AND !empty($_POST['comment'])
		) {
			if($comment->getUserID() == $session->getSessionUserID()) {
				$comment->setData($_POST['comment']);
				$post = array();
				$post['attributeID'] = $comment->getAttributeID();
				
				if($comment->getTypeID() == 3) {
					$post['type'] = "todoList";
				} else if($comment->getTypeID() == 1) {
					$post['type'] = "event";
				}
				
				if($comment->saveData()) {
					echo json_encode($post);
				} else {
					echo "error";
				}
			} else {
				echo "missingRights";
			}
		} else {
			echo "error";
		}
	}
	
	//toggle notifications for comments on specific attribute
	//Objective 11.1
	else if($request == "toggleCommentNotifications") {
		$todo = new ToDoList;
		$event = new Event;
		
		$type = 0;

		if(isset($_POST['type'])) {

			if($_POST['type'] == "todoList") {
				$type = 3;
			} else if($_POST['type'] == "event") {
				$type = 1;
			}
		
		}
		if(
			isset($_POST['attributeID']) AND
			(
				($type == 3 AND $todo->loadData($_POST['attributeID'])) OR 
				($type == 1 AND $event->loadData($_POST['attributeID']))
			)
		) {
			$currentState = NotificationRequest::checkIfRequestActivated($session->getSessionUserID(), $type, $_POST['attributeID']);
			
			$newState = filter_var($_POST['newState'], FILTER_VALIDATE_BOOLEAN);
			
			//Check if new state is transmitted
			
			$result = "";
			
			if(isset($_POST['newState']) AND !empty($_POST['newState'])) {
				if($newState == $currentState) {
					$result = True;
				} else if($newState == True) {
					$result = NotificationRequest::createRequest($session->getSessionUserID(), $type, $_POST['attributeID']);
				} else if($newState == False) {
					$result = NotificationRequest::deleteRequest($session->getSessionUserID(), $type, $_POST['attributeID']);
				}
			} 
			
			//Otherwise tooggle state
			
			else if($currentState == True) {
				$result = NotificationRequest::deleteRequest($session->getSessionUserID(), $type, $_POST['attributeID']);
			} else if($currentState == False) {
				$result = NotificationRequest::createRequest($session->getSessionUserID(), $type, $_POST['attributeID']);
			}
			
			if($result == True) {
				echo "success";
			} else {
				echo "error";
			}
			
		} else {
			echo "error";
		}
	}
	
	
	//decode data of qr-code scanner
	//Objective 4.3/5.4
	else if($request == "decodeScanData") {
		if(isset($_POST['data']) AND !empty($_POST['data'])) {
			$data = $_POST['data'];
			$post = array();
			
			//Turn URL into decodeable string
			if(substr($data,0,4) == "http") {
				$data = substr($data, (strpos($data, "scan/") + 5));
				$data = str_replace("/","#",$data);
			}
			
			//decode data
			$data = explode("#",$data);
		
			$post = array();
			
			if(count($data) == 2) {
				$type = strtolower($data[0]);
				$identifier = $data[1];
				if($type == "item") {
					$item = new Item;
					if($item->loadData($identifier)) {
						$post['result'] = "success";
						$post['action'] = "redirect";
						$post['URL'] = URL."/scan/display/".$type."/".$item->getID();
					}
				} else if($type == "storage") {
					$storage = new Storage;
					if($storage->loadData($identifier)) {
						$post['result'] = "success";
						$post['action'] = "redirect";
						$post['URL'] = URL."/scan/display/".$type."/".$storage->getID();
					}
				}
			} 
			
			if(!isset($post['result']) OR empty($post['result'])) {
				$post['result'] = "error";
			}
			
			
			echo json_encode($post);
		} else {
			echo "error";
		}
	}
	
}

ob_flush();
