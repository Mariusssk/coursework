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
				notice = LANG.USER_LOGIN_WRONG+ ' <div onclick="passwordResetForm()" class="resetPasswordBtn"> Reset password </div>';
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

//display password reset request form

function passwordResetForm() {

	var loginContainers = document.querySelectorAll(".loginForm");
	var passwordResetContainers = document.querySelectorAll(".passwordResetRequest");

	//Hide login form
	for(i = 0; i < Object.keys(loginContainers).length; i++) {
		loginContainers[i].classList.add("none");
	}
	
	//Display password reset request form
	for(i = 0; i < Object.keys(passwordResetContainers).length; i++) {
		passwordResetContainers[i].classList.remove("none");
	}
	
}

//request password reset 

function requestPasswordReset(e) {
	var resetContainer = e.parentNode;
	var username = resetContainer.querySelector("input[data-input-name='resetUsername']").value;
	
	$.post(INCLUDES+"/post_functions.php",{
		requestType: "requestPasswordReset",
		username: username
	},
	function(data, status){
		var dataCut = parsePostData(data);
		if(dataCut == "empty") {
			headerNotification(LANG.USER_PASSWORD_RESET_REQUEST_EMPTY,"red");
		} else {
			resetContainer.innerHTML = LANG.USER_PASSWORD_RESET_REQUEST_SUCCESS + '<div class="generalButton" onclick="reload()"> '+LANG.WORD_LOGIN+' </div>';
		}
		
	});
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


//Load list of all user roles

function loadRoles() {
	var name = document.querySelector('.page.role.roleList .searchInput[data-search-name="roleName"]').value;

	
	$.post(INCLUDES+"/user_functions.php",{
		requestType: "loadRoleList",
		name: name
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
			
			roles = data['roles'];
			
			var roleString = "";

			//run trough every tag send back by php
			
			for(i = 0;i < Object.keys(roles).length;i++) {
				var tmpRole = "";
				
				//create tag html elment
				tmpRole += `<div class="row tagContainer" data-request-id="`+roles[i]['ID']+`">`;
				
				
				//Name
				
				tmpRole += `<div class="tagName td col-sm-6 col-7">`+roles[i]['name']+`</div>`;
				
				//Pre defined
				
				if(roles[i]['preDefined'] == true) {
					tmpRole += `
					<div class="tagName td col-sm-4 col-5"><span class="icon generalIcon"><i class="fa fa-check-square" aria-hidden="true"></i></span></div>
					<div class="td col-sm-2 col-12">
					</div>
					`;
				} else {
					tmpRole += `
					<div class="tagName td col-sm-4 col-5"></div>
					<div class="td col-sm-2 col-12 tagActions noselect">
						<span class="editRole icon generalIcon" onclick="editRole('`+roles[i]['ID']+`')">
							<i class="fa fa-pencil-square" aria-hidden="true"></i>
						</span>
					</div>
					`;
				}

				tmpRole += `</div>`;
				
				roleString += tmpRole;
				
			}
			
			//check if there are any roles
			
			if(roleString.length == 0) {
				roleString = LANG.TAG_LIST_NON_FOUND
			} 
			
			document.querySelector(".page.role.roleList .roleListContainer").innerHTML = roleString;
			
		}
		
	});
}

//redirect to edit role page

function editRole(roleID) {
	redirect(URL+"/settings/user/roles/edit/"+roleID);
}

//Create new template of role 

function createNewRole() {
	$.post(INCLUDES+"/user_functions.php",{
		requestType: "createNewRole"
	},
	function(data, status){
		//get return from PHP
		var dataCut = parsePostData(data);
		
		//check if request is valid
		if(dataCut == "error" || dataCut == "") {
			headerNotification(LANG.ERROR_REQUEST_FAILED,"red");
		} else if(dataCut == "success") {
			headerNotification(LANG.PHRASE_SAVED_SUCCESS,"green");
			loadRoles();
		} else {
			headerNotification(data,"red");
		}
	});
}


	