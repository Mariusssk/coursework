
//Basic standard functions
	
	//cut down return from post function
	
	function parsePostData(data) {
		return(data.replace(/(\r\n|\n|\r)/gm,"").replace(" ",""));
	}
	
	//redirect to new URL
	
	function redirect(tmpURL = "") {
		window.location.href = tmpURL;
	}
	
	//reload page
	
	function reload() {
		location.reload();
	}
	
//overlay

function closeOverlay(overlay) {
	overlay.closest(".overlayContainer").classList.add("none");
}

//Header notification

	//set timeout

	var notificationTime = window.setTimeout(function(){hideHeaderNotification()},1);


	//Display header notification

	function headerNotification(notification, colour = "green", linkData = "", linkURL = "#") {
		hideHeaderNotification()
		if(document.getElementById("headerNotice").classList.contains("headerNoticeHidden")) {
			document.getElementById("headerNotice").classList.remove("headerNoticeHidden");
		}
		
		//Set colour
		
		if(colour == "green") {
			document.getElementById("headerNotice").classList.add(colour);
		} else if(colour == "red") {
			document.getElementById("headerNotice").classList.add(colour);
		}
		
		//set link
		
		document.getElementById("headerNoticeMessageText").innerHTML = notification;
		if(linkData != "" && linkURL != "") {
			document.getElementById("headerNoticeMessageLink").innerHTML = linkData;
			document.getElementById("headerNoticeMessageLink").href = linkURL;
		} else {
			document.getElementById("headerNoticeMessageLink").innerHTML = "";
		}
		
		window.clearTimeout(notificationTime);
		notificationTime = window.setTimeout(function(){hideHeaderNotification()},4000);
	}

	//Hide header notification

	function hideHeaderNotification() {
		if(document.getElementById("headerNotice")) {
			document.getElementById("headerNotice").classList.add("headerNoticeHidden");
			if(document.getElementById("headerNotice").classList.contains("green")) {
				document.getElementById("headerNotice").classList.remove("green");
			} else if(document.getElementById("headerNotice").classList.contains("red")) {
				document.getElementById("headerNotice").classList.remove("red");
			}
		}
	}

//Menu

	//Open mobile menu

	function mobileMenu() {
		document.querySelector(".menuContainer .mobileMenuContainer").classList.toggle("none");
	}

	//Open sub menu

	function subMenu(menuID) {
		document.querySelector('.menuContainer .mobileMenuContainer .subMenu[data-sub-menu="'+menuID+'"]').classList.toggle("none");
		document.querySelector('.menuContainer .mobileMenuContainer .fa-caret-down[data-sub-menu-button="'+menuID+'"]').classList.toggle("subMenuOpen");
	}

	function desktopSubMenu() {
		var selected = document.querySelectorAll('.menuContainer .desktopMenuContainer .menuElement.selected');
		
		for(i = 0;i < Object.keys(selected).length; i++) {
			if(selected[i].hasAttribute("data-sub-menu-parent")) {
				var subMenuId = selected[i].dataset.subMenuParent;
				document.querySelector('.menuContainer .desktopMenuContainer .subMenu[data-sub-menu="'+subMenuId+'"]').classList.toggle("subMenuOpen");
			}
		}
	}
	
	//set which menu option slected
	
	function setMenuSelected(tmpURL) {
		var selectedOptions = document.querySelectorAll('.menuContainer .menuElement[data-destination="'+tmpURL+'"]');
		for(i = 0;i < Object.keys(selectedOptions).length; i++) {
			selectedOptions[i].classList.add("selected");
		}
		
		tmpURL = tmpURL.substring(0, tmpURL.indexOf('/'));
		var selectedOptions = document.querySelectorAll('.menuContainer .menuElement[data-destination="'+tmpURL+'"]:not(.subElement)');
		for(i = 0;i < Object.keys(selectedOptions).length; i++) {
			selectedOptions[i].classList.add("selected");
		}
		
		desktopSubMenu();
	}
	
//Run when loaded
let code = ""
let reading = ""
$(document).ready(function() {
	document.addEventListener('keypress', e=>{
		if (e.keyCode===13 && code.toUpperCase().includes("BANANA")){
		   alert("David Nooooo!");
		   code = "";
		}else{
			code+=e.key;         
		}
		if(!reading){
			reading=true;
			setTimeout(()=>{
				code="";
				reading=false;
			}, 10000);
		}
	});
});




