<?php

//-----------------New Page---------------------
//Login/Logout Page
//-----------------New Page---------------------

include_once("../resources/config.php");

//check if user is already logged-in
//Else display login/logout form
//Objective 3.3

if(isset($_GET['request']) AND $_GET['request'] == "logout") {
	$session->logout();
	redirect(URL."/login");
} else if($session->loggedIn() === True) {
	include(TEMPLATES."/header/header_small.php");
	?>
	<div class="page logout">
		<div class="row">
			<div class="col-md-4 col-sm-12">
			</div>
			<div class="col-md-4 col-sm-12 center">
				<h2> <?php echo USER_LOGED_IN_STATUS;?> </h2>
				<p> <?php echo WORD_WELCOME." ".$session->getUserFirstname()." ".$session->getUserLastname();?>! </p>
				<div class="generalButton" onclick="submitLogout()"><?php echo USER_LOGOUT_BTN;?></div>
			</div>
			
		</div>
	</div>
	<?php
	include(TEMPLATES."/footer/footer.php");
} else {
	include(TEMPLATES."/page/login.php");
}

?>
