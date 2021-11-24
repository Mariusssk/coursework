<?php

namespace Template\Email;

function getEmailHeader($type = "") {
	$header = <<<HTML

	<!DOCTYPE html>
	<html lang="en" xmlns="https://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office">
	<head>
		<meta http-equiv="Content-Type" content="text/html charset=UTF-8" />
		<meta name="viewport" content="width=device-width,initial-scale=1">
		<meta name="x-apple-disable-message-reformatting">
		<!--[if !mso]><!-->
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<!--<![endif]-->
		<title></title>
		<!--[if mso]>
		<style type="text/css">
			table {border-collapse:collapse;border-spacing:0;margin:0;}
			div, td {padding:0;}
			div {margin:0 !important;}
			a, a:link, a:visited {text-decoration: none; color: #00788a;}
			a:hover {text-decoration: underline;}
			h2,h2 a,h2 a:visited,h3,h3 a,h3 a:visited,h4,h5,h6,.t_cht {color:#000 !important;}
			.ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td {line-height: 100%;}
			.ExternalClass {width: 100%;}
		</style>
		<noscript>
		<xml>
			<o:OfficeDocumentSettings>
				<o:PixelsPerInch>96</o:PixelsPerInch>
			</o:OfficeDocumentSettings>
		</xml>
		</noscript>
		<![endif]-->
	</head>	
HTML;

	$header .= '	
	<body style="margin:0;padding:0;word-spacing:normal;background-color:#ffffff;">
		<table border="0" cellpadding="0" cellspacing="0"><tr style="background-color: #f5f5f5"><td style="width: 10%"></td><td height="50" width="3000">
			<img src="'.IMAGES.'/aulatechnik_logo_square.jpg" height="120" />
		</td></tr></table>
		<!--[if mso]>
		<div role="article" aria-roledescription="email" lang="en" style="-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;background-color:#ffffff;">
			<table width="100%" style="padding: 10px;" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation">
				<tr>
					<td align="center">
		<![endif]-->
	';
	 
	return($header);
}