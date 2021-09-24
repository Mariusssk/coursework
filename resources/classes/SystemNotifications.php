<?php

class SystemNotifications extends SystemClass {
	
	protected $notificationsList = array();
	
	function __construct($userID) {
		//load all notifications
		$this->loadNotifications($userID);
	}
	
	protected function loadNotifications($userID) {
		//load items lend by user
		$lendItems = Lend::getAllLendByUser($userID);
		
		foreach($lendItems as $tmpLendItem) {
			$lend = new Lend;
			if($lend->loadData($tmpLendItem)) {
				if($lend->getDaysUntilReturn() !== False AND $lend->getDaysUntilReturn() < 3) {
					$notification = array("type" => "lendReturn", "dueDays" => $lend->getDaysUntilReturn(), "itemName" => $lend->getItemName());
					array_push($this->notificationsList, $notification);
				}
			}
		}
		
		//open email request
		
		if(EmailRequest::checkOpenRequest($userID, "personalEmail")) {
			$notification = array("type" => "emailRequest", "requestType" => "personalEmail");
			array_push($this->notificationsList, $notification);
		}
		
		if(EmailRequest::checkOpenRequest($userID, "schoolEmail")) {
			$notification = array("type" => "emailRequest", "requestType" => "schoolEmail");
			array_push($this->notificationsList, $notification);
		}
	}
	
	function countNotifications() {
		return(count($this->notificationsList));
	}
	
	function displayNotifications() {
		$post = "";
		foreach($this->notificationsList as $tmpNotification) {
			$post .= '<div class="col-12 notificationContainer">';
			if($tmpNotification['type'] == "lendReturn") {
				if($tmpNotification['dueDays'] < 0) {
					$tmpNotification['dueDays'] = $tmpNotification['dueDays'] * -1;
					$post .= SYSTEM_NOTIFICATIONS_LEND_OVERDUE_A." ".$tmpNotification['itemName']." ".SYSTEM_NOTIFICATIONS_LEND_OVERDUE_B." ".$tmpNotification['dueDays']." ".SYSTEM_NOTIFICATIONS_LEND_OVERDUE_C;
				} else if($tmpNotification['dueDays'] == 0) {
					$post .= SYSTEM_NOTIFICATIONS_LEND_DUE_TODAY_A." ".$tmpNotification['itemName']." ".SYSTEM_NOTIFICATIONS_LEND_DUE_TODAY_B;
				} else if($tmpNotification['dueDays'] > 0) {
					$post .= SYSTEM_NOTIFICATIONS_LEND_DUE_A." ".$tmpNotification['itemName']." ".SYSTEM_NOTIFICATIONS_LEND_DUE_B." ".$tmpNotification['dueDays']." ".SYSTEM_NOTIFICATIONS_LEND_DUE_C;
				}
				
			} else if($tmpNotification['type'] == "emailRequest") {
				$post .= SYSTEM_NOTIFICATIONS_CONFIRM_EMAIL_A;
				if($tmpNotification['requestType'] == "personalEmail") {
					$post .= " ".SYSTEM_NOTIFICATIONS_CONFIRM_PERSONAL_EMAIL_A."!";
					$post .= ' <span class="onclickText" onclick="sendConfirmEmail('."'personalEmail'".')">';
				} else if($tmpNotification['requestType'] == "schoolEmail") {
					$post .= " ".SYSTEM_NOTIFICATIONS_CONFIRM_SCHOOL_EMAIL_A."!";
					$post .= ' <span class="onclickText" onclick="sendConfirmEmail('."'schoolEmail'".')">';
				}
				$post .= SYSTEM_NOTIFICATIONS_CONFIRM_EMAIL_B;
				$post .= "</span>";
				
			}
			$post .= "</div>";
		}
		return($post);
	}
	
	
}