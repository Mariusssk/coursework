<?php

include_once("../resources/config.php");

include(TEMPLATES."/header/header_basic.php");
//check if code has been included in URL
if(isset($_GET['code'])) {
	$code = $_GET['code'];
} else {
	$code = "";
}
?>
<div class="fullPageContent">
	<div class="page verify" style="background-image: url('<?php echo str_replace("\\","/",IMAGES."/background-stage.jpg");?>')">
		
		<div class="verifyContainer">
			<div class="verifyData">
				<div class="inputCodeContainer">
					<div id="verifyInformation" style="color: red;padding-bottom: 8px;"></div>
					<h4>Verification Code*:</h4>
					<input type="text" id="verifyCode" class="generalInput" value="<?php echo $code;?>">
					<div class="generalButton" onclick="verifyEmailRequest()">Verify</div>
				</div>
				<div class="passwordResetContainer none">
					<?php include(TEMPLATES."/user/password_reset_form.php");?>
				</div>
			</div>
			<?php if($session->loggedIn()) {?>
			<a class="generalButton" href="<?php echo URL;?>">Dashbord</a>
			<?php } ?>
		</div>
	</div>
</div>
<?php


?>

<?php

include(TEMPLATES."/footer/footer.php");
