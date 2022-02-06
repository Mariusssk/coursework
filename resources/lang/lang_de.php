<?php

//Error

defined("LANG_404_ERROR") ? null :  define("LANG_404_ERROR", "Error: 404 Seite nicht gefunden");
defined("JS_MISSING_ERROR") ? null :  define("JS_MISSING_ERROR", "Um diese Seite anzuzeigen benötigen sie JavaScript. Bitte aktivieren sie JavaScript oder wechseln sie den Browser.");

defined("ERROR_PAGE_LOGIN_NEEDED") ? null :  define("ERROR_PAGE_LOGIN_NEEDED", "Sie müssen sich anmelden um diese Seite anzusehen");

//Header


	//Menu
	
	defined("HEADER_MENU_USER_PROFILE") ? null :  define("HEADER_MENU_USER_PROFILE", "Profil");
	defined("HEADER_MENU_USER_LOGOUT") ? null :  define("HEADER_MENU_USER_LOGOUT", "Abmelden");
	
	//Menu Items
	
		//Storage
			defined("HEADER_MENU_ITEM_STORAGE") ? null :  define("HEADER_MENU_ITEM_STORAGE", "Lager");
			defined("HEADER_MENU_ITEM_STORAGE_CREATE") ? null :  define("HEADER_MENU_ITEM_STORAGE_CREATE", "Neu");
			defined("HEADER_MENU_ITEM_STORAGE_OVERVIEW") ? null :  define("HEADER_MENU_ITEM_STORAGE_OVERVIEW", "Übersicht");
			
		//Item
			defined("HEADER_MENU_ITEM_ITEM_MAIN") ? null :  define("HEADER_MENU_ITEM_ITEM_MAIN", "Objekte");
			defined("HEADER_MENU_ITEM_ITEM_CONSUMABLE") ? null :  define("HEADER_MENU_ITEM_ITEM_CONSUMABLE", "Verbrauchsmaterialien");
			defined("HEADER_MENU_ITEM_ITEM_CREATE") ? null :  define("HEADER_MENU_ITEM_ITEM_CREATE", "Neu");
			defined("HEADER_MENU_ITEM_ITEM_OVERVIEW") ? null :  define("HEADER_MENU_ITEM_ITEM_OVERVIEW", "Übersicht");
			defined("HEADER_MENU_ITEM_ITEM_LEND") ? null :  define("HEADER_MENU_ITEM_ITEM_LEND", "Ausleihen");
			
		//To-Do
			defined("HEADER_MENU_ITEM_TODO_CREATE") ? null :  define("HEADER_MENU_ITEM_TODO_CREATE", "Neu");
			defined("HEADER_MENU_ITEM_TODO_CATEGORY") ? null :  define("HEADER_MENU_ITEM_TODO_CATEGORY", "Kategorien");
			defined("HEADER_MENU_ITEM_TODO_PUBLIC") ? null :  define("HEADER_MENU_ITEM_TODO_PUBLIC", "Gemeinsame Listen");
			defined("HEADER_MENU_ITEM_TODO_PERSONAL") ? null :  define("HEADER_MENU_ITEM_TODO_PERSONAL", "Eigene Listen");
			
		//Events
			defined("HEADER_MENU_ITEM_EVENT_CREATE") ? null :  define("HEADER_MENU_ITEM_EVENT_CREATE", "Neu");
			defined("HEADER_MENU_ITEM_EVENT_OVERVIEW") ? null :  define("HEADER_MENU_ITEM_EVENT_OVERVIEW", "Übersicht");
			defined("HEADER_MENU_ITEM_EVENT_LOCATIONS") ? null :  define("HEADER_MENU_ITEM_EVENT_LOCATIONS", "Standorte");
			defined("HEADER_MENU_ITEM_EVENT_CLIENT") ? null :  define("HEADER_MENU_ITEM_EVENT_CLIENT", "Klienten");
	
		//Settings
			defined("HEADER_MENU_ITEM_SETTINGS_MAIN") ? null :  define("HEADER_MENU_ITEM_SETTINGS_MAIN", "Einstellungen");
			defined("HEADER_MENU_ITEM_SETTINGS_USER") ? null :  define("HEADER_MENU_ITEM_SETTINGS_USER", "Profildaten");
			defined("HEADER_MENU_ITEM_SETTINGS_TAGS") ? null :  define("HEADER_MENU_ITEM_SETTINGS_TAGS", "Tags");
			defined("HEADER_MENU_ITEM_SETTINGS_NOTIFICATIONS") ? null :  define("HEADER_MENU_ITEM_SETTINGS_NOTIFICATIONS", "Benachrichtigungen");
			defined("HEADER_MENU_ITEM_SETTINGS_CREATE_USER") ? null :  define("HEADER_MENU_ITEM_SETTINGS_CREATE_USER", "Neuer Nutzer");
			defined("HEADER_MENU_ITEM_SETTINGS_USER_OVERVIEW") ? null :  define("HEADER_MENU_ITEM_SETTINGS_USER_OVERVIEW", "Nutzer auflisten");
			defined("HEADER_MENU_ITEM_SETTINGS_USER_ROLE") ? null :  define("HEADER_MENU_ITEM_SETTINGS_USER_ROLE", "Nutzer rollen");
	
	//Overlay Login
	
	defined("HEADER_OVERLAY_SESSION_ENDED") ? null :  define("HEADER_OVERLAY_SESSION_ENDED", "Ihre Session is abgelaufen");
	
//User

	//Login/Logout
		
	defined("USER_USERNAME") ? null :  define("USER_USERNAME", "Nutzername/E-Mail");
	defined("USER_PASSWORD") ? null :  define("USER_PASSWORD", "Passwort");
	defined("USER_REPEAT_PASSWORD") ? null :  define("USER_REPEAT_PASSWORD", "Passwort wiederholen");
	defined("USER_LOGIN_BTN") ? null :  define("USER_LOGIN_BTN", "Anmelden");
	
	defined("USER_RESET_PASSWORD_BTN") ? null :  define("USER_RESET_PASSWORD_BTN", "Passwort zurücksetzen");

	defined("USER_LOGOUT_BTN") ? null :  define("USER_LOGOUT_BTN", "Abmelden");
	defined("USER_LOGED_IN_STATUS") ? null :  define("USER_LOGED_IN_STATUS", "Sie sind angemeldet");
	
	defined("USER_RESET_PASSWORD_BTN") ? null :  define("USER_RESET_PASSWORD_BTN", "Passwort zurücksetzen");
	
	//Rights missing
	
	defined("USER_RIGHTS_MISSING_HEADER") ? null :  define("USER_RIGHTS_MISSING_HEADER", "Sie haben keine Berechtigungen");
	defined("USER_RIGHTS_MISSING_INFO") ? null :  define("USER_RIGHTS_MISSING_INFO", "Sie haben keine Berechtigung diese Seite bzw. Informationen anzusehen.");
	
	
	//Password requirements
	defined("USER_PASSWORD_REQUIREMENTS_LENGTH") ? null :  define("USER_PASSWORD_REQUIREMENTS_LENGTH", "zwischen 8 und 20 Zeichen");
	defined("USER_PASSWORD_REQUIREMENTS_NUMBER") ? null :  define("USER_PASSWORD_REQUIREMENTS_NUMBER", "mindestens eine Nummer");
	defined("USER_PASSWORD_REQUIREMENTS_LOWERCASE") ? null :  define("USER_PASSWORD_REQUIREMENTS_LOWERCASE", "mindestens ein Kleinbuchstabe");
	defined("USER_PASSWORD_REQUIREMENTS_UPPERCASE") ? null :  define("USER_PASSWORD_REQUIREMENTS_UPPERCASE", "mindestens ein Großbuchstabe");
	defined("USER_PASSWORD_REQUIREMENTS_SPECIAL_CHAR_A") ? null :  define("USER_PASSWORD_REQUIREMENTS_SPECIAL_CHAR_A", "mindestens ein Sonderzeichen");
	defined("USER_PASSWORD_REQUIREMENTS_SPECIAL_CHAR_B") ? null :  define("USER_PASSWORD_REQUIREMENTS_SPECIAL_CHAR_B", "Mögliche Sonderzeichen: !?@#$%&*");
	
	
	//Profile
	
	defined("USER_PROFILE_NOT_FOUND") ? null :  define("USER_PROFILE_NOT_FOUND", "Profil nicht gefunden");
	defined("USER_PROFILE_REGISTERED_SINCE") ? null :  define("USER_PROFILE_REGISTERED_SINCE", "Registriert seit");
	
	
	

//Dashbord 

	//lend
	
	defined("DASHBORD_BOX_LEND_HEADER") ? null : define("DASHBORD_BOX_LEND_HEADER", "Objekte ausgeliehen");
	defined("DASHBORD_BOX_LEND_EMPTY") ? null : define("DASHBORD_BOX_LEND_EMPTY", "Sie haben aktuell keine Objekte ausgeliehen");
	
//Storage

	//Overview
	
		//Search
		
		defined("STORAGE_OVERVIEW_SEARCH_NAME") ? null :  define("STORAGE_OVERVIEW_SEARCH_NAME", "Name");
		defined("STORAGE_OVERVIEW_SEARCH_PARENT_NAME") ? null :  define("STORAGE_OVERVIEW_SEARCH_PARENT_NAME", "Übergeorneter");
		defined("STORAGE_OVERVIEW_SEARCH_TYPE") ? null :  define("STORAGE_OVERVIEW_SEARCH_TYPE", "Lager Typ");
	
	//Edit/Add
	
		defined("STORAGE_PLACEHOLDER_PARENT") ? null :  define("STORAGE_PLACEHOLDER_PARENT", "Übergeorneter");
		defined("STORAGE_PLACEHOLDER_SELECT") ? null :  define("STORAGE_PLACEHOLDER_SELECT", "Lager");
		
	//Item list
	
		defined("STORAGE_LIST_STORAGE_NOT_FOUND") ? null :  define("STORAGE_LIST_STORAGE_NOT_FOUND", "Lager nicht gefunden");
		
//Items

	//Overview
		
		//search
		
		defined("ITEM_OVERVIEW_SEARCH_PLACEHOLDER_NAME") ? null :  define("ITEM_OVERVIEW_SEARCH_PLACEHOLDER_NAME", "Name");
		defined("ITEM_OVERVIEW_SEARCH_PLACEHOLDER_TYPE") ? null :  define("ITEM_OVERVIEW_SEARCH_PLACEHOLDER_TYPE", "Objekt Typ");
		defined("ITEM_OVERVIEW_SEARCH_PLACEHOLDER_CONSUMEABLE") ? null :  define("ITEM_OVERVIEW_SEARCH_PLACEHOLDER_CONSUMEABLE", "Nur Verbrauchsmaterialien");
		defined("ITEM_OVERVIEW_SEARCH_PLACEHOLDER_AMOUNT") ? null :  define("ITEM_OVERVIEW_SEARCH_PLACEHOLDER_AMOUNT", "Menge");
		
		//Header
		
		defined("ITEM_OVERVIEW_HEADER_CONSUMEABLE") ? null :  define("ITEM_OVERVIEW_HEADER_CONSUMEABLE", "Verbrauchsmaterialien");
		defined("ITEM_OVERVIEW_HEADER_ADD_LEND") ? null :  define("ITEM_OVERVIEW_HEADER_ADD_LEND", "Neu ausleihen");
		defined("ITEM_OVERVIEW_HEADER_RETURN_LEND") ? null :  define("ITEM_OVERVIEW_HEADER_RETURN_LEND", "Zurückgeben");
		
	//EDIT
	
		//InputName
		
		defined("ITEM_EDIT_DATA_LOAD_FAILURE") ? null :  define("ITEM_EDIT_DATA_LOAD_FAILURE", "Das Objekt konnte nicht gefunden werden");
		
		defined("ITEM_EDIT_INPUTNAME_NAME") ? null :  define("ITEM_EDIT_INPUTNAME_NAME", "Name");
		defined("ITEM_EDIT_INPUTNAME_LENGTH") ? null :  define("ITEM_EDIT_INPUTNAME_LENGTH", "Länge");
		defined("ITEM_EDIT_INPUTNAME_AMOUNT") ? null :  define("ITEM_EDIT_INPUTNAME_AMOUNT", "Menge");
		defined("ITEM_EDIT_INPUTNAME_TYPE") ? null :  define("ITEM_EDIT_INPUTNAME_TYPE", "Typ");
		defined("ITEM_EDIT_INPUTNAME_STORAGE") ? null :  define("ITEM_EDIT_INPUTNAME_STORAGE", "Lager");
		defined("ITEM_EDIT_INPUTNAME_DESCRIPTION") ? null :  define("ITEM_EDIT_INPUTNAME_DESCRIPTION", "Beschreibung");
		
		
	//Lend
	
		//header
		
		defined("ITEM_EDIT_LEND_ADD_HEADLINE") ? null :  define("ITEM_EDIT_LEND_ADD_HEADLINE", "Ausgeliehenes Objekt");
		defined("ITEM_EDIT_LEND_HEADLINE_RETURN") ? null :  define("ITEM_EDIT_LEND_HEADLINE_RETURN", "Zurückgeben");
		defined("ITEM_EDIT_LEND_HEADLINE_RETURN_DATE") ? null :  define("ITEM_EDIT_LEND_HEADLINE_RETURN_DATE", "Rückgabedatum");
		
		
		//Footer
		
		defined("ITEM_EDIT_LEND_INFO_DATE_CHANGE") ? null :  define("ITEM_EDIT_LEND_INFO_DATE_CHANGE", "Bitte das Datum anklicken, um es zu ändern");

//Todo

	//category
	
		//Overview
		
		defined("TODO_CATEGORY_OVERVIEW_HEADER_NAME") ? null :  define("TODO_CATEGORY_OVERVIEW_HEADER_NAME", "Name");	
		defined("TODO_CATEGORY_OVERVIEW_HEADER_TYPE") ? null :  define("TODO_CATEGORY_OVERVIEW_HEADER_TYPE", "Typ");	
		
		defined("TODO_CATEGORY_OVERVIEW_TYPE_GLOBAL") ? null :  define("TODO_CATEGORY_OVERVIEW_TYPE_GLOBAL", "Öffentlich");	
		defined("TODO_CATEGORY_OVERVIEW_TYPE_PERSONAL") ? null :  define("TODO_CATEGORY_OVERVIEW_TYPE_PERSONAL", "Privat");	
		
		//EDIT
		
		defined("TODO_CATEGORY_EDIT_NAME") ? null :  define("TODO_CATEGORY_EDIT_NAME", "Name");	
		defined("TODO_CATEGORY_EDIT_GLOBAL") ? null :  define("TODO_CATEGORY_EDIT_GLOBAL", "Öffentlich");	
		
	//Lists
	
		defined("TODO_LISTS_HEAD_UNCATEGORIZED") ? null :  define("TODO_LISTS_HEAD_UNCATEGORIZED", "Ohne Kategorie");
		
		//Overview
		
		defined("TODO_LISTS_OVERVIEW_EMPTY") ? null :  define("TODO_LISTS_OVERVIEW_EMPTY", "Keine To-Do Listen gefunden");	
		defined("TODO_LISTS_OVERVIEW_NEW_LIST_NAME") ? null :  define("TODO_LISTS_OVERVIEW_NEW_LIST_NAME", "Neue Liste");	
		
		//global
		
		defined("TODO_LISTS__GLOBAL_HEAD_EVENT") ? null :  define("TODO_LISTS__GLOBAL_HEAD_EVENT", "Veranstaltungen");	
		
		
//Events

	//Clients
	
		defined("EVENT_CLIENT_LIST_HEADER_NAME") ? null :  define("EVENT_CLIENT_LIST_HEADER_NAME", "Name");
		defined("EVENT_CLIENT_LIST_HEADER_EXTERNAL") ? null :  define("EVENT_CLIENT_LIST_HEADER_EXTERNAL", "Extern");
		defined("EVENT_CLIENT_LIST_HEADER_DESCRIPTION") ? null :  define("EVENT_CLIENT_LIST_HEADER_DESCRIPTION", "Beschreibung");
		
		defined("EVENT_CLIENT_NEW_TEMPLATE_NAME") ? null :  define("EVENT_CLIENT_NEW_TEMPLATE_NAME", "Neuer Klient");
		
	//Locations
	
		defined("EVENT_LOCATION_NEW_TEMPLATE_NAME") ? null :  define("EVENT_LOCATION_NEW_TEMPLATE_NAME", "Neuer Standort");
	
	//Overview
		
		defined("EVENT_OVERVIEW_CONTAINER_STARTTIME") ? null :  define("EVENT_OVERVIEW_CONTAINER_STARTTIME", "Von");
		defined("EVENT_OVERVIEW_CONTAINER_ENDTIME") ? null :  define("EVENT_OVERVIEW_CONTAINER_ENDTIME", "Bis");
		defined("EVENT_OVERVIEW_CONTAINER_LOCATION") ? null :  define("EVENT_OVERVIEW_CONTAINER_LOCATION", "Standort");
		defined("EVENT_OVERVIEW_CONTAINER_CLIENT") ? null :  define("EVENT_OVERVIEW_CONTAINER_CLIENT", "Klient");
		defined("EVENT_OVERVIEW_CONTAINER_EXTERNAL") ? null :  define("EVENT_OVERVIEW_CONTAINER_EXTERNAL", "Extern");
	
		//Category Names
		
			defined("EVENT_OVERVIEW_CATEGORY_RUNNING") ? null :  define("EVENT_OVERVIEW_CATEGORY_RUNNING", "Läuft");
			defined("EVENT_OVERVIEW_CATEGORY_SOON") ? null :  define("EVENT_OVERVIEW_CATEGORY_SOON", "Bald");
			defined("EVENT_OVERVIEW_CATEGORY_FUTURE") ? null :  define("EVENT_OVERVIEW_CATEGORY_FUTURE", "In der Zukunft");
			defined("EVENT_OVERVIEW_CATEGORY_PAST") ? null :  define("EVENT_OVERVIEW_CATEGORY_PAST", "Vergangen");
			defined("EVENT_OVERVIEW_CATEGORY_UNCATEGORIZED") ? null :  define("EVENT_OVERVIEW_CATEGORY_UNCATEGORIZED", "Ohne Kategorie");
			
	//create
		
		defined("EVENT_CREATION_TEMPLATE_NAME") ? null :  define("EVENT_CREATION_TEMPLATE_NAME", "Neue Veranstaltung");
		
	//EDIT
	
		defined("EVENT_EDIT_ERROR_LOADING_DATA") ? null :  define("EVENT_EDIT_ERROR_LOADING_DATA", "Die Daten für die Veranstaltung konnten nicht geladen werden");
		defined("EVENT_EDIT_PLACEHOLDER_NAME") ? null :  define("EVENT_EDIT_PLACEHOLDER_NAME", "Name");
		defined("EVENT_EDIT_PLACEHOLDER_LOCATION") ? null :  define("EVENT_EDIT_PLACEHOLDER_LOCATION", "Standort");
		defined("EVENT_EDIT_PLACEHOLDER_CLIENT") ? null :  define("EVENT_EDIT_PLACEHOLDER_CLIENT", "Klient");
		defined("EVENT_EDIT_PLACEHOLDER_STARTDATE") ? null :  define("EVENT_EDIT_PLACEHOLDER_STARTDATE", "Start-Datum");
		defined("EVENT_EDIT_PLACEHOLDER_ENDDATE") ? null :  define("EVENT_EDIT_PLACEHOLDER_ENDDATE", "End-Datum");
		defined("EVENT_EDIT_PLACEHOLDER_STARTTIME") ? null :  define("EVENT_EDIT_PLACEHOLDER_STARTTIME", "Start-Zeit");
		defined("EVENT_EDIT_PLACEHOLDER_ENDTIME") ? null :  define("EVENT_EDIT_PLACEHOLDER_ENDTIME", "End-Zeit");
		defined("EVENT_EDIT_PLACEHOLDER_DESCRIPTION") ? null :  define("EVENT_EDIT_PLACEHOLDER_DESCRIPTION", "Beschreibung");
		
		defined("EVENT_EDIT_HEADLINE_TAGS") ? null :  define("EVENT_EDIT_HEADLINE_TAGS", "Tags");
		defined("EVENT_EDIT_HEADLINE_RESPONSIBLES") ? null :  define("EVENT_EDIT_HEADLINE_RESPONSIBLES", "Verantwortliche");
		
	
//Settings

	//personal data
		
		defined("SETTINGS_PERSONAL_DATA_INPUT_NAME_SCHOOL_EMAIL") ? null :  define("SETTINGS_PERSONAL_DATA_INPUT_NAME_SCHOOL_EMAIL", "Schul E-Mail");
		defined("SETTINGS_PERSONAL_DATA_SELECT_LANGUAGE") ? null :  define("SETTINGS_PERSONAL_DATA_SELECT_LANGUAGE", "Sprache");
		defined("SETTINGS_PERSONAL_DATA_SET_PASSWORD") ? null :  define("SETTINGS_PERSONAL_DATA_SET_PASSWORD", "Passwort ändern");
		defined("SETTINGS_PERSONAL_DATA_PASSWORD_CHANGE_INFO") ? null :  define("SETTINGS_PERSONAL_DATA_PASSWORD_CHANGE_INFO", "Sie haben erfolgreich eine Änderung ihres Passworts angefragt. Sie können nun etweder den folgenden Code auf der neuen Seite eingeben, wenn sie den Button drücken oder den Link in der E-Mail benutzt die sie bald erhalten.");
		defined("SETTINGS_PERSONAL_DATA_PASSWORD_CHANGE_BUTTON") ? null :  define("SETTINGS_PERSONAL_DATA_PASSWORD_CHANGE_BUTTON", "Passwort ändern");
		defined("SETTINGS_PERSONAL_DATA_PASSWORD_CHANGE_CODE") ? null :  define("SETTINGS_PERSONAL_DATA_PASSWORD_CHANGE_CODE", "Code");
		
	//Tags
		
		defined("TAG_LIST_HEADLINE_NAME") ? null :  define("TAG_LIST_HEADLINE_NAME", "Tag Name");
		defined("TAG_LIST_HEADLINE_COLOR") ? null :  define("TAG_LIST_HEADLINE_COLOR", "Farbe");
		
		//New TAG
			defined("TAG_NEW_NAME") ? null :  define("TAG_NEW_NAME", "Neuer Tag");
	
	//Scanner
	
		//View scanner
		
			defined("SCANNER_SCAN_OPEN_SCANNER") ? null :  define("SCANNER_SCAN_OPEN_SCANNER", "Scanner öffnen");
			defined("SCANNER_SCAN_FIND_DATA") ? null :  define("SCANNER_SCAN_FIND_DATA", "Daten suchen");
			defined("SCANNER_SCAN_DATA_INPUT") ? null :  define("SCANNER_SCAN_DATA_INPUT", "Scan Code");
			
		// View data
			
			defined("SCANNER_VIEW_TYPE_NOT_VALID") ? null :  define("SCANNER_VIEW_TYPE_NOT_VALID", "Der angebene Typ is nicht gültig");
			defined("SCANNER_VIEW_DATA_NOT_FOUND") ? null :  define("SCANNER_VIEW_DATA_NOT_FOUND", "Das System konnte auf Basis des Typs und der Attribute keine Daten finden");
			
			//Storage
			
				defined("SCANNER_VIEW_STORAGE_LOCATION") ? null :  define("SCANNER_VIEW_STORAGE_LOCATION", "Standort");
			
		//Generator
		
			defined("QR_GENERATOR_DATA_SELECT_TYPE_PLACEHOLDER") ? null :  define("QR_GENERATOR_DATA_SELECT_TYPE_PLACEHOLDER", "Typ auswählen");
			
	//Roles
		
		defined("ROLE_LIST_HEADLINE_NAME") ? null :  define("ROLE_LIST_HEADLINE_NAME", "Rollen name");
		defined("ROLE_LIST_HEADLINE_PRE_DEFINED") ? null :  define("ROLE_LIST_HEADLINE_PRE_DEFINED", "Vor-definiert");
		
		defined("ROLE_LIST_TEMPLATE_NEW_ROLE_NAME") ? null :  define("ROLE_LIST_TEMPLATE_NEW_ROLE_NAME", "Neue Rolle");
		
		//edit
		
			defined("ROLE_EDIT_NOT_FOUND") ? null :  define("ROLE_EDIT_NOT_FOUND", "Die gesuchte Rolle konnte nicht gefunden werden");
			defined("ROLE_EDIT_PRE_DEFINED") ? null :  define("ROLE_EDIT_PRE_DEFINED", "Vor-definierte Rollen können nicht geändert werden");
			defined("ROLE_EDIT_FORM_PLACEHOLDER_NAME") ? null :  define("ROLE_EDIT_FORM_PLACEHOLDER_NAME", "Name");
		
		
	//User
	
		//list
		
			defined("SETTINGS_USER_LIST_HEADLINE_ROLE") ? null : define("SETTINGS_USER_LIST_HEADLINE_ROLE", "Rolle");
			defined("SETTINGS_USER_LIST_PLACEHOLDER_NAME") ? null : define("SETTINGS_USER_LIST_PLACEHOLDER_NAME", "Name");
		
		//edit
		
			defined("SETTINGS_USER_EDIT_LOAD_FAILED") ? null : define("SETTINGS_USER_EDIT_LOAD_FAILED", "Nutzerdaten konnten nicht geladen werden");
			defined("SETTINGS_USER_EDIT_PLACEHOLDER_EMAIL") ? null : define("SETTINGS_USER_EDIT_PLACEHOLDER_EMAIL", "E-Mail");
			defined("SETTINGS_USER_EDIT_PLACEHOLDER_SCHOOL_EMAIL") ? null : define("SETTINGS_USER_EDIT_PLACEHOLDER_SCHOOL_EMAIL", "Schul-Email");
			defined("SETTINGS_USER_EDIT_PLACEHOLDER_USER_ROLE") ? null : define("SETTINGS_USER_EDIT_PLACEHOLDER_USER_ROLE", "Nutzer Rolle");
			defined("SETTINGS_USER_EDIT_PLACEHOLDER_ACTIVE") ? null : define("SETTINGS_USER_EDIT_PLACEHOLDER_ACTIVE", "Aktiv");
			defined("SETTINGS_USER_EDIT_PLACEHOLDER_PREFERRED_LANGUAGE") ? null : define("SETTINGS_USER_EDIT_PLACEHOLDER_PREFERRED_LANGUAGE", "Sprache");
		
		
		
//Emails

	//Basic
	
		defined("EMAIL_BASIC_ADOBE_INFO") ? null :  define("EMAIL_BASIC_ADOBE_INFO", "Sie benötigen den Acrobat Reader, um PDF-Dateien zu öffnen. Sie können ihn hier kostenlos herunterladen");
		defined("EMAIL_BASIC_CONFIDENTIAL_INFO") ? null :  define("EMAIL_BASIC_CONFIDENTIAL_INFO", "Diese Nachricht kann vertrauliche Informationen enthalten und ist nur für den angegebenen Empfänger bestimmt. Sollten Sie nicht der vorgesehene Empfänger sein, informieren Sie bitte den Absender oder löschen Sie die Nachricht. Die Weiterleitung, Verteilung oder Vervielfältigung der Nachricht ist verboten.");

	//Confirm Email
		defined("EMAIL_CONFIRM_EMAIL_SUBJECT") ? null :  define("EMAIL_CONFIRM_EMAIL_SUBJECT", "Please confirm your email address");
		defined("EMAIL_CONFIRM_EMAIL_INTRO") ? null :  define("EMAIL_CONFIRM_EMAIL_INTRO", "Bitte klicken Sie auf die Schaltfläche, um Ihre neue oder geänderte E-Mail-Adresse zu bestätigen. Alternativ können Sie auch den folgenden Link in Ihrem Webbrowser eingeben.");
		defined("EMAIL_CONFIRM_EMAIL_BUTTON") ? null :  define("EMAIL_CONFIRM_EMAIL_BUTTON", "E-Mail Adresse Bestätigen");
		defined("EMAIL_CONFIRM_EMAIL_BOTTOM_INFO") ? null :  define("EMAIL_CONFIRM_EMAIL_BOTTOM_INFO", "Der Link ist 24 Stunden lang gültig und kann nur einmal verwendet werden! Wenn in der Zwischenzeit ein neuer Link generiert wird, wird der alte Link deaktiviert.");

	//Reset Password
		defined("EMAIL_PASSWORD_RESET_SUBJECT") ? null :  define("EMAIL_PASSWORD_RESET_SUBJECT", "Aulatechnik System Passwort Zurücksetzen");
		defined("EMAIL_PASSWORD_RESET_INTRO") ? null :  define("EMAIL_PASSWORD_RESET_INTRO", "Bitte klicken Sie auf die Schaltfläche, um ein neues Passwort festzulegen. Alternativ können Sie auch den folgenden Link in Ihrem Webbrowser eingeben.");
		defined("EMAIL_PASSWORD_RESET_BUTTON") ? null :  define("EMAIL_PASSWORD_RESET_BUTTON", "Passwort festlegen");
		defined("EMAIL_PASSWORD_RESET_BOTTOM_INFO") ? null :  define("EMAIL_PASSWORD_RESET_BOTTOM_INFO", "Der Link ist 24 Stunden lang gültig und kann nur einmal verwendet werden! Wenn in der Zwischenzeit ein neuer Link generiert wird, wird der alte Link deaktiviert.");
		
	//Notification
	
		defined("EMAIL_NOTIFICATION_SUBJECT") ? null :  define("EMAIL_NOTIFICATION_SUBJECT", "Aulatechnik Neue Benachrichtigung");
		
	
	
//Notifications

	//System Notifications
	
		defined("SYSTEM_NOTIFICATIONS_LEND_OVERDUE_A") ? null: define("SYSTEM_NOTIFICATIONS_LEND_OVERDUE_A", "Die Rückgabe von");
		defined("SYSTEM_NOTIFICATIONS_LEND_OVERDUE_B") ? null: define("SYSTEM_NOTIFICATIONS_LEND_OVERDUE_B", "ist überfällig seit");
		defined("SYSTEM_NOTIFICATIONS_LEND_OVERDUE_C") ? null: define("SYSTEM_NOTIFICATIONS_LEND_OVERDUE_C", "Tagen");
		
		defined("SYSTEM_NOTIFICATIONS_LEND_DUE_TODAY_A") ? null: define("SYSTEM_NOTIFICATIONS_LEND_DUE_TODAY_A", "Die Rückgabe von");
		defined("SYSTEM_NOTIFICATIONS_LEND_DUE_TODAY_B") ? null: define("SYSTEM_NOTIFICATIONS_LEND_DUE_TODAY_B", "ist heute fällig");
		
		defined("SYSTEM_NOTIFICATIONS_LEND_DUE_A") ? null: define("SYSTEM_NOTIFICATIONS_LEND_DUE_A", "Die Rückgabe von");
		defined("SYSTEM_NOTIFICATIONS_LEND_DUE_B") ? null: define("SYSTEM_NOTIFICATIONS_LEND_DUE_B", "ist fällig in");
		defined("SYSTEM_NOTIFICATIONS_LEND_DUE_C") ? null: define("SYSTEM_NOTIFICATIONS_LEND_DUE_C", "Tage");
	
		defined("SYSTEM_NOTIFICATIONS_CONFIRM_EMAIL_A") ? null: define("SYSTEM_NOTIFICATIONS_CONFIRM_EMAIL_A", "Bitte bestätigen Sie ihre");
		defined("SYSTEM_NOTIFICATIONS_CONFIRM_EMAIL_B") ? null: define("SYSTEM_NOTIFICATIONS_CONFIRM_EMAIL_B", "Bestätigungsmail erneut senden");
		
		defined("SYSTEM_NOTIFICATIONS_CONFIRM_PERSONAL_EMAIL_A") ? null: define("SYSTEM_NOTIFICATIONS_CONFIRM_PERSONAL_EMAIL_A", "persönliche E-Mail Adresse");
		defined("SYSTEM_NOTIFICATIONS_CONFIRM_SCHOOL_EMAIL_A") ? null: define("SYSTEM_NOTIFICATIONS_CONFIRM_SCHOOL_EMAIL_A", "schul-E-Mail Adresse");
	
	//User notification
	
		defined("USER_NOTIFICATIONS_HEADLINE") ? null : define("USER_NOTIFICATIONS_HEADLINE", "Benachrichtigungen");
		
		defined("USER_NOTIFICATIONS_SUB_HEADLINE_SYSTEM") ? null : define("USER_NOTIFICATIONS_SUB_HEADLINE_SYSTEM", "System Benachrichtigungen");
		defined("USER_NOTIFICATIONS_SUB_HEADLINE_USER") ? null : define("USER_NOTIFICATIONS_SUB_HEADLINE_USER", "Nutzer Benachrichtigungen");
		
		defined("USER_NOTIFICATIONS_EMPTY_LIST") ? null : define("USER_NOTIFICATIONS_EMPTY_LIST", "Aktuell gibt es keine Benachrichtigungen");
		
		defined("USER_NOTIFICATIONS_BUTTON_MARK_READ") ? null : define("USER_NOTIFICATIONS_BUTTON_MARK_READ", "Als gelesen makieren");
		
		
		
	//Notifications List
		
		defined("NOTIFICATION_REQUEST_LIST_HEADLINE_TYPE") ? null : define("NOTIFICATION_REQUEST_LIST_HEADLINE_TYPE", "Typ");
		defined("NOTIFICATION_REQUEST_LIST_HEADLINE_NAME") ? null : define("NOTIFICATION_REQUEST_LIST_HEADLINE_NAME", "Name");
		defined("NOTIFICATION_REQUEST_LIST_HEADLINE_EMAIL_UPDATE") ? null : define("NOTIFICATION_REQUEST_LIST_HEADLINE_EMAIL_UPDATE", "E-Mail Updates");
		defined("NOTIFICATION_REQUEST_LIST_HEADLINE_DAILY_UPDATE") ? null : define("NOTIFICATION_REQUEST_LIST_HEADLINE_DAILY_UPDATE", "Tägliches Update");
	
//Language
	
	defined("LANGUAGE_NAME_GERMAN") ? null :  define("LANGUAGE_NAME_GERMAN", "Deutsch");
	defined("LANGUAGE_NAME_ENGLISH") ? null :  define("LANGUAGE_NAME_ENGLISH", "Englisch");
	
//Basic words

	defined("WORD_WELCOME") ? null :  define("WORD_WELCOME", "Willkommen");
	defined("WORD_ACTION") ? null :  define("WORD_ACTION", "Aktionen");
	defined("WORD_SEARCH") ? null :  define("WORD_SEARCH", "Suchen");
	defined("WORD_LOADING") ? null :  define("WORD_LOADING", "Lädt...");
	defined("WORD_SAVE") ? null :  define("WORD_SAVE", "Speichern");
	defined("WORD_NEW") ? null :  define("WORD_NEW", "Neu");
	defined("WORD_DELETE") ? null :  define("WORD_DELETE", "Löschen");
	defined("WORD_ABORT") ? null :  define("WORD_ABORT", "Abbrechen");
	defined("WORD_RESET") ? null :  define("WORD_RESET", "Zurücksetzen");
	defined("WORD_EMAIL") ? null :  define("WORD_EMAIL", "E-Mail");
	defined("WORD_USERNAME") ? null :  define("WORD_USERNAME", "Nutzername");
	defined("WORD_COMMENTS") ? null :  define("WORD_COMMENTS", "Kommentare");
	defined("WORD_ADD") ? null :  define("WORD_ADD", "Hinzufügen");
	defined("WORD_LABELS") ? null :  define("WORD_LABELS", "Labels");
	defined("WORD_TODO_LIST") ? null :  define("WORD_TODO_LIST", "ToDo Liste");
	defined("WORD_STORAGE") ? null :  define("WORD_STORAGE", "Lager");
	defined("WORD_ITEM") ? null :  define("WORD_ITEM", "Objekt");
	defined("WORD_EVENT") ? null :  define("WORD_EVENT", "Veranstaltung");
	defined("WORD_BACK") ? null :  define("WORD_BACK", "Zurück");
	
	defined("WORD_FIRSTNAME") ? null :  define("WORD_FIRSTNAME", "Vorname");
	defined("WORD_LASTNAME") ? null :  define("WORD_LASTNAME", "Nachnahme");
	
//Basic phrase

	
	
?>

