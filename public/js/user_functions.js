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
	var username = document.querySelector('.loginFormOverlay .generalDataInput[data-input-name="username"]').value;
	var userPassword = document.querySelector('.loginFormOverlay .generalDataInput[data-input-name="password"]').value;
	if(username == "" || userPassword == "") {
		headerNotification(LANG.USER_LOGIN_FILL_ALL,"red");
	} else {
		$.post(INCLUDES+"/post_functions.php",{
		requestType: "login",
		username: username,
		userPassword: userPassword
		},
		function(data, status){
			var notice = "";
			var dataCut = parsePostData(data);
			if(dataCut == "success") {
				setLoginOverlay(true);
			} else if(dataCut == "loginWrong") {
				notice = LANG.USER_LOGIN_WRONG+ ' <a href="'+URL+'">Reset password </a>';
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
	
	$.post(INCLUDES+"/post_functions.php",{
		requestType: "logout"
	},
	function(data, status){
		setLoginOverlay(true);
	});
	
}


//Check if user logged-in on protected page

function setLoginOverlay(redirectLogin = false) {
	$.post(INCLUDES+"/post_functions.php",{
		requestType: "checkUserLoginStatus"
	},
	function(data, status){
		var dataCut = parsePostData(data);
		if (typeof protectedPage !== 'undefined' && protectedPage == true) {
			if(dataCut == "false") {
				document.querySelector(".mainContainer .headerLoginOverlay").classList.remove("none");
			} else {
				document.querySelector(".mainContainer .headerLoginOverlay").classList.add("none");
			}
		} else if(redirectLogin == true) {
			if(dataCut == "true") {
				redirect(URL);
			} else {
				redirect(URL+"/login");
			}
		}
	});
}

	