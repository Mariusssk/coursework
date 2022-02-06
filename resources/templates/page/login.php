<?php
//-----------------Template File---------------------
//File will be included in other files and most focusses on style and not on processing data
//Full login Page
//-----------------Template File---------------------


include(TEMPLATES."/header/header_basic.php");
?>
<div class="fullPageContent">
	<div class="page login" style="background-image: url('<?php echo str_replace("\\","/",IMAGES."/background-stage.jpg");?>')">		
		<div class="loginFormOverlay loginFormContainer">
			<?php include TEMPLATES."/user/login_form.php";?>
		</div>
	</div>
</div>
<?php
include(TEMPLATES."/footer/footer.php");
?>