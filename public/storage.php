<?php

include_once("../resources/config.php");

//check if user is logged-in

if($session->loggedIn() === True) {
	include(TEMPLATES."/header/header_small.php");
	
	//Check if specific reuqest is send
	
	$requestAllowed = array("overview","new","edit");
	if(isset($_GET['request']) AND !empty($_GET['request']) AND in_array($_GET['request'],$requestAllowed)) {
		$request = $_GET['request'];
	} else {
		$request = "overview";
	}
	
	//Page
	
	?>
	<div class="page storage <?php echo $request;?> container">
		<?php 
		//Load page data based on request
		if($request == "overview") {
			if($session->checkRights("view_storages") == True) {
				?>
				<div class="generalTable">
					<div class="row generalTableSearch">
						<div class="td col-md-4 col-sm-12">
							<input type="text" class="generalInput" placeholder="<?php echo STORAGE_OVERVIEW_SEARCH_NAME;?>">
						</div>
						<div class="td col-md-4 col-sm-12">
							<input type="text" class="generalInput" placeholder="<?php echo STORAGE_OVERVIEW_SEARCH_PARENT_NAME;?>">
						</div>
						<div class="td col-md-4 col-sm-12">
							<?php echo StorageType::getSelect(array("class"=>"generalSelect"));?>
						</div>
					</div>
					<div class="row generalTableHeader">
						<div class="td col-sm-4 col-6">
							<?php echo STORAGE_OVERVIEW_SEARCH_NAME;?>
						</div>
						<div class="td col-sm-4 d-none d-sm-block">
							<?php echo STORAGE_OVERVIEW_SEARCH_PARENT_NAME;?>
						</div>
						<div class="td col-sm-4 col-6">
							<?php echo STORAGE_OVERVIEW_SEARCH_TYPE;?>
						</div>
					</div>
					<div class="tableContent" id="storageList">
						<div class="row generalTableContentRow">
							<div class="td col-sm-4 col-6">
								Test
							</div>
							<div class="td col-sm-4 d-none d-sm-block">
								Test
							</div>
							<div class="td col-sm-4 col-6">
								Room
							</div>
						</div>
					</div>
				</div>
				<?php
			} else {
				include(TEMPLATES."/user/missing_rights.php");
			}
		} else if($request == "new" OR $request == "edit") {
			
		}
		?>
	</div>
	<?php
	include(TEMPLATES."/footer/footer.php");
} else {
	include(TEMPLATES."/page/login_needed.php");
}

