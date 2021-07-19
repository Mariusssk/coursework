<?php

include_once("../resources/config.php");

//check if user is already logged-in

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
} else {
	include(TEMPLATES."/header/header_basic.php");
	
	
	?>
	<div class="page login">
		<img src="<?php echo IMAGES;?>/background-stage.jpg" class="backgroundImage">
		
		<div class="loginFormOverlay loginFormContainer">
			<?php include TEMPLATES."/user/login_form.php";?>
		</div>

	</div>
	<?php
}

?>

<?php

include(TEMPLATES."/footer/footer.php");
