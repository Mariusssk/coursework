<?php

$URL = $_SERVER['REQUEST_URI'];

$URL = str_replace(URL_PATH,"",$URL);

$URL = ltrim($URL, '/');

if($URL == "") {
	$URL = "dashbord";
}
?>

<script>

$( document ).ready(function() {
    setMenuSelected("<?php echo $URL;?>");
});

</script>

<!-- Desktop menu -->
				
<div class="desktopMenuContainer">
	<div class="menuElement" data-destination="dashbord">
		<a href="<?php echo URL;?>">Dashbord</a>
	</div>
	<div class="menuElement" data-sub-menu-parent="Storage" data-destination="storage">
		<a href="<?php echo URL;?>/storage">Storage</a></i>
	</div>
	<div class="subMenu" data-sub-menu="Storage">
		<div class="menuElement subElement" data-destination="storage/new">
			<a href="<?php echo URL;?>/storage/new"><?php echo HEADER_MENU_ITEM_STORAGE_CREATE;?></a>
		</div>
		<div class="menuElement subElement" data-destination="storage">
			<a href="<?php echo URL;?>/storage"><?php echo HEADER_MENU_ITEM_STORAGE_OVERVIEW;?></a>
		</div>
	</div>
	<div class="menuElement" data-destination="todo">
		<a href="<?php echo URL;?>">To-Do</a>
	</div>
	<div class="menuElement" data-destination="events">
		<a href="<?php echo URL;?>">Events</a>
	</div>
	<div class="menuElement" data-destination="settings">
		<a href="<?php echo URL;?>">Settings</a>
	</div>
	<!--<div class="menuElement " data-sub-menu-parent="subMenuTest" data-destination="">
		<a href="<?php echo URL;?>">Test</a></i>
	</div>
	<div class="subMenu" data-sub-menu="subMenuTest">
		<div class="menuElement subElement" data-destination="">
			<a href="<?php echo URL;?>">Test</a>
		</div>
		<div class="menuElement subElement" data-destination="">
			<a href="<?php echo URL;?>">Test</a>
		</div>
	</div>-->
	
</div>

<!-- Mobile menu -->

<div class="mobileMenuContainer none">
	<div class="menuElement" data-destination="dashbord">
		<a href="#">Dashbord</a>
	</div>
	<!--<div class="menuElement" data-destination="">
		<a href="#">Test</a><i class="fa fa-caret-down" data-sub-menu-button="subMenuTest" aria-hidden="true" onclick="subMenu('subMenuTest')"></i>
	</div>
	<div class="subMenu none" data-sub-menu="subMenuTest">
		<div class="menuElement subElement" data-destination="">
			<a href="#">Test</a>
		</div>
		<div class="menuElement subElement" data-destination="">
			<a href="#">Test</a>
		</div>
	</div>-->
	<hr>
	<div class="menuElement" data-destination="login">
		<a href="<?php echo URL;?>/login"><?php echo HEADER_MENU_USER_LOGOUT;?></a>
	</div>
	<div class="menuElement"></div>
	<div class="menuElement"></div>
</div>