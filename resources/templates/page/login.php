<?php
include(TEMPLATES."/header/header_basic.php");
?>
<div class="fullPageContent">
	<div class="page login" style="background-image: url('<?php echo str_replace("\\","/",IMAGES."/background-stage.jpg");?>')">>
		<div class="loginFormOverlay loginFormContainer">
			<div id="loginForm">
				<?php include TEMPLATES."/user/login_form.php";?>
			</div>
			<div id="passwordResetRequest" class="none">
				<input type="text" class="generalInput" data-input-name="username" placeholder="<?php echo USER_USERNAME;?>">
				<div class="generalButton"> </div>
			</div>
		</div>
	</div>
</div>
<?php
include(TEMPLATES."/footer/footer.php");