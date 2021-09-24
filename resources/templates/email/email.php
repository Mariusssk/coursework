<?php

namespace Template\Email;

function getEmailContent($type,$attributes = array()) {
	ob_start();
	if(($type == "confirmEmail" OR $type == "confirmSchoolEmail")) {
		$link = "";
		if(isset($attributes['verify_link'])){
			$link .= $attributes['verify_link'];
		}
		if(isset($attributes['code'])){
			$link .= "/".$attributes['code'];
		}
		echo '
		<div style="width: 100%;text-align: center;">
			<div style="width:100%;max-width:780px;display:inline-block;vertical-align:middle; margin: 5px; font-family: arial,sans-serif;">
				<div style="padding:10px;font-size:14px;line-height:18px;text-align:center;" valign="middle" align="center">
					<!--[if mso]>
					<table style="width: 100%;">
					<tr><td height="5" colspan="3"></tr>
					<tr><td width="10"></td>
					<td width="740">
					<![endif]-->
						<h2>'.EMAIL_CONFIRM_EMAIL_SUBJECT.'!</h2>
						<p>'.EMAIL_CONFIRM_EMAIL_INTRO.'</p>
						<a href="'.$link.'" style="color: white; background-color: #2271b1; border-radius: 4px; padding: 10px 25px; text-decoration: none;mso-padding-alt:0;"><!--[if mso]><i style="letter-spacing: 25px;mso-font-width:-100%;mso-text-raise:20pt">&nbsp;</i><![endif]--> <span style="mso-text-raise:10pt;font-weight:bold;">'.EMAIL_CONFIRM_EMAIL_BUTTON.'</span><!--[if mso]><i style="letter-spacing: 25px;mso-font-width:-100%">&nbsp;</i><![endif]--></a>
						<br><br>
						<p style="font-size: 8px; word-break:break-all;">'.$link.'</p>
						<p style="font-size: 8px;">'.EMAIL_CONFIRM_EMAIL_BOTTOM_INFO.'</p>
					<!--[if mso]>
					</td><td width="10"></td></tr></table>
					<![endif]-->
				</div>
			</div>
		</div>
		';
	} else if($type == "resetPassword") {
		$link = "";
		if(isset($attributes['verify_link'])){
			$link .= $attributes['verify_link'];
		}
		if(isset($attributes['code'])){
			$link .= "/".$attributes['code'];
		}
		echo '
		<div style="width: 100%;text-align: center;">
			<div style="width:100%;max-width:780px;display:inline-block;vertical-align:middle; margin: 5px; font-family: arial,sans-serif;">
				<div style="padding:10px;font-size:14px;line-height:18px;text-align:center;" valign="middle" align="center">
					<!--[if mso]>
					<table style="width: 100%;">
					<tr><td height="5" colspan="3"></tr>
					<tr><td width="10"></td>
					<td width="740">
					<![endif]-->
						<h2>'.EMAIL_PASSWORD_RESET_SUBJECT.'!</h2>
						<p>'.EMAIL_PASSWORD_RESET_INTRO.'</p>
						<a href="'.$link.'" style="color: white; background-color: #2271b1; border-radius: 4px; padding: 10px 25px; text-decoration: none;mso-padding-alt:0;"><!--[if mso]><i style="letter-spacing: 25px;mso-font-width:-100%;mso-text-raise:20pt">&nbsp;</i><![endif]--> <span style="mso-text-raise:10pt;font-weight:bold;">'.EMAIL_PASSWORD_RESET_BUTTON.'</span><!--[if mso]><i style="letter-spacing: 25px;mso-font-width:-100%">&nbsp;</i><![endif]--></a>
						<br><br>
						<p style="font-size: 8px; word-break:break-all;">'.$link.'</p>
						<p style="font-size: 8px;">'.EMAIL_PASSWORD_RESET_BOTTOM_INFO.'</p>
					<!--[if mso]>
					</td><td width="10"></td></tr></table>
					<![endif]-->
				</div>
			</div>
		</div>
		';
	}
	
	$email = ob_get_contents();
	ob_end_clean();
	return($email);
}

function getOrderFacts($order) {
	ob_start();
	
	?>
	<div class="column" style="width:100%;max-width:780px;display:inline-block;vertical-align:middle; margin: 5px">
		<div style="padding:10px;font-size:14px;line-height:18px;text-align:left;" valign="middle" align="center">
			<!--[if mso]>
			<table style="width: 100%;">
			<tr><td height="5" colspan="3"></tr>
			<tr><td width="10"></td>
			<td width="370">
			<![endif]-->
			<div style="width: 49%;display:inline-block;" align="left">
				<p style="margin-top:0;margin-bottom:12px;font-family:Arial,sans-serif;font-weight:bold;">Bestellcode </p>
				<p style="margin-top:0;margin-bottom:12px;font-family:Arial,sans-serif;font-weight:bold;">Bestelldatum </p>
				<p style="margin-top:0;margin-bottom:12px;font-family:Arial,sans-serif;font-weight:bold;">Status </p>
			</div>
			<!--[if mso]>
			</td>
			<td width="370">
			<![endif]-->
			<div style="width: 50%;display:inline-block;" align="right">
				<p style="margin-top:0;margin-bottom:14px;font-family:Arial,sans-serif;"><?php echo $order->getOrderCode();?></p>
				<p style="margin-top:0;margin-bottom:14px;font-family:Arial,sans-serif;"><?php echo $order->getOrderDate();?></p>
				<p style="margin-top:0;margin-bottom:14px;font-family:Arial,sans-serif;"><?php echo $order->getOrderStatusName();?></p>
			</div>
			<!--[if mso]>
			</td><td width="10"></td></tr></table>
			<![endif]-->
		</div>
	</div>
	<?php
	$facts = ob_get_contents();
	ob_end_clean();
	return($facts);
}