<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

ob_start();

include "../../resources/config.php";

if(isset($_POST['requestType']) AND !empty($_POST['requestType'])) {
	$request = $_POST['requestType'];
	
	
	if($session->loggedIn() == True) {
		
		//resend email to confirm email
		
		if($request == "sendConfirmEmail") {
			
			//check which type of email needs to be confirmed
			if(isset($_POST['type']) AND $_POST['type'] == "personalEmail") {
				$emailRequest = new EmailRequest;
				if($emailRequest->createEmailConfirmRequest($session->getSessionUserID(), "email")) {
					echo "success";
				} else {echo "error";}
			} else if(isset($_POST['type']) AND $_POST['type'] == "schoolEmail") {
				$emailRequest = new EmailRequest;
				if($emailRequest->createEmailConfirmRequest($session->getSessionUserID(), "schoolEmail")) {
					echo "success";
				} else {echo "error";}
			} else {
				echo "error";
			}
		}

		//Load all notifications of user_error
		
		else if($request == "loadPersonalNotifications") {
			$notifications = Notification::getAllNotificationsForUser($session->getSessionUserID());
			
			$post = array();
			$post['notifications'] = array();
			
			foreach($notifications as $tmpNotification) {
				$notification = new Notification;
				if($notification->loadData($tmpNotification)) {
					$notificationArray = array();
					
					//seen
					$notificationArray['seen'] = $notification->getSeenAsBoolean();
					
					$attributeTypeID = $notification->getAttributeTypeID();
					
					//Attribute Data
					
					if($attributeTypeID == 1) {
						$notificationArray['type'] = "event";
					} else if($attributeTypeID == 3) {
						$notificationArray['type'] = "todoList";
						$todo = new ToDoList;
						if($todo->loadData($notification->getAttributeID())) {
							$notificationArray['name'] = $todo->getName();
							$notificationArray['URL'] = $todo->getURL();
						}
					}
					
					array_push($post['notifications'], $notificationArray);
				}
			}
			
			echo json_encode($post);
		}
		
		//Mark unread notifications as read
		
		else if($request == "markNotificationsAsRead") {
			$notifications = Notification::getAllNotificationsForUser($session->getSessionUserID());
			
			foreach($notifications as $tmpNotification) {
				$notification = new Notification;
				if(
					$notification->loadData($tmpNotification) AND
					$notification->setSeen(1) AND
					$notification->saveData()
				) {
					echo "success";
				}
			}
		}
	} 
}

ob_flush();
