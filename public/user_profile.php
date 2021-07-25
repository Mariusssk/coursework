<?php

include_once("../resources/config.php");

//check if user is logged-in

if($session->loggedIn() === True) {
	include(TEMPLATES."/header/header_small.php");
	
	//Check if specific reuqest is send
	
	$requestAllowed = array("profile","personal_data");
	if(isset($_GET['request']) AND !empty($_GET['request']) AND in_array($_GET['request'],$requestAllowed)) {
		$request = $_GET['request'];
	} else {
		$request = "";
	}
	
	//Page
	
	?>
	<script> var protectedPage = true; </script>
	<?php
	
	//load page based on request
	
	if($request == "personal_data") {
		$user = new User;
		$user->loadData($session->getSessionUserID());
		?>
		<div class="page settings personalData">
			<div class="row">
				<div class="col-md-6 col-sm-12 col-lg-4 inputBlock">
					<?php echo WORD_FIRSTNAME;?>*:
					<input type="text" class="generalInput" data-input-name="firstname" value="<?php echo $user->getName(1,0);?>">
				</div>
				<div class="col-md-6 col-sm-12 col-lg-4 inputBlock">
					<?php echo WORD_LASTNAME;?>*:
					<input type="text" class="generalInput" data-input-name="lastname" value="<?php echo $user->getName(0,1);?>">
				</div>
				<div class="col-md-6 col-sm-12 col-lg-4 inputBlock">
					<?php echo WORD_USERNAME;?>*:
					<input type="text" class="generalInput" data-input-name="username" value="<?php echo $user->getUsername();?>">
				</div>
				<div class="col-md-6 col-sm-12 inputBlock">
					<?php echo WORD_EMAIL;?>*:
					<input type="text" class="generalInput" data-input-name="email" value="<?php echo $user->getEmail();?>">
				</div>
				<div class="col-md-6 col-sm-12 inputBlock">
					<?php echo SETTINGS_PERSONAL_DATA_INPUT_NAME_SCHOOL_EMAIL;?>:
					<input type="text" class="generalInput" data-input-name="schoolEmail" value="<?php echo $user->getSchoolEmail();?>">
				</div>
				<div class="col-12">
					<div class="generalButton" onclick="saveUserPersonalData()"><?php echo WORD_SAVE;?></div>
				</div>
			</div>
		</div>
		<?php
	}

	include(TEMPLATES."/footer/footer.php");
} else {
	include(TEMPLATES."/page/login_needed.php");
}

