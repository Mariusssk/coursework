<?php

include_once("../resources/config.php");

//check if user is logged-in

if($session->loggedIn() === True) {
	include(TEMPLATES."/header/header_small.php");
	
	//Check if specific reuqest is send
	
	$requestAllowed = array("overview","new","edit","consumable");
	if(isset($_GET['request']) AND !empty($_GET['request']) AND in_array($_GET['request'],$requestAllowed)) {
		$request = $_GET['request'];
	} else {
		$request = "overview";
	}
	
	//Page
	
	?>
	<script> var protectedPage = true; </script>
	<div class="page item <?php echo $request;?>">
		<?php 
		//Load page data based on request
		if($request == "overview") {
			if($session->checkRights("view_all_items") == True) {
				?>
				<div class="generalTable">
					<div class="row generalTableSearch">
						<div class="td col-md-4 col-sm-12">
							<input type="text" class="generalInput searchInput" data-search-name="name" placeholder="<?php echo ITEM_OVERVIEW_SEARCH_PLACEHOLDER_NAME;?>">
						</div>
						<div class="td col-md-4 col-sm-12">
							<?php echo ItemType::getSelect(array("class"=>"generalSelect searchInput","data"=>array("search-name","type")),0,ITEM_OVERVIEW_SEARCH_PLACEHOLDER_TYPE);?>
						</div>
						<div class="td col-md-2 col-sm-12">
							<div class="generalCheckboxContainer"><?php echo ITEM_OVERVIEW_SEARCH_PLACEHOLDER_CONSUMEABLE;?><input type="checkbox" class="generalCheckbox searchInput" data-search-name="consumable" placeholder="<?php echo ITEM_OVERVIEW_SEARCH_PLACEHOLDER_NAME;?>"></div>
						</div>
						<div class="td col-md-2 col-sm-12">
							<div class="generalSearchBarButton" onclick="loadItems()"> <?php echo WORD_SEARCH;?> </div>
						</div>
						<div class="td d-block d-md-none col-12">
							<div class="generalButton" onclick="loadItems()"> <?php echo WORD_SEARCH;?> </div>
						</div>
					</div>
					<div class="row generalTableHeader">
						<div class="td col-sm-4 col-6">
							<?php echo ITEM_OVERVIEW_SEARCH_PLACEHOLDER_NAME;?>
						</div>
						<div class="td col-sm-4 d-none d-sm-block">
							<?php echo ITEM_OVERVIEW_SEARCH_PLACEHOLDER_TYPE;?>
						</div>
						<div class="td col-sm-2 col-3">
							<?php echo ITEM_OVERVIEW_HEADER_CONSUMEABLE;?>
						</div>
						<div class="td col-sm-2 col-3">
							<?php echo ITEM_OVERVIEW_SEARCH_PLACEHOLDER_AMOUNT;?>
						</div>
					</div>
					<div class="tableContent" id="itemList">
						<div class="row generalTableContentRow">
							<div class="td col-12">
								<?php echo WORD_LOADING;?>
							</div>
						</div>
					</div>
				</div>
				<script>
					//Load items
					loadItems();
				</script>
				<?php
			} else {
				include(TEMPLATES."/user/missing_rights.php");
			}
		} else if($request == "consumable") {
			if($session->checkRights("view_all_items") == True) {
				?>
				<div class="generalTable">
					<div class="row generalTableSearch">
						<div class="td col-md-7 col-sm-12">
							<input type="text" class="generalInput searchInput" data-search-name="name" placeholder="<?php echo ITEM_OVERVIEW_SEARCH_PLACEHOLDER_NAME;?>">
						</div>
						<div class="td col-md-2 col-sm-12">
							<input type="text" class="generalInput searchInput" data-search-name="amount" placeholder="<?php echo ITEM_OVERVIEW_SEARCH_PLACEHOLDER_AMOUNT;?>">
						</div>
						<div class="td col-md-3 col-sm-12">
							<div class="generalSearchBarButton" onclick="loadItems('consumable')"> <?php echo WORD_SEARCH;?> </div>
						</div>
						<div class="td d-block d-md-none col-12">
							<div class="generalButton" onclick="loadItems('consumable')"> <?php echo WORD_SEARCH;?> </div>
						</div>
					</div>
					<div class="row generalTableHeader">
						<div class="td col-sm-4 col-6">
							<?php echo ITEM_OVERVIEW_SEARCH_PLACEHOLDER_NAME;?>
						</div>
						<div class="td col-sm-4 d-none d-sm-block">
							<?php echo ITEM_OVERVIEW_SEARCH_PLACEHOLDER_TYPE;?>
						</div>
						<div class="td col-sm-1 col-2">
							
						</div>
						<div class="td col-sm-3 col-4">
							<?php echo ITEM_OVERVIEW_SEARCH_PLACEHOLDER_AMOUNT;?>
						</div>
					</div>
					<div class="tableContent" id="itemList">
						<div class="row generalTableContentRow">
							<div class="td col-12">
								<?php echo WORD_LOADING;?>
							</div>
						</div>
					</div>
				</div>
				<script>
					//load consumables
					loadItems('consumable');
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

