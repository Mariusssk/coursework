<?php

//-----------------New Page---------------------
//Notifications Page / Display notifications
//-----------------New Page---------------------

include_once("../resources/config.php");

//check if user is logged-in

if($session->loggedIn() === True) {
	include(TEMPLATES."/header/header_small.php");
	
	//Check if specific reuqest is send
	
	$requestAllowed = array("list","requestList");
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
	
	//Display list of all current notifications the user has
	//Objective 11/ 11.2
	
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
				<div class="headerPersonal">
					<h3> <?php echo USER_NOTIFICATIONS_SUB_HEADLINE_USER;?> </h3>
					<div class="generalButton markReadButton" onclick="markNotificationsAsRead()"> <?php echo USER_NOTIFICATIONS_BUTTON_MARK_READ;?> </div>
				</div>
				<div style="clear:both;"></div>
				<div class="row personalNotifications">
					
				</div>
			</div>
		</div>
		<script>
			loadPersonalNotifications();
		</script>
		<?php
	} 
	//Page to edit all notification requests the user has made for events or todo lists
	//Objective 11.3
	else if($request == "requestList") {
		?>
		<div class="page notifications requestList">
			<div class="generalTable">
				<div class="row generalTableSearch">
					<div class="td col-md-6 col-sm-12">
						<?php echo AttributeType::getSelect(array("class"=>"generalSelect searchInput","data"=>array("search-name","typeID")),0,NOTIFICATION_REQUEST_LIST_HEADLINE_TYPE);?>
					</div>
					<div class="td col-md-6 col-sm-12">
						<input type="text" class="generalInput searchInput" data-search-name="attributeName" placeholder="<?php echo NOTIFICATION_REQUEST_LIST_HEADLINE_NAME;?>">
					</div>
					<div class="td d-block d-md-none col-12">
						<div class="generalButton" onclick="loadNotificationRequestList()"> <?php echo WORD_SEARCH;?> </div>
					</div>
				</div>
				<div class="row generalTableHeader">
					<div class="td col-sm-4 d-none d-sm-block">
						<?php echo NOTIFICATION_REQUEST_LIST_HEADLINE_TYPE;?>
					</div>
					<div class="td col-sm-4 col-6">
						<?php echo NOTIFICATION_REQUEST_LIST_HEADLINE_NAME;?>
					</div>
					<div class="td col-sm-2 col-3">
						<?php echo NOTIFICATION_REQUEST_LIST_HEADLINE_EMAIL_UPDATE;?>
					</div>
					<div class="td col-sm-2 col-3">
						<?php echo NOTIFICATION_REQUEST_LIST_HEADLINE_DAILY_UPDATE;?>
					</div>
				</div>
				<div class="tableContent requestListContainer">
					<div class="row generalTableContentRow">
						<div class="td col-12">
							<?php echo WORD_LOADING;?>
						</div>
					</div>
				</div>
			</div>
			<script>
				//Load all notification requests
				loadNotificationRequestList();
			</script>
		</div>
		<?php
	}

	include(TEMPLATES."/footer/footer.php");
} else {
	include(TEMPLATES."/page/login_needed.php");
}
?>
