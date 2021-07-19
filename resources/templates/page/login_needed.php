<?php

include(TEMPLATES."/header/header_basic.php");

?>

<div class="page 404">
	<img src="<?php echo IMAGES;?>/background-stage.jpg" class="backgroundImage">

	<div class="center">
	
		<h2 style="color: white;"><?php echo ERROR_PAGE_LOGIN_NEEDED;?></h2>
	
		<a href="<?php echo URL;?>/login" class="btn btn-secondary"><?php echo USER_LOGIN_BTN;?></a>
	
	</div>

</div>

<?php

include(TEMPLATES."/footer/footer.php");
