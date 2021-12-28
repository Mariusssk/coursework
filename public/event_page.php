<?php

include_once("../resources/config.php");

//check if user is logged-in

if($session->loggedIn() === True) {
	include(TEMPLATES."/header/header_small.php");
	
	//Check if specific reuqest is send
	
	$requestAllowed = array("overview","locations","clients");
	if(isset($_GET['request']) AND !empty($_GET['request']) AND in_array($_GET['request'],$requestAllowed)) {
		$request = $_GET['request'];
	} else {
		$request = "overview";
	}
	//Page
	
	?>
	<script> var protectedPage = true; </script>
	<div class="page event <?php echo $request;?>">
		<?php 
		//Load page data based on request
		if($request == "clients") {
			if($session->checkRights("edit_event_clients") == True) {
				//Load list of all event clients
				?>
				<div class="generalTable">
					<div class="row generalTableSearch">
						<div class="td col-md-4 col-sm-12">
							<input type="text" class="generalInput searchInput" data-search-name="name" placeholder="<?php echo EVENT_CLIENT_LIST_HEADER_NAME;?>">
						</div>
						<div class="td col-md-2 col-sm-12">
								<div class="generalCheckboxContainer"><?php echo EVENT_CLIENT_LIST_HEADER_EXTERNAL;?><input type="checkbox" class="generalCheckbox searchInput" data-search-name="external" placeholder="<?php echo ITEM_OVERVIEW_SEARCH_PLACEHOLDER_NAME;?>"></div>
							</div>
						<div class="td d-none d-md-block col-md-3">
							<div class="generalSearchBarButton" onclick="loadClientList()"> <?php echo WORD_SEARCH;?> </div>
						</div>
						<div class="td d-block d-md-none col-12">
							<div class="generalButton" onclick="loadClientList()"> <?php echo WORD_SEARCH;?> </div>
						</div>
						<div class="td d-none d-md-block col-md-3">
							<div class="generalSearchBarButton createNewToDoBtn" onclick="createNewClient()"> <?php echo WORD_NEW;?> </div>
						</div>
						<div class="td d-block d-md-none col-12">
							<div class="generalButton createNewToDoBtn" onclick="createNewClient()"> <?php echo WORD_NEW;?> </div>
						</div>
					</div>
					<div class="row generalTableHeader">
						<div class="td col-8 col-md-4">
							<?php echo EVENT_CLIENT_LIST_HEADER_NAME;?>
						</div>
						<div class="td col-4 col-md-2">
							<?php echo EVENT_CLIENT_LIST_HEADER_EXTERNAL;?>
						</div>
						<div class="td col-8 col-md-4">
							<?php echo EVENT_CLIENT_LIST_HEADER_DESCRIPTION;?>
						</div>
						<div class="td col-4 col-md-2">
							<?php echo WORD_ACTION;?>
						</div>
					</div>
					<div class="tableContent" id="clientList">
						<div class="row generalTableContentRow">
							<div class="td col-12">
								<?php echo WORD_LOADING;?>
							</div>
						</div>
					</div>
				</div>
				<script>
					//load all clients
					loadClientList();
				</script>
				<?php
			} else {
				include(TEMPLATES."/user/missing_rights.php");
			}
		}
		?>
	</div>
	<?php
	include(TEMPLATES."/footer/footer.php");
} else {
	include(TEMPLATES."/page/login_needed.php");
}

