//save personal data of user

function saveUserPersonalData() {
	
	//select all inputs
	var inputs = document.querySelectorAll(".page.settings.personalData .generalInput, .page.settings.personalData .generalSelect");
	
	var personalData = {};
	
	//run through all inputs
	for(i = 0;i < Object.keys(inputs).length;i++) {
		var inputKey = inputs[i].dataset.inputName;
		personalData[inputKey] = inputs[i].value;
	}
	
	//send data to php
	$.post(INCLUDES+"/settings_functions.php",{
		requestType: "saveUserPersonalData",
		personalData: personalData
	},
	function(data, status){
		var options = "";
		var dataCut = parsePostData(data);
		
		//check if request is valid
		if(dataCut == "error" || dataCut == "") {
			headerNotification(LANG.ERROR_REQUEST_FAILED,"red");
		} else if(dataCut == "success") {
			headerNotification(LANG.PHRASE_SAVED_SUCCESS);
		} else if(dataCut == "dataMissing") {
			headerNotification(LANG.PHRASE_FILL_ALL,"red");
		} else if(dataCut == "usernameAlreadyUsed") {
			headerNotification(LANG.SETTINGS_USER_PERSONAL_DATA_USERNAME_ALREADY_USED,"red");
		} else if(dataCut == "emailAlreadyUsed") {
			headerNotification(LANG.SETTINGS_USER_PERSONAL_DATA_EMAIL_ALREADY_USED,"red");
		} else if(dataCut == "schoolEmailAlreadyUsed") {
			headerNotification(LANG.SETTINGS_USER_PERSONAL_DATA_SCHOOL_EMAIL_ALREADY_USED,"red");
		} else {
			headerNotification(data,"red");
		}
		
	});

}

//verify code of email request 

function verifyEmailRequest() {
	var container = document.querySelector(".page.verify .verifyContainer");
	var code = document.getElementById("verifyCode").value;
	var inputContainer = document.querySelector(".page.verify .inputCodeContainer");
	var passwordResetContainer = document.querySelector(".page.verify .passwordResetContainer");
	
	
	if(code != "") {
		$.post(INCLUDES+"/settings_functions.php",{
			requestType: "verifyCode",
			code: code
		},
		function(data, status){
			var dataCut = parsePostData(data);
			
			//check if request is valid
			if(dataCut == "error" || dataCut == "") {
				headerNotification(LANG.ERROR_REQUEST_FAILED,"red");
			} else if(dataCut == "codeNotFound") {
				document.getElementById("verifyInformation").innerHTML = LANG.SETTINGS_VERIFY_CODE_NOT_VALID;
			} else if(dataCut == "passwordReset") {
				inputContainer.classList.add("none");
				passwordResetContainer.classList.remove("none");
				passwordResetContainer.innerHTML = passwordResetContainer.innerHTML + `
				<input type="hidden" value="`+code+`" id="verifyCode">`;
			} else if(dataCut == "emailVerified") {
				container.innerHTML = LANG.SETTINGS_VERIFY_EMAIL_SUCCESS;
			} else {
				headerNotification(data,"red");
			}
			
		});
	} else {
		headerNotification(LANG.PHRASE_FILL_ALL,"red");
	}
}