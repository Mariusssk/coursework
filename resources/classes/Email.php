<?php
//-----------------New PHP Class File---------------------

class Email {
	
	protected $reciver, $subject, $content, $mailInstance;
	
	function __construct($reciver = "",$subject = "",$content = "") {
		if(is_array($reciver) AND count($reciver) == 2) {
			$this->reciver = $reciver;
		}
		$this->subject = $subject;
		$this->content = $content;
		$this->mailInstance = new PHPMailer\PHPMailer\PHPMailer();
		$this->mailInstance->CharSet = 'UTF-8';
		return(True);
	}
	
	function createEmail($type,$userID,$contentAttributes = array()) {
		$user = new User;
		if(!empty($type) AND $user->loadData($userID)) {
			include TEMPLATES."/email/footer.php";
			include TEMPLATES."/email/header.php";
			include TEMPLATES."/email/email.php";
			if($type == "confirmEmail" OR $type == "confirmSchoolEmail") {
				if($type == "confirmEmail") {
					$this->reciver = array($user->getEmail(),$user->getName(1,1));
				} else if($type == "confirmSchoolEmail") {
					$this->reciver = array($user->getSchoolEmail(),$user->getName(1,1));
				}
				
				$this->subject = EMAIL_CONFIRM_EMAIL_SUBJECT;
				
				$content = "";
				$content .= Template\Email\getEmailHeader($type);
				$content .= Template\Email\getEmailContent($type,$contentAttributes);
				$content .= Template\Email\getEmailFooter($type);
				
				$this->content = $content;
				$this->prepareEmail();
				if($this->sendEmail()) {
					return(True);
				}
			} else if($type == "resetPassword") {
				$this->reciver = array($user->getEmail(),$user->getName(1,1));
				
				$this->subject = EMAIL_PASSWORD_RESET_SUBJECT;
				
				$content = "";
				$content .= Template\Email\getEmailHeader($type);
				$content .= Template\Email\getEmailContent($type,$contentAttributes);
				$content .= Template\Email\getEmailFooter($type);
				
				$this->content = $content;
				$this->prepareEmail();
				if($this->sendEmail()) {
					return(True);
				}
			} else if($type == "notification") {
				$this->reciver = array($user->getEmail(),$user->getName(1,1));
				
				$this->subject = EMAIL_NOTIFICATION_SUBJECT;
				
				$content = "";
				$content .= Template\Email\getEmailHeader($type);
				$content .= Template\Email\getEmailContent($type,$contentAttributes);
				$content .= Template\Email\getEmailFooter($type);
				
				$this->content = $content;
				$this->prepareEmail();
				if($this->sendEmail()) {
					return(True);
				}
			}
		}
		return(False);
	}
	
	
	function prepareEmail() {
		$this->mailInstance->isSMTP();
		$this->mailInstance->Host = 's203.goserver.host';
		$this->mailInstance->SMTPAuth = true;
		$this->mailInstance->Username = 'web144p7';
		$this->mailInstance->Password = 'sq1FObJA9ShKcDwo';
		$this->mailInstance->SMTPSecure = 'ssl';
		$this->mailInstance->Port = 465;

		$this->mailInstance->setFrom('aulatechnik_system@color-site-web.de', 'Aulatechnik System');
		$this->mailInstance->addReplyTo('aulatechnik_system@color-site-web.de', 'Aulatechnik System');
		$this->mailInstance->addAddress($this->reciver[0], $this->reciver[1]); 
		$this->mailInstance->Subject = $this->subject;
		$this->mailInstance->isHTML(true);
		$this->mailInstance->Body = $this->content;
	}
	
	
	function sendEmail() {
		if($this->mailInstance->send()) {
			return(True);
		} else {
			echo 'Message could not be sent.';
			echo 'Mailer Error: ' . $this->mailInstance->ErrorInfo;
			return(False);
		}
	}
}

?>