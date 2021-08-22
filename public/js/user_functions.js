//Run on load

$(document).ready(function() {
	$(".loginFormOverlay .generalDataInput").on('keyup', function (e) {
		if (e.key === 'Enter' || e.keyCode === 13) {
			submitLogin();
		}
	});
	
	if (typeof protectedPage !== 'undefined' && protectedPage == true) {
		setLoginOverlay();
		setInterval(function(){ 
			setLoginOverlay();
		}, 2000);
	}
});

//login user

function submitLogin() {
	//get form data
	var username = document.querySelector('.loginFormOverlay .generalDataInput[data-input-name="username"]').value;
	var userPassword = document.querySelector('.loginFormOverlay .generalDataInput[data-input-name="password"]').value;
	
	//check if all data is submitted
	if(username == "" || userPassword == "") {
		headerNotification(LANG.USER_LOGIN_FILL_ALL,"red");
	} else {
		//send data to PHP
		$.post(INCLUDES+"/post_functions.php",{
		requestType: "login",
		username: username,
		userPassword: userPassword
		},
		function(data, status){
			var notice = "";
			var dataCut = parsePostData(data);
			//check return from PHP
			if(dataCut == "success") {
				setLoginOverlay(true);
			} else if(dataCut == "loginWrong") {
				notice = LANG.USER_LOGIN_WRONG+ ' <div onlick="passwordResetForm()" class="resetPasswordBtn"> Reset password </div>';
			} else if(dataCut == "error" || data == ""){
				notice = LANG.USER_LOGIN_FAIL;
			} else {
				headerNotification(data,"red");
			}
			if(notice != "") {
				var loginNotices = document.querySelectorAll(".loginNotice");
				for(i = 0; i < Object.keys(loginNotices).length; i++) {
					loginNotices[i].innerHTML = notice;
				}
			}
		});
	}
}

//login user

function submitLogout() {
	//send new lang to php
	$.post(INCLUDES+"/post_functions.php",{
		requestType: "logout"
	},
	function(data, status){
		setLoginOverlay(true);
	});
}

//Change session language

function changeLang(lang) {
	//send lang to php
	$.post(INCLUDES+"/post_functions.php",{
		requestType: "setLanguage",
		lang: lang
	},
	function(data, status){
		//reload page with new lang
		location.reload();
	});
}

//Check if user logged-in on protected page

function setLoginOverlay(redirectLogin = false) {
	//request login status from php
	$.post(INCLUDES+"/post_functions.php",{
		requestType: "checkUserLoginStatus"
	},
	function(data, status){
		var dataCut = parsePostData(data);
		if (typeof protectedPage !== 'undefined' && protectedPage == true) {
			//display login form if not logged in
			if(dataCut == "false") {
				document.querySelector(".mainContainer .headerLoginOverlay").classList.remove("none");
			} else {
				document.querySelector(".mainContainer .headerLoginOverlay").classList.add("none");
			}
		} else if(redirectLogin == true) {
			//redirect to login if not logged in
			if(dataCut == "true") {
				redirect(URL);
			} else {
				redirect(URL+"/login");
			}
		}
	});
}

//reset password 
function resetPassword() {
	var passwordInputs = document.querySelectorAll(".page .passwordResetContainer .generalInput");
	var passwordA = "";
	var passwordB = "";
	
	var passwordResetContainer = document.querySelector(".page .passwordResetContainer");
	
	var code = "";
	var codeContainer = document.getElementById("verifyCode");
	if(codeContainer) {
		code = codeContainer.value;
	}
	
	for(i = 0;i < Object.keys(passwordInputs).length;i++) {
		//alert(passwordInputs[i].dataset.inputName);
		if(passwordInputs[i].dataset.inputName == "password") {
			passwordA = passwordInputs[i].value;
		} else if(passwordInputs[i].dataset.inputName == "passwordRepeat") {
			passwordB = passwordInputs[i].value;
		}
	}
	
	if(passwordA == "" || passwordB == "") {
		headerNotification(LANG.PHRASE_FILL_ALL,"red");
	} else if(passwordA != passwordB){
		headerNotification(LANG.USER_PASSWORD_RESET_NOT_EQUAL,"red");
	} else {
		//send request to php
		$.post(INCLUDES+"/post_functions.php",{
			requestType: "resetPassword",
			passwordA: passwordA,
			passwordB: passwordB,
			code: code
		},
		function(data, status){
			//get return from PHP
			var dataCut = parsePostData(data);
			
			//check if request is valid
			if(dataCut == "error" || dataCut == "") {
				headerNotification(LANG.ERROR_REQUEST_FAILED,"red");
			} else if(dataCut == "requirementsFailed") {
				var requirementsContainer = document.querySelector(".page .passwordResetContainer .passwordRequirements");
				if(requirementsContainer.classList.contains("none")) {
					requirementsContainer.classList.remove("none");
				}
			} else if(dataCut == "success") {
				passwordResetContainer.innerHTML = LANG.USER_PASSWORD_CHANGE_SUCCESS+'<a href="'+URL+'/login" class="generalButton">'+LANG.WORD_LOGIN+'</a>';
			} else {
				headerNotification(data,"red");
			}
		});
	}
}

	