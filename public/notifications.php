<?php

include_once("../resources/config.php");

//check if user is logged-in

if($session->loggedIn() === True) {
	include(TEMPLATES."/header/header_small.php");
	
	//Check if specific reuqest is send
	
	$requestAllowed = array("list");
	if(isset($_GET['request']) AND !empty($_GET['request']) AND in_array($_GET['request'],$requestAllowed)) {
		$request = $_GET['request'];
	} else {
		$request = "";
	}
	
	//Page
	
	?>
	<script> var protectedPage = true; </script>
	<?php
	
	//load page based on request
	
	if($request == "list") {
		?>
		<div class="page notifications list">
			<h2> <?php echo USER_NOTIFICATIONS_HEADLINE;?> </h2>
			<hr>
			<div class="profileContainer">
				<h3> <?php echo USER_NOTIFICATIONS_SUB_HEADLINE_SYSTEM;?> </h3>
				<div class="row">
					<?php 
					$systemNotification = new SystemNotifications($session->getSessionUserID());
					if($systemNotification->countNotifications() <= 0) {
						?>
						<div class="col-12 emptyList center">
							<?php echo USER_NOTIFICATIONS_EMPTY_LIST;?> 
						</div>
						<?php
					} else {
						echo $systemNotification->displayNotifications();
					}
					?>
				</div>
				<hr>
				<h3> <?php echo USER_NOTIFICATIONS_SUB_HEADLINE_USER;?> </h3>
				<div class="row">
					<div class="col-12 notificationContainer">
						Test
					</div>
					<div class="col-12 notificationContainer">
						Test
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	include(TEMPLATES."/footer/footer.php");
} else {
	include(TEMPLATES."/page/login_needed.php");
}

