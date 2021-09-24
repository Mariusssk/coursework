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