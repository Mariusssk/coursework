<?php

//-----------------Config File---------------------
//Overall config file
//SQL connection
//URL and file constants
//Import of language and class files 
//-----------------Config File---------------------

//Session
session_start();

//Config File

//Files

include "db_functions.php";
include "functions.php";

//Constants

	//Bascis

	defined("DS") ? null : define("DS", DIRECTORY_SEPARATOR);
	
	//URL
	
	defined("URL_PATH") ? null : define("URL_PATH", "/coursework/public");
	
	$allowedURL = array("localhost");

	if(isset($_SERVER['SERVER_NAME']) AND in_array($_SERVER['SERVER_NAME'],$allowedURL)) {
		defined("URL") ? null : define("URL", "http://".$_SERVER['SERVER_NAME'].URL_PATH);
	}
	
	defined("URL") ? null : define("URL", "http:/localhost".URL_PATH);
	
	//Files
	
	defined("PUBLIC_DIR") ? null : define("PUBLIC_DIR", __DIR__ . DS . "../public");
	
	defined("INCLUDES") ? null : define("INCLUDES", URL . "/includes");

	defined("TEMPLATES") ? null : define("TEMPLATES", __DIR__ . DS . "templates");
	
	defined("MODULES") ? null : define("MODULES", __DIR__ . DS . "modules");

	defined("LANG") ? null : define("LANG", __DIR__ . DS . "lang");
	
	defined("IMAGES") ? null : define("IMAGES", URL . "/images");
	
	defined("CLASSES") ? null : define("CLASSES", __DIR__ . DS . "classes");
	
	defined("CSS") ? null : define("CSS", URL . "/css");
	
	defined("JS") ? null : define("JS", URL . "/js");
	
	//Database
	
	defined("DB_HOST") ? null : define("DB_HOST", "localhost");
	
	defined("DB_USER") ? null : define("DB_USER", "root");
	
	defined("DB_PASS") ? null : define("DB_PASS", "");
	
	defined("DB_NAME") ? null : define("DB_NAME", "aulatechnik_db");
	
//Connect database

$mysqli = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

//Include mailer functions

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
	
//Load functions
	
spl_autoload_register(function ($class_name) {
	if(substr($class_name,0,9) == "PHPMailer" OR $class_name == "PHPMailer" OR $class_name=="Exception" OR $class_name=="SMTP") {
		include MODULES.'/PHPMailer/src/Exception.php';
		include MODULES.'/PHPMailer/src/PHPMailer.php';
		include MODULES.'/PHPMailer/src/SMTP.php';
	} else if(substr($class_name,0,4) == "Mpdf") {
		//require_once MODULES.'/mpdf/vendor/autoload.php';
	} else {
		include CLASSES."/".$class_name . '.php';
	}
});


// Include php Language file
if(isset($_SESSION['lang'])){
	$langFileDir = LANG."/lang_".$_SESSION['lang'].".php";
	if(file_exists($langFileDir)){
		include $langFileDir;
	}
}

include LANG."/lang_en.php";

//Configurate session


$session = new Session;

?>