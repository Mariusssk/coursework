<?php

//Error

defined("LANG_404_ERROR") ? null :  define("LANG_404_ERROR", "Error: 404 Page not found");
defined("JS_MISSING_ERROR") ? null :  define("JS_MISSING_ERROR", "This page only works with JavaScript. Please activate JavaScript or use a different browser!");

defined("ERROR_PAGE_LOGIN_NEEDED") ? null :  define("ERROR_PAGE_LOGIN_NEEDED", "You need to sign in before you can view this page!");

//Header


	//Menu
	
	defined("HEADER_MENU_USER_PROFILE") ? null :  define("HEADER_MENU_USER_PROFILE", "Profile");
	defined("HEADER_MENU_USER_LOGOUT") ? null :  define("HEADER_MENU_USER_LOGOUT", "Logout");
	
	//Menu Items
	
		//Storage
			defined("HEADER_MENU_ITEM_STORAGE_CREATE") ? null :  define("HEADER_MENU_ITEM_STORAGE_CREATE", "Create new");
			defined("HEADER_MENU_ITEM_STORAGE_OVERVIEW") ? null :  define("HEADER_MENU_ITEM_STORAGE_OVERVIEW", "Overview");
	
	
	
	//Overlay Login
	
	defined("HEADER_OVERLAY_SESSION_ENDED") ? null :  define("HEADER_OVERLAY_SESSION_ENDED", "Your session has ended.");
	
//User

	//Login
		
	defined("USER_USERNAME") ? null :  define("USER_USERNAME", "Username/E-Mail");
	defined("USER_PASSWORD") ? null :  define("USER_PASSWORD", "Password");
	defined("USER_LOGIN_BTN") ? null :  define("USER_LOGIN_BTN", "Login");
	
	//Logout
	
	defined("USER_LOGOUT_BTN") ? null :  define("USER_LOGOUT_BTN", "Logout");
	defined("USER_LOGED_IN_STATUS") ? null :  define("USER_LOGED_IN_STATUS", "Your are logged in");
	
	//Rights missing
	
	defined("USER_RIGHTS_MISSING_HEADER") ? null :  define("USER_RIGHTS_MISSING_HEADER", "You are not having sufficient rights!");
	defined("USER_RIGHTS_MISSING_INFO") ? null :  define("USER_RIGHTS_MISSING_INFO", "You user role does not have the rights to view this page or information. Please contact an admin if you think this is a mistake!");
	
//Storage

	//Overview
	
		//Search
		
		defined("STORAGE_OVERVIEW_SEARCH_NAME") ? null :  define("STORAGE_OVERVIEW_SEARCH_NAME", "Name");
		defined("STORAGE_OVERVIEW_SEARCH_PARENT_NAME") ? null :  define("STORAGE_OVERVIEW_SEARCH_PARENT_NAME", "Parent name");
		defined("STORAGE_OVERVIEW_SEARCH_TYPE") ? null :  define("STORAGE_OVERVIEW_SEARCH_TYPE", "Storage type");
	
	//Edit/Add
	
		defined("STORAGE_PLACEHOLDER_PARENT") ? null :  define("STORAGE_PLACEHOLDER_PARENT", "Parent");
		
//Basic words

	defined("WORD_WELCOME") ? null :  define("WORD_WELCOME", "Welcome");
	defined("WORD_SEARCH") ? null :  define("WORD_SEARCH", "Search");
	defined("WORD_LOADING") ? null :  define("WORD_LOADING", "Loading...");
	defined("WORD_SAVE") ? null :  define("WORD_SAVE", "Save");
	defined("WORD_DELETE") ? null :  define("WORD_DELETE", "Delete");
	defined("WORD_ABORT") ? null :  define("WORD_ABORT", "Abort");
	
//Basic phrase

	
	
?>

