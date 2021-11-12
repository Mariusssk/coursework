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
			
		//Item
			defined("HEADER_MENU_ITEM_ITEM_MAIN") ? null :  define("HEADER_MENU_ITEM_ITEM_MAIN", "Item");
			defined("HEADER_MENU_ITEM_ITEM_CONSUMABLE") ? null :  define("HEADER_MENU_ITEM_ITEM_CONSUMABLE", "Consumables");
			defined("HEADER_MENU_ITEM_ITEM_CREATE") ? null :  define("HEADER_MENU_ITEM_ITEM_CREATE", "Create new");
			defined("HEADER_MENU_ITEM_ITEM_OVERVIEW") ? null :  define("HEADER_MENU_ITEM_ITEM_OVERVIEW", "Overview");
			defined("HEADER_MENU_ITEM_ITEM_LEND") ? null :  define("HEADER_MENU_ITEM_ITEM_LEND", "Lended");
			
		//To-Do
			defined("HEADER_MENU_ITEM_TODO_CREATE") ? null :  define("HEADER_MENU_ITEM_TODO_CREATE", "Create new");
			defined("HEADER_MENU_ITEM_TODO_CATEGORY") ? null :  define("HEADER_MENU_ITEM_TODO_CATEGORY", "Categories");
			defined("HEADER_MENU_ITEM_TODO_PUBLIC") ? null :  define("HEADER_MENU_ITEM_TODO_PUBLIC", "Group Lists");
			defined("HEADER_MENU_ITEM_TODO_PERSONAL") ? null :  define("HEADER_MENU_ITEM_TODO_PERSONAL", "Own Lists");
			
		//Events
			defined("HEADER_MENU_ITEM_EVENT_CREATE") ? null :  define("HEADER_MENU_ITEM_EVENT_CREATE", "Create new");
			defined("HEADER_MENU_ITEM_EVENT_OVERVIEW") ? null :  define("HEADER_MENU_ITEM_EVENT_OVERVIEW", "Overview");
			defined("HEADER_MENU_ITEM_EVENT_LOCATIONS") ? null :  define("HEADER_MENU_ITEM_EVENT_LOCATIONS", "Locations");
	
		//Settings
			defined("HEADER_MENU_ITEM_SETTINGS_MAIN") ? null :  define("HEADER_MENU_ITEM_SETTINGS_MAIN", "Settings");
			defined("HEADER_MENU_ITEM_SETTINGS_USER") ? null :  define("HEADER_MENU_ITEM_SETTINGS_USER", "Personal data");
			defined("HEADER_MENU_ITEM_SETTINGS_NOTIFICATIONS") ? null :  define("HEADER_MENU_ITEM_SETTINGS_NOTIFICATIONS", "Notifications");
			defined("HEADER_MENU_ITEM_SETTINGS_CREATE_USER") ? null :  define("HEADER_MENU_ITEM_SETTINGS_CREATE_USER", "Create User");
			defined("HEADER_MENU_ITEM_SETTINGS_USER_OVERVIEW") ? null :  define("HEADER_MENU_ITEM_SETTINGS_USER_OVERVIEW", "List User");
			defined("HEADER_MENU_ITEM_SETTINGS_USER_ROLE") ? null :  define("HEADER_MENU_ITEM_SETTINGS_USER_ROLE", "User roles");
	
	//Overlay Login
	
	defined("HEADER_OVERLAY_SESSION_ENDED") ? null :  define("HEADER_OVERLAY_SESSION_ENDED", "Your session has ended.");
	
//User

	//Login/Logout
		
	defined("USER_USERNAME") ? null :  define("USER_USERNAME", "Username/E-Mail");
	defined("USER_PASSWORD") ? null :  define("USER_PASSWORD", "Password");
	defined("USER_REPEAT_PASSWORD") ? null :  define("USER_REPEAT_PASSWORD", "repeat Password");
	defined("USER_LOGIN_BTN") ? null :  define("USER_LOGIN_BTN", "Login");
	
	defined("USER_RESET_PASSWORD_BTN") ? null :  define("USER_RESET_PASSWORD_BTN", "Reset Password");

	defined("USER_LOGOUT_BTN") ? null :  define("USER_LOGOUT_BTN", "Logout");
	defined("USER_LOGED_IN_STATUS") ? null :  define("USER_LOGED_IN_STATUS", "Your are logged in");
	
	defined("USER_RESET_PASSWORD_BTN") ? null :  define("USER_RESET_PASSWORD_BTN", "Reset Password");
	
	//Rights missing
	
	defined("USER_RIGHTS_MISSING_HEADER") ? null :  define("USER_RIGHTS_MISSING_HEADER", "You are not having sufficient rights!");
	defined("USER_RIGHTS_MISSING_INFO") ? null :  define("USER_RIGHTS_MISSING_INFO", "You user role does not have the rights to view this page or information. Please contact an admin if you think this is a mistake!");
	
	
	//Password requirements
	defined("USER_PASSWORD_REQUIREMENTS_LENGTH") ? null :  define("USER_PASSWORD_REQUIREMENTS_LENGTH", "minimum 8 and maximum 20 characters");
	defined("USER_PASSWORD_REQUIREMENTS_NUMBER") ? null :  define("USER_PASSWORD_REQUIREMENTS_NUMBER", "at least one number");
	defined("USER_PASSWORD_REQUIREMENTS_LOWERCASE") ? null :  define("USER_PASSWORD_REQUIREMENTS_LOWERCASE", "at least one lowercase letter");
	defined("USER_PASSWORD_REQUIREMENTS_UPPERCASE") ? null :  define("USER_PASSWORD_REQUIREMENTS_UPPERCASE", "at least one uppercase letter");
	defined("USER_PASSWORD_REQUIREMENTS_SPECIAL_CHAR_A") ? null :  define("USER_PASSWORD_REQUIREMENTS_SPECIAL_CHAR_A", "at least one special character");
	defined("USER_PASSWORD_REQUIREMENTS_SPECIAL_CHAR_B") ? null :  define("USER_PASSWORD_REQUIREMENTS_SPECIAL_CHAR_B", "allowed special characters: !?@#$%&*");
	
	
	//Profile
	
	defined("USER_PROFILE_NOT_FOUND") ? null :  define("USER_PROFILE_NOT_FOUND", "User Profile not found!");
	defined("USER_PROFILE_REGISTERED_SINCE") ? null :  define("USER_PROFILE_REGISTERED_SINCE", "Registered since");
	
	//Notifications
	
	defined("USER_NOTIFICATIONS_HEADLINE") ? null : define("USER_NOTIFICATIONS_HEADLINE", "Notifications");
	
	defined("USER_NOTIFICATIONS_SUB_HEADLINE_SYSTEM") ? null : define("USER_NOTIFICATIONS_SUB_HEADLINE_SYSTEM", "System Notifications");
	defined("USER_NOTIFICATIONS_SUB_HEADLINE_USER") ? null : define("USER_NOTIFICATIONS_SUB_HEADLINE_USER", "User Notifications");
	
	defined("USER_NOTIFICATIONS_EMPTY_LIST") ? null : define("USER_NOTIFICATIONS_EMPTY_LIST", "Currently there are no notifications to display");

//Dashbord 

	//lend
	
	defined("DASHBORD_BOX_LEND_HEADER") ? null : define("DASHBORD_BOX_LEND_HEADER", "Items lend");
	defined("DASHBORD_BOX_LEND_EMPTY") ? null : define("DASHBORD_BOX_LEND_EMPTY", "You currently have no items lend");
	
//Storage

	//Overview
	
		//Search
		
		defined("STORAGE_OVERVIEW_SEARCH_NAME") ? null :  define("STORAGE_OVERVIEW_SEARCH_NAME", "Name");
		defined("STORAGE_OVERVIEW_SEARCH_PARENT_NAME") ? null :  define("STORAGE_OVERVIEW_SEARCH_PARENT_NAME", "Parent name");
		defined("STORAGE_OVERVIEW_SEARCH_TYPE") ? null :  define("STORAGE_OVERVIEW_SEARCH_TYPE", "Storage type");
	
	//Edit/Add
	
		defined("STORAGE_PLACEHOLDER_PARENT") ? null :  define("STORAGE_PLACEHOLDER_PARENT", "Parent");
		defined("STORAGE_PLACEHOLDER_SELECT") ? null :  define("STORAGE_PLACEHOLDER_SELECT", "Storage");
		
//Items

	//Overview
		
		//search
		
		defined("ITEM_OVERVIEW_SEARCH_PLACEHOLDER_NAME") ? null :  define("ITEM_OVERVIEW_SEARCH_PLACEHOLDER_NAME", "Name");
		defined("ITEM_OVERVIEW_SEARCH_PLACEHOLDER_TYPE") ? null :  define("ITEM_OVERVIEW_SEARCH_PLACEHOLDER_TYPE", "Item Type");
		defined("ITEM_OVERVIEW_SEARCH_PLACEHOLDER_CONSUMEABLE") ? null :  define("ITEM_OVERVIEW_SEARCH_PLACEHOLDER_CONSUMEABLE", "Only consumable");
		defined("ITEM_OVERVIEW_SEARCH_PLACEHOLDER_AMOUNT") ? null :  define("ITEM_OVERVIEW_SEARCH_PLACEHOLDER_AMOUNT", "Amount");
		
		//Header
		
		defined("ITEM_OVERVIEW_HEADER_CONSUMEABLE") ? null :  define("ITEM_OVERVIEW_HEADER_CONSUMEABLE", "Consumable");
		defined("ITEM_OVERVIEW_HEADER_ADD_LEND") ? null :  define("ITEM_OVERVIEW_HEADER_ADD_LEND", "Lend new");
		defined("ITEM_OVERVIEW_HEADER_RETURN_LEND") ? null :  define("ITEM_OVERVIEW_HEADER_RETURN_LEND", "Return");
		
	//EDIT
	
		//InputName
		
		defined("ITEM_EDIT_INPUTNAME_NAME") ? null :  define("ITEM_EDIT_INPUTNAME_NAME", "Name");
		defined("ITEM_EDIT_INPUTNAME_LENGTH") ? null :  define("ITEM_EDIT_INPUTNAME_LENGTH", "Length");
		defined("ITEM_EDIT_INPUTNAME_AMOUNT") ? null :  define("ITEM_EDIT_INPUTNAME_AMOUNT", "Amount");
		defined("ITEM_EDIT_INPUTNAME_TYPE") ? null :  define("ITEM_EDIT_INPUTNAME_TYPE", "Type");
		defined("ITEM_EDIT_INPUTNAME_STORAGE") ? null :  define("ITEM_EDIT_INPUTNAME_STORAGE", "Storage");
		defined("ITEM_EDIT_INPUTNAME_DESCRIPTION") ? null :  define("ITEM_EDIT_INPUTNAME_DESCRIPTION", "Description");
		
		
	//Lend
	
		//header
		
		defined("ITEM_EDIT_LEND_ADD_HEADLINE") ? null :  define("ITEM_EDIT_LEND_ADD_HEADLINE", "Lend Item:");
		defined("ITEM_EDIT_LEND_HEADLINE_RETURN") ? null :  define("ITEM_EDIT_LEND_HEADLINE_RETURN", "Return");
		defined("ITEM_EDIT_LEND_HEADLINE_RETURN_DATE") ? null :  define("ITEM_EDIT_LEND_HEADLINE_RETURN_DATE", "Return date");
		
		
		//Footer
		
		defined("ITEM_EDIT_LEND_INFO_DATE_CHANGE") ? null :  define("ITEM_EDIT_LEND_INFO_DATE_CHANGE", "Please click on the date to change it.");

//Todo

	//category
	
		//Overview
		
		defined("TODO_CATEGORY_OVERVIEW_HEADER_NAME") ? null :  define("TODO_CATEGORY_OVERVIEW_HEADER_NAME", "Name");	
		defined("TODO_CATEGORY_OVERVIEW_HEADER_TYPE") ? null :  define("TODO_CATEGORY_OVERVIEW_HEADER_TYPE", "Type");	
		
		defined("TODO_CATEGORY_OVERVIEW_TYPE_GLOBAL") ? null :  define("TODO_CATEGORY_OVERVIEW_TYPE_GLOBAL", "Global");	
		defined("TODO_CATEGORY_OVERVIEW_TYPE_PERSONAL") ? null :  define("TODO_CATEGORY_OVERVIEW_TYPE_PERSONAL", "Personal");	
		
		//EDIT
		
		defined("TODO_CATEGORY_EDIT_NAME") ? null :  define("TODO_CATEGORY_EDIT_NAME", "Name");	
		defined("TODO_CATEGORY_EDIT_GLOBAL") ? null :  define("TODO_CATEGORY_EDIT_GLOBAL", "Global");	
		
	//Lists
	
		defined("TODO_LISTS_HEAD_UNCATEGORIZED") ? null :  define("TODO_LISTS_HEAD_UNCATEGORIZED", "Uncategorized");
		
		//Overview
		
		defined("TODO_LISTS_OVERVIEW_EMPTY") ? null :  define("TODO_LISTS_OVERVIEW_EMPTY", "No To-Do lists found!");	
		defined("TODO_LISTS_OVERVIEW_NEW_LIST_NAME") ? null :  define("TODO_LISTS_OVERVIEW_NEW_LIST_NAME", "New ToDo List");	
		
		//global
		
		defined("TODO_LISTS__GLOBAL_HEAD_EVENT") ? null :  define("TODO_LISTS__GLOBAL_HEAD_EVENT", "Events");	
		
//Settings

	//personal data
		
		defined("SETTINGS_PERSONAL_DATA_INPUT_NAME_SCHOOL_EMAIL") ? null :  define("SETTINGS_PERSONAL_DATA_INPUT_NAME_SCHOOL_EMAIL", "School E-Mail");
		defined("SETTINGS_PERSONAL_DATA_SELECT_LANGUAGE") ? null :  define("SETTINGS_PERSONAL_DATA_SELECT_LANGUAGE", "Preferred Language");
		defined("SETTINGS_PERSONAL_DATA_SET_PASSWORD") ? null :  define("SETTINGS_PERSONAL_DATA_SET_PASSWORD", "Change Password");
		defined("SETTINGS_PERSONAL_DATA_PASSWORD_CHANGE_INFO") ? null :  define("SETTINGS_PERSONAL_DATA_PASSWORD_CHANGE_INFO", "You succsesfully requested a password change. You can now either enter the following code on the new page opend when you click the button or use the link in the email you will receive.");
		defined("SETTINGS_PERSONAL_DATA_PASSWORD_CHANGE_BUTTON") ? null :  define("SETTINGS_PERSONAL_DATA_PASSWORD_CHANGE_BUTTON", "Change Password");
		defined("SETTINGS_PERSONAL_DATA_PASSWORD_CHANGE_CODE") ? null :  define("SETTINGS_PERSONAL_DATA_PASSWORD_CHANGE_CODE", "Code");
		
//Emails

	//Basic
	
		defined("EMAIL_BASIC_ADOBE_INFO") ? null :  define("EMAIL_BASIC_ADOBE_INFO", "You need Acrobat Reader to open PDF files. You can download it here free of charge");
		defined("EMAIL_BASIC_CONFIDENTIAL_INFO") ? null :  define("EMAIL_BASIC_CONFIDENTIAL_INFO", "This message may contain confidential information and is only intended for the specified recipient. If you are not the intended recipient, please inform the sender or delete the message. Forwarding, distribution or copying of the message is prohibited.");

	//Confirm Email
		defined("EMAIL_CONFIRM_EMAIL_SUBJECT") ? null :  define("EMAIL_CONFIRM_EMAIL_SUBJECT", "Please confirm your email address");
		defined("EMAIL_CONFIRM_EMAIL_INTRO") ? null :  define("EMAIL_CONFIRM_EMAIL_INTRO", "Please click the button to confirm your new or changed email address. Alternatively, you can enter the following link in your web browser.");
		defined("EMAIL_CONFIRM_EMAIL_BUTTON") ? null :  define("EMAIL_CONFIRM_EMAIL_BUTTON", "Confirm E-Mail");
		defined("EMAIL_CONFIRM_EMAIL_BOTTOM_INFO") ? null :  define("EMAIL_CONFIRM_EMAIL_BOTTOM_INFO", "The link is valid for 24 hours and can only be used once! If a new link is generated in the meantime the old link gets deactivated.");

	//Reset Password
		defined("EMAIL_PASSWORD_RESET_SUBJECT") ? null :  define("EMAIL_PASSWORD_RESET_SUBJECT", "Aulatechnik System Password Reset");
		defined("EMAIL_PASSWORD_RESET_INTRO") ? null :  define("EMAIL_PASSWORD_RESET_INTRO", "Please click the button to set a new password. Alternatively, you can enter the following link in your web browser.");
		defined("EMAIL_PASSWORD_RESET_BUTTON") ? null :  define("EMAIL_PASSWORD_RESET_BUTTON", "Set Password");
		defined("EMAIL_PASSWORD_RESET_BOTTOM_INFO") ? null :  define("EMAIL_PASSWORD_RESET_BOTTOM_INFO", "The link is valid for 24 hours and can only be used once! If a new link is generated in the meantime the old link gets deactivated.");
		
//Notifications

	//System Notifications
	
		defined("SYSTEM_NOTIFICATIONS_LEND_OVERDUE_A") ? null: define("SYSTEM_NOTIFICATIONS_LEND_OVERDUE_A", "The return of");
		defined("SYSTEM_NOTIFICATIONS_LEND_OVERDUE_B") ? null: define("SYSTEM_NOTIFICATIONS_LEND_OVERDUE_B", "overdue by");
		defined("SYSTEM_NOTIFICATIONS_LEND_OVERDUE_C") ? null: define("SYSTEM_NOTIFICATIONS_LEND_OVERDUE_C", "days");
		
		defined("SYSTEM_NOTIFICATIONS_LEND_DUE_TODAY_A") ? null: define("SYSTEM_NOTIFICATIONS_LEND_DUE_TODAY_A", "The return of");
		defined("SYSTEM_NOTIFICATIONS_LEND_DUE_TODAY_B") ? null: define("SYSTEM_NOTIFICATIONS_LEND_DUE_TODAY_B", "is due today");
		
		defined("SYSTEM_NOTIFICATIONS_LEND_DUE_A") ? null: define("SYSTEM_NOTIFICATIONS_LEND_DUE_A", "The return of");
		defined("SYSTEM_NOTIFICATIONS_LEND_DUE_B") ? null: define("SYSTEM_NOTIFICATIONS_LEND_DUE_B", "is due in");
		defined("SYSTEM_NOTIFICATIONS_LEND_DUE_C") ? null: define("SYSTEM_NOTIFICATIONS_LEND_DUE_C", "days");
	
		defined("SYSTEM_NOTIFICATIONS_CONFIRM_EMAIL_A") ? null: define("SYSTEM_NOTIFICATIONS_CONFIRM_EMAIL_A", "Please confirm your");
		defined("SYSTEM_NOTIFICATIONS_CONFIRM_EMAIL_B") ? null: define("SYSTEM_NOTIFICATIONS_CONFIRM_EMAIL_B", "Resend confirmation email");
		
		defined("SYSTEM_NOTIFICATIONS_CONFIRM_PERSONAL_EMAIL_A") ? null: define("SYSTEM_NOTIFICATIONS_CONFIRM_PERSONAL_EMAIL_A", "personal email address");
		defined("SYSTEM_NOTIFICATIONS_CONFIRM_SCHOOL_EMAIL_A") ? null: define("SYSTEM_NOTIFICATIONS_CONFIRM_SCHOOL_EMAIL_A", "school email address");
		
	
//Language
	
	defined("LANGUAGE_NAME_GERMAN") ? null :  define("LANGUAGE_NAME_GERMAN", "German");
	defined("LANGUAGE_NAME_ENGLISH") ? null :  define("LANGUAGE_NAME_ENGLISH", "English");
	
//Basic words

	defined("WORD_WELCOME") ? null :  define("WORD_WELCOME", "Welcome");
	defined("WORD_SEARCH") ? null :  define("WORD_SEARCH", "Search");
	defined("WORD_LOADING") ? null :  define("WORD_LOADING", "Loading...");
	defined("WORD_SAVE") ? null :  define("WORD_SAVE", "Save");
	defined("WORD_NEW") ? null :  define("WORD_NEW", "New");
	defined("WORD_DELETE") ? null :  define("WORD_DELETE", "Delete");
	defined("WORD_ABORT") ? null :  define("WORD_ABORT", "Abort");
	defined("WORD_RESET") ? null :  define("WORD_RESET", "Reset");
	defined("WORD_EMAIL") ? null :  define("WORD_EMAIL", "E-Mail");
	defined("WORD_USERNAME") ? null :  define("WORD_USERNAME", "Username");
	defined("WORD_COMMENTS") ? null :  define("WORD_COMMENTS", "Comments");
	defined("WORD_ADD") ? null :  define("WORD_ADD", "Add");
	defined("WORD_LABELS") ? null :  define("WORD_LABELS", "Labels");
	defined("WORD_COMMENTS") ? null :  define("WORD_COMMENTS", "Comments");
	
	defined("WORD_FIRSTNAME") ? null :  define("WORD_FIRSTNAME", "Firstname");
	defined("WORD_LASTNAME") ? null :  define("WORD_LASTNAME", "Lastname");
	
//Basic phrase

	
	
?>

