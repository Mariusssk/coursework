<?php
//-----------------CRON JOB File---------------------
//This file is exectuded every 5 minutes to send out the notification emails
//-----------------CRON JOB File---------------------

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include __DIR__ . "/../config.php";

//Checks for unseen notifications and sends out emails if requested by the user
//Objective 10.3

$now = new DateTime();

$dailyEmail = False;

if($now->format("H") == "18") {
	$dailyEmail = True;
}


$users = User::getAll();


foreach($users as $tmpUser) {
	$user = new User;
	if($user->loadData($tmpUser)) {
		
		$lang = "EN";
		
		if(strtoupper($user->getPreferredLanguage()) == "DE") {
			$lang = "DE";
		}
		
		$notifications = Notification::getAllNotificationsForUser($user->getID(),True);
		
		$userNotificationString = "";
		
		print_r($notifications);

		foreach($notifications as $tmpNotification) {
			$notification = new Notification;
			$request = new NotificationRequest;
			
			//check if user selected to get e-mail updates
			
			if(
				$notification->loadData($tmpNotification) AND $request->loadData($notification->getRequestID()) AND
				(
					$notification->getEmailSent() == 0 AND $request->getEmailUpdate() == 1 AND
					(($request->getDailyUpdate() == 1 AND $dailyEmail == True) OR
					$request->getDailyUpdate() == 0)
				)
			) {
				
				$notificationText = $notification->getTimePosted("external").": ";
				
				//Set email text based on if it is event or todo list
				
				if($request->getAttributeTypeID() == 1) {
					$event = new Event;
					if($event->loadData($request->getAttributeID())) {
						if($lang == "DE") {
							$notificationText .= "Es gibt einen neuen Kommentar für das Event: ";
						} else {
							$notificationText .= "There is a new comment for the event: ";
						}
						$notificationText .= $event->getName();
					}
				}else if($request->getAttributeTypeID() == 3) {
					$todo = new ToDoList;
					if($todo->loadData($request->getAttributeID())) {
						if($lang == "DE") {
							$notificationText .= "Es gibt einen neuen Kommentar für die ToDo-Liste: ";
						} else {
							$notificationText .= "There is a new comment for the todo list: ";
						}
						$notificationText .= $todo->getName();
					}
				} 
				
				if(strlen($notificationText) > 0) {
					$userNotificationString .= '
					<p class="notification">
						<span class="notificationText">'.$notificationText.'</span>
					</p>
					';
				}
				
				$notification->updateEmailSent(1);
			}
		}
		
		//Send email

		if(strlen($userNotificationString) > 0) {
			$email = new Email;
			$email->createEmail("notification",$user->getID(),array("notificationText" => $userNotificationString));
		}
	}
}
?>