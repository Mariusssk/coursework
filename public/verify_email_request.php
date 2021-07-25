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
<div class="page verify">
	<img src="<?php echo IMAGES;?>/background-stage.jpg" class="backgroundImage">
		
	<div class="verifyContainer">
		<div class="inputCodeContainer">
			<div id="verifyInformation" style="color: red;padding-bottom: 8px;"></div>
			<h4>Verification Code*:</h4>
			<input type="text" id="verifyCode" class="generalInput" value="<?php echo $code;?>">
			<div class="generalButton" onclick="verifyEmailRequest()">Verify</div>
			<a class="generalButton" href="<?php echo URL;?>">Dashbord</a>
		</div>
		<div class="passwordResetContainer none">
			<?php include(TEMPLATES."/user/password_reset_form.php");?>
		</div>
	</div>
</div>
<?php


?>

<?php

include(TEMPLATES."/footer/footer.php");
