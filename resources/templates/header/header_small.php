<!--
//-----------------Template File---------------------
//File will be included in other files and most focusses on style and not on processing data
//Website small header (for pages as login)
//-----------------Template File---------------------
-->

<!-- Basics -->

	<?php include(TEMPLATES."/header/header_basic.php");?>
	
		
		<!-- Login overlay -->
		
		<?php include "login_overlay.php";?>
		
		<!-- Header -->
		
		<?php include(TEMPLATES."/header/header_element.php");?>
		
		<div class="pageContent row">
		
			<div class="menuContainer col-sm-12 col-md-3 col-lg-2">
			
				<!-- Menus -->
				
				<?php include(TEMPLATES."/header/menu.php");?>
				
			</div>
			
			<div class="content col-sm-12 col-md-9 col-lg-10">
		