<?php

include(TEMPLATES."/header/header_basic.php");

?>
<div class="fullPageContent">
	<div class="page 404 loginNeeded" style="background-image: url('<?php echo str_replace("\\","/",IMAGES."/background-stage.jpg");?>')">
		<div class="center">
		
			<h2 style="color: white;"><?php echo ERROR_PAGE_LOGIN_NEEDED;?></h2>
		
			<a href="<?php echo URL;?>/login" class="btn btn-secondary"><?php echo USER_LOGIN_BTN;?></a>
		
		</div>

	</div>
</div>

<?php

include(TEMPLATES."/footer/footer.php");
