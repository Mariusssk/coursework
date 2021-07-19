<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Aulatechnik</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="<?php echo CSS;?>/font-awesome.min.css" type="text/css">
    <link rel="stylesheet" href="<?php echo CSS;?>/style.css" type="text/css">
	<link rel="stylesheet" href="<?php echo CSS;?>/nice-select.css" type="text/css">
	
	<link rel="stylesheet" href="<?php echo CSS;?>/user_style.css" type="text/css">
	<link rel="stylesheet" href="<?php echo CSS;?>/storage.css" type="text/css">
	
	<link rel="icon" href="<?php echo IMAGES;?>/aulatechnik_logo_square.jpg">
</head>

<body>
	<script> var INCLUDES = "<?php echo INCLUDES;?>" </script>
	<script> var URL = "<?php echo URL;?>" </script>

	<noscript>
		<style type="text/css">
			.pageContent {display:none;}
		</style>
		<div class="noscriptmsg">
			<h3><?php echo JS_MISSING_ERROR;?></h3>
		</div>
	</noscript>
	
	<script src="<?php echo JS;?>/jquery.min.js"></script>
	
	<div class="mainContainer">
	
		<!-- Notice -->
		
		<div class="headerNotice headerNoticeHidden" id="headerNotice">
			<div class="headerNoticeWrapper">
				<span class="headerNoticeMessage">
					<span id="headerNoticeMessageText">
					</span>
					<a class="headerNoticeMessageLink" id="headerNoticeMessageLink" href=""></a>
				</span>
				<span class="headerNoticeClose" title="Schließen" onclick="hideHeaderNotification()">
				</span>
			</div>
		</div>
		