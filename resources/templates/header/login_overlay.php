<?php

//-----------------Template File---------------------
//File will be included in other files and most focusses on style and not on processing data
//Login Form
//-----------------Template File---------------------


if(isset($_POST['loginNotice']) AND !empty($_POST['loginNotice'])) {
	$loginNotice = $_POST['loginNotice'];
} else {
	$loginNotice = "";
}

?>
<div class="headerLoginOverlay none">
	<h2><?php echo HEADER_OVERLAY_SESSION_ENDED;?></h2>
	<div class="loginFormOverlay">
		<?php include TEMPLATES."/user/login_form.php";?>
	</div>
</div>
