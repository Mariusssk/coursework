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
				<div class="col-md-6 col-sm-12 col-lg-4 inputBlock">
					<?php echo WORD_EMAIL;?>*:
					<input type="text" class="generalInput" data-input-name="email" value="<?php echo $user->getEmail();?>">
				</div>
				<div class="col-md-6 col-sm-12 col-lg-4 inputBlock">
					<?php echo SETTINGS_PERSONAL_DATA_INPUT_NAME_SCHOOL_EMAIL;?>:
					<input type="text" class="generalInput" data-input-name="schoolEmail" value="<?php echo $user->getSchoolEmail();?>">
				</div>
				<div class="col-md-6 col-sm-12 col-lg-4 inputBlock">
					<?php
					if($user->getPreferredLanguage() == "de") {
						$deSelect = "selected";
						$enSelect = "";
					} else if($user->getPreferredLanguage() == "en") {
						$deSelect = "";
						$enSelect = "selected";
					}
					echo SETTINGS_PERSONAL_DATA_SELECT_LANGUAGE;?>:
					<select class="generalSelect" data-input-name="preferredLanguage">
						<option value="de" <?php echo $deSelect;?>><?php echo LANGUAGE_NAME_GERMAN;?></option>
						<option value="en" <?php echo $enSelect;?>><?php echo LANGUAGE_NAME_ENGLISH;?></option>
					</select>
				</div>
				<div class="col-12">
					<div class="generalButton" onclick="saveUserPersonalData()"><?php echo WORD_SAVE;?></div>
				</div>
				<hr>
				<div class="col-12 passwordChangeButton">
					<div class="generalButton" onclick="changePassword()"><?php echo SETTINGS_PERSONAL_DATA_SET_PASSWORD;?></div>
				</div>
				<div class="col-12 passwordChangeInfoBox none">
					<?php echo SETTINGS_PERSONAL_DATA_PASSWORD_CHANGE_INFO;?>
					<?php echo SETTINGS_PERSONAL_DATA_PASSWORD_CHANGE_CODE;?>: <span class="changeCode"></span>
					<a class="generalButton" href="<?php echo URL;?>/verify"><?php echo SETTINGS_PERSONAL_DATA_SET_PASSWORD;?></a>
				</div>
			</div>
		</div>
		<?php
	} else if($request == "profile") {
		$user = new User;
		if(isset($_GET['username']) AND !empty($_GET['username'])) {
			if(!$user->loadUserByUsername($_GET['username'])) {
				?><h3 class="center"> <?php echo USER_PROFILE_NOT_FOUND;?> </h3><?php
			}
		} else {
			$user->loadData($session->getSessionUserID());
		}
		
		?>
		<div class="page user profile">
			<h2> <?php echo HEADER_MENU_USER_PROFILE;?> </h2>
			<div class="profileContainer">
				<div class="row">
					<div class="col-sm-12 col-md-6">
						<h2><?php echo $user->getUsername();?></h2>
					</div>
					<div class="col-sm-12 col-md-6">
						Name: <?php echo $user->getName(1,1);?><br>
						<?php echo WORD_COMMENTS.": <span style='color: red'>WIP</span>";?><br>
						<?php echo USER_PROFILE_REGISTERED_SINCE.": ".$user->getDateCreated();?>
						<hr>
						<?php echo SETTINGS_PERSONAL_DATA_INPUT_NAME_SCHOOL_EMAIL.": ".$user->getSchoolEmail();?>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	include(TEMPLATES."/footer/footer.php");
} else {
	include(TEMPLATES."/page/login_needed.php");
}

