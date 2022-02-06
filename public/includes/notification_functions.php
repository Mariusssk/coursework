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
		//Objective 9.6
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

		//Load all notifications of user
		//Objective 11
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
					
					$attributeTypeID = $notification->getAttributeTypeID();#
					
					//Date posted
					
					$notificationArray['timePosted'] = $notification->getTimePosted("external");
					
					//Attribute Data
					
					if($attributeTypeID == 1) {
						$notificationArray['type'] = "event";
						$event = new Event;
						if($event->loadData($notification->getAttributeID())) {
							$notificationArray['name'] = $event->getName();
							$notificationArray['URL'] = $event->getURL();
						}
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
		//Objective 11
		else if($request == "markNotificationsAsRead") {
			$notifications = Notification::getAllNotificationsForUser($session->getSessionUserID());
			
			$failed = 0;
			
			foreach($notifications as $tmpNotification) {
				$notification = new Notification;
				if(
					!$notification->loadData($tmpNotification) OR
					!$notification->setSeen(1) OR
					!$notification->saveData()
				) {
					$failed += 1;
				}
			}
			
			if($failed == 0) {
				echo "success";
			}
		}
		
		//load all notification requsts for specific user
		//Objectives 11.3
		else if($request == "loadNotificationRequestList") {
			$requests = NotificationRequest::loadRequestsByUserID($session->getSessionUserID());
			
			$post = array();
			$post['requests'] = array();
			
			foreach($requests as $tmpRequest) {
				$request = new NotificationRequest;
				if($request->loadData($tmpRequest)) {
					$tmpArray = array();

					if(
						(
							((isset($_POST['requestTypeID']) AND $_POST['requestTypeID'] != 0 AND $_POST['requestTypeID'] == $request->getAttributeTypeID()) OR
							!isset($_POST['requestTypeID']) OR empty($_POST['requestTypeID']) OR $_POST['requestTypeID'] == 0) AND
							((isset($_POST['attributeName']) AND !empty($_POST['attributeName']) AND strpos(strtoupper($request->getAttributeName()),strtoupper($_POST['attributeName'])) !== False) OR
							!isset($_POST['attributeName']) OR empty($_POST['attributeName']))
						) 
					){
						if($request->getAttributeTypeID() == 3) {
							$tmpArray['type'] = "todoList";
						} else if($request->getAttributeTypeID() == 1){
							$tmpArray['type'] = "event";
						} else {
							$tmpArray['type'] = "";
						}
						
						$tmpArray['requestID'] = $request->getID();
						
						$tmpArray['name'] = $request->getAttributeName();

						$tmpArray['emailUpdate'] = $request->getEmailUpdate();
						
						$tmpArray['dailyUpdate'] = $request->getDailyUpdate();
						
						
						array_push($post['requests'], $tmpArray);
					}
				}
			}
			
			echo json_encode($post);
		}
		
		//change updates of notifications request as email update
		//Objectives 11.4
		else if($request == "changeNotificationsRequestUpdates") {
			$request = new NotificationRequest;
			if(
				isset($_POST['requestID']) AND $request->loadData($_POST['requestID']) AND
				isset($_POST['newState'])
			) {
				if($_POST['changeType'] == "emailUpdate") {
					if($request->updateEmailRequest($_POST['newState'])) {
						echo "success";
					} else {
						echo "error";
					}
				} else if($_POST['changeType'] == "dailyUpdate") {
					if($request->updateDailyRequest($_POST['newState'])) {
						echo "success";
					} else {
						echo "error";
					}
				} else {
					echo "error";
				}
			} else {
				echo "error";
			}
		}
	} 

}


ob_flush();
