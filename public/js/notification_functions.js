//resend email to confirm email

function sendConfirmEmail(type) {
	$.post(INCLUDES+"/notification_functions.php",{
		requestType: "sendConfirmEmail",
		type: type
	},
	function(data, status){
		var dataCut = parsePostData(data);
		
		//check if request is valid
		if(dataCut == "error" || dataCut == "") {
			headerNotification(LANG.ERROR_REQUEST_FAILED,"red");
		} else if(dataCut == "success") {
			headerNotification(LANG.NOTIFICATIONS_CONFIRM_EMAIL_REQUEST_SUCCESS,"green");
		} else {
			headerNotification(data,"red");
		}
		
	});
}

//Load all personal notifications of userAgent

function loadPersonalNotifications() {
	$.post(INCLUDES+"/notification_functions.php",{
		requestType: "loadPersonalNotifications"
	},
	function(data, status){
		var dataCut = parsePostData(data);
		
		//check if request is valid
		if(dataCut == "error" || dataCut == "") {
			headerNotification(LANG.ERROR_REQUEST_FAILED,"red");
		} else if(dataCut == "success") {
			headerNotification(LANG.NOTIFICATIONS_CONFIRM_EMAIL_REQUEST_SUCCESS,"green");
		} else {
			data = JSON.parse(data);
			
			notifications = data['notifications'];
			
			var notificationsString = "";
			
			for(i = 0;i < Object.keys(notifications).length;i++) {
				var tmpNotification = "";
				
				var seen = "";
				
				if(notifications[i]['seen'] == false) {
					seen = "unread";
				}
				
				tmpNotification += '<div class="col-12 notificationContainer '+seen+'">';

				if(notifications[i]['type'] == "todoList"){
					tmpNotification += LANG.NOTIFICATIONS_PERSONAL_NEW_COMMENT_TODO_LIST
				} else if(notifications[i]['type'] == "event") {
					tmpNotification += LANG.NOTIFICATIONS_PERSONAL_NEW_COMMENT_EVENT
				}

				if(typeof notifications[i]['URL'] !== "undefined" && notifications[i]['URL'] != "") {
					tmpNotification += ` <a href="`+notifications[i]['URL']+`">`+notifications[i]['name']+`</a>`;
				} else {
					tmpNotification += ` `+notifications[i]['name'];
				}
					
				tmpNotification += '</div>';
				
				notificationsString += tmpNotification;
			}
			
			if(notificationsString.length == 0) {
				notificationsString = LANG.NOTIFICATIONS_NON_FOUND
			} 
			
			document.querySelector(".page.notifications.list .personalNotifications").innerHTML = notificationsString;
		}
		
	});
}

//Mark the notifications as read

function markNotificationsAsRead() {
	$.post(INCLUDES+"/notification_functions.php",{
		requestType: "markNotificationsAsRead"
	},
	function(data, status){
		var dataCut = parsePostData(data);
		
		//check if request is valid
		if(dataCut == "error" || dataCut == "") {
			headerNotification(LANG.ERROR_REQUEST_FAILED,"red");
		} else if(dataCut == "success") {
			headerNotification(LANG.PHRASE_REQUEST_SUCCSESSFUL,"green");
		} else {
			headerNotification(data,"red");
		}
		
	});
}