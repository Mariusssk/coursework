<!--
//-----------------Template File---------------------
//File will be included in other files and most focusses on style and not on processing data
//HTML header element template
//-----------------Template File---------------------
-->

<header class="header">
		
	<!-- Desktop header -->
	
	<?php 
	$notificationCount = $session->getNotificationCount();
	if($notificationCount >= 1) {
	?>
		<style>
		
		header.header .fa-bell::after {
			background-color: red;
			padding: 4px;
			font-weight: bold;
			border-radius: 5px;
			font-family: Arial, Helvetica, sans-serif;
			font-size: 33%;
			position: relative;
			top: -18px;
			left: -1px;
			color: white;
			content: "<?php echo $notificationCount;?>";
		}
		
		</style>
	<?php } ?>
	
	<div class="desktopHeader">
		<a href="<?php echo URL;?>/notifications"><i class="fa fa-bell" aria-hidden="true"></i></a>
			<div class="headerProfileInfo">
				<div class="profileLink">
					<span class="userInfo"> <?php echo $session->getUserFirstname();?> </span>
					<i class="fa fa-user-circle-o" aria-hidden="true"></i>
				</div>
				<div class="userInfoDropdown">
					<div class="dropdownElement">
						<a href="<?php echo URL;?>/profile"> <?php echo HEADER_MENU_USER_PROFILE;?> </a>
					</div>
					<div class="dropdownElement">
						<a href="<?php echo URL;?>/logout"> <?php echo HEADER_MENU_USER_LOGOUT;?> </a>
					</div>
				</div>
			</div>
		</a>
	</div>
	
	<!-- Mobile header -->
	
	<div class="mobileHeader">
		<i class="fa fa-bars" aria-hidden="true" onclick="mobileMenu()"></i>
		<a href="<?php echo URL;?>/notifications"><i class="fa fa-bell" aria-hidden="true"></i></a>
		<a href="<?php echo URL;?>/profile"><i class="fa fa-user-circle-o" aria-hidden="true"></i></a>
	</div>
	
</header>