<?php

include(TEMPLATES."/header/header_basic.php");

?>
<div class="fullPageContent">
	<div class="page 404 notFound" style="background-image: url('<?php echo str_replace("\\","/",IMAGES."/background-stage.jpg");?>')">

		<div class="center">
		
			<h2 style="color: white;"><?php echo LANG_404_ERROR;?></h2>
		
			<a href="<?php echo URL;?>" class="btn btn-secondary">Dashbord</a>
		</div>

	</div>
</div>
<?php

include(TEMPLATES."/footer/footer.php");
