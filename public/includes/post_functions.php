<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

ob_start();

include "../../resources/config.php";

if(isset($_POST['requestType']) AND !empty($_POST['requestType'])) {
	$request = $_POST['requestType'];
	
	//check status of user login
	
	if($request == "checkUserLoginStatus") {
		$session = new Session;
		if($session->loggedIn() == True) {
			echo "true";
		} else {
			echo "false";
		}
	}
	
	//login user
	
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
	
	else if($request == "logout") {
		$session->logout();
	}
	
	//reset password of user
	
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
	
	else if($request == "setLanguage" AND isset($_POST['lang']) AND !empty($_POST['lang'])) {
		if(strlen($_POST['lang']) == 2) {
			$session->setPreferredLanguage($_POST['lang']);
		}
	}
	
	//load all tags
	
	else if($request == "loadTags") {
		echo json_encode(Tag::getSelect(array("class" => "generalSelect")));
	}
	
	//load comments for specific attribute type and attribute
	
	else if($request == "") {
		if(
			isset($_POST['type']) AND isset($_POST['attributeID'])
		) {
			if($_POST['type'] == "todoList") {
				$type = 3;
			} else {
				$type = 0;
			}
			
			$todo = new ToDoList;
			
			if(
				$type != 0 AND
				(
					($type == 3 AND $todo->loadData($_POST['attributeID']))
				)
			) {
				$comments = Comment::loadCommentsForTypeAndAttribute($type, $_POST['attributeID']);
				
				$post = array();
				$post['comments'] = array();
				
				foreach($comments as $tmpComment) {
					$comment = new Comment;
					if($comment->loadData($tmpComment)) {
						$tmpCommentArray = array();
						
						if($comment->getUserID() == $session->getSessionUserID()) {
							$tmpCommentArray['edit'] = True;
						} else {
							$tmpCommentArray['edit'] = False;
						}
						
						$tmpCommentArray['data'] = $comment->getData();
						$tmpCommentArray['timestamp'] = $comment->getTimestap();
						
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
	
	else if($request == "saveNewComment") {
		$todo = new ToDoList;
		
		$type = 0;

		if(isset($_POST['type'])) {

			if($_POST['type'] == "todoList") {
				$type = 3;
			}
		
		}
		
		if(
			isset($_POST['attributeID']) AND
			(
				($type == 3 AND $todo->loadData($_POST['attributeID']))
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
	
}

ob_flush();
