<?php

//-----------------New Page---------------------
//Item/ Hardware Page (create, edit, etc);
//-----------------New Page---------------------

include_once("../resources/config.php");

//check if user is logged-in

if($session->loggedIn() === True) {
	include(TEMPLATES."/header/header_small.php");
	
	//Check if specific reuqest is send
	
	$requestAllowed = array("overview","new","edit","consumable","lended","addLend");
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
		// List of all items 
		//Objective 4
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
						<div class="td col-md-2 col-sm-12 d-none d-md-block ">
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
		} 
		//Display page to list all consumable items in storage
		//Objective 4.4.1
		else if($request == "consumable") {
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
		//Form to either edit or create a new hardware/item
		//Objective 4.1 / 4.1.1
		
		else if($request == "new" OR $request == "edit") {
			//check rights for edit/new
			if(($request == "edit" AND $session->checkRights("edit_item") == True) OR ($request == "new" AND $session->checkRights("create_new_item") == True)) {
				$item = new Item;
				if(isset($_GET['ID'])) {
					if(!$item->loadData($_GET['ID'])) {
						echo '<h2 class="red">'.ITEM_EDIT_DATA_LOAD_FAILURE."</h2>";
					}
				}
				
				//display form
				?>
				<div class="row">
					<div class="col-sm-12 col-md-6 inputBlock">
						<?php echo ITEM_EDIT_INPUTNAME_NAME;?>*:<br>
						<input type="text" class="generalInput dataInput" data-input-name="name" value="<?php echo $item->getName();?>"><br>
					</div>
					<div class="col-sm-6 col-md-3 inputBlock">
						<?php echo ITEM_EDIT_INPUTNAME_LENGTH;?>:<br>
						<input type="number" class="generalInput dataInput" data-input-name="length" value="<?php echo $item->getLength();?>"><br>
					</div>
					<div class="col-sm-6 col-md-3 inputBlock">
						<?php echo ITEM_EDIT_INPUTNAME_AMOUNT;?>:<br>
						<input type="number" class="generalInput dataInput" data-input-name="amount" value="<?php echo $item->getAmount();?>"><br>
					</div>
					<div class="col-sm-12 col-md-6 inputBlock" data-input-name="type">
						<?php echo ITEM_EDIT_INPUTNAME_TYPE;?>*:<br>
						<?php echo ItemType::getSelect(array("class"=>"generalSelect dataInput","data"=>array("input-name","type")),$item->getTypeID());?>
					</div>
					<div class="col-sm-12 col-md-6 inputBlock" data-input-name="storage">
						<?php echo ITEM_EDIT_INPUTNAME_STORAGE;?>:<br>
						<?php echo Storage::getSelect(array("class"=>"generalSelect dataInput","data"=>array("input-name","storage")),$item->getStorageID());?>
					</div>
					<div class="col-sm-12 inputBlock" data-input-name="storage">
						<?php echo ITEM_EDIT_INPUTNAME_DESCRIPTION;?>:<br>
						<textarea class="generalInput dataInput" data-input-name="description"><?php echo $item->getDescription();?></textarea><br>
					</div>
					<?php 
					//check if user has right do delete
					if($session->checkRights("delete_item") == True AND $request == "edit") {
						?>
						<div class="col-12 col-md-6">
							<div class="generalButton" onclick="saveItemData('<?php echo $item->getID();?>')"> <?php echo WORD_SAVE;?> </div>
						</div>
						<div class="col-12 col-md-6" id="checkDeleteContainer">
							<div class="generalButton" onclick="deleteItem('<?php echo $item->getID();?>','check')"> <?php echo WORD_DELETE;?> </div>
						</div>
						<div class="col-12 col-md-6 none" id="confirmDeleteContainer">
							<div class="generalButton" onclick="deleteItem('<?php echo $item->getID();?>','confirm')"> <?php echo WORD_DELETE;?> </div> <div class="generalButton" onclick="deleteItem('<?php echo $item->getID();?>','abort')"> <?php echo WORD_ABORT;?> </div>
						</div>
						<?php
					} else {
						?>
						<div class="col-12">
							<div class="generalButton" onclick="saveItemData('<?php echo $item->getID();?>')"> <?php echo WORD_SAVE;?> </div>
						</div>
						<?php
					}?>
					
				</div>
				<?php
				
			} else {
				include(TEMPLATES."/user/missing_rights.php");
			}
		} 
		//Page to give the user the option to lend items // show items lend in past
		//Objective 4.5
		
		else if($request == "lended") {
			if($session->checkRights("lend_item") == True) {
				?>
				<div class="generalTable">
					<div class="row generalTableSearch">
						<div class="td col-md-4 col-sm-12">
							<input type="text" class="generalInput searchInput" data-search-name="name" placeholder="<?php echo ITEM_OVERVIEW_SEARCH_PLACEHOLDER_NAME;?>">
						</div>
						<div class="td col-md-4 col-sm-12">
							<?php echo ItemType::getSelect(array("class"=>"generalSelect searchInput","data"=>array("search-name","type")),0,ITEM_OVERVIEW_SEARCH_PLACEHOLDER_TYPE);?>
						</div>
						<div class="td col-md-2 col-sm-12 d-none d-md-block ">
							<div class="generalSearchBarButton" onclick="loadItems('lend')"> <?php echo WORD_SEARCH;?> </div>
						</div>
						<div class="td col-md-2 col-sm-12 d-none d-md-block ">
							<div class="generalSearchBarButton" style="background-color: green;" onclick="addLendItem()"> <?php echo ITEM_OVERVIEW_HEADER_ADD_LEND;?> </div>
						</div>
						<div class="td d-block d-md-none col-12">
							<div class="generalSearchBarButton" onclick="loadItems('lend')"> <?php echo WORD_SEARCH;?> </div>
						</div>
						<div class="td d-block d-md-none col-12">
							<div class="generalSearchBarButton" style="background-color: green;" onclick="addLendItem()"> <?php echo ITEM_OVERVIEW_HEADER_ADD_LEND;?> </div>
						</div>
					</div>
					<div class="row generalTableHeader">
						<div class="td col-sm-4 col-6">
							<?php echo ITEM_OVERVIEW_SEARCH_PLACEHOLDER_NAME;?>
						</div>
						<div class="td col-sm-3 d-none d-sm-block">
							<?php echo ITEM_OVERVIEW_SEARCH_PLACEHOLDER_TYPE;?>
						</div>
						<div class="td col-sm-2 col-6">
							<?php echo ITEM_OVERVIEW_SEARCH_PLACEHOLDER_AMOUNT;?>
						</div>
						<div class="td col-sm-2 d-none d-sm-block">
							<?php echo ITEM_EDIT_LEND_HEADLINE_RETURN_DATE;?>
						</div>
						<div class="td col-sm-1 d-none d-sm-block">
							<?php echo ITEM_EDIT_LEND_HEADLINE_RETURN;?>
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
				<small> <?php echo ITEM_EDIT_LEND_INFO_DATE_CHANGE;?> </small>
				<script>
					//Load items
					loadItems('lend');
				</script>
				<?php
			} else {
				include(TEMPLATES."/user/missing_rights.php");
			}
		} 
		//Page to select objective to add to already lend objects
		//Objective 4.5.1
		else if($request == "addLend") {
			if($session->checkRights("lend_item") == True) {
				?>
				<div class="generalTable">
					<div class="row generalTableSearch">
						<div class="td col-md-4 col-sm-12">
							<input type="text" class="generalInput searchInput" data-search-name="name" placeholder="<?php echo ITEM_OVERVIEW_SEARCH_PLACEHOLDER_NAME;?>">
						</div>
						<div class="td col-md-4 col-sm-12">
							<?php echo ItemType::getSelect(array("class"=>"generalSelect searchInput","data"=>array("search-name","type")),0,ITEM_OVERVIEW_SEARCH_PLACEHOLDER_TYPE);?>
						</div>
						<div class="td col-md-2 col-sm-12 d-none d-md-block ">
							<div class="generalSearchBarButton" onclick="loadItems('addLend')"> <?php echo WORD_SEARCH;?> </div>
						</div>
						<div class="td col-md-2 col-sm-12 d-none d-md-block ">
							<div class="generalSearchBarButton" style="background-color: green;" onclick="addLendItem('1')"> <?php echo ITEM_OVERVIEW_HEADER_RETURN_LEND;?> </div>
						</div>
						<div class="td d-block d-md-none col-12">
							<div class="generalSearchBarButton" onclick="loadItems('addLend')"> <?php echo WORD_SEARCH;?> </div>
						</div>
						<div class="td d-block d-md-none col-12">
							<div class="generalSearchBarButton" style="background-color: green;" onclick="addLendItem('1')"> <?php echo ITEM_OVERVIEW_HEADER_RETURN_LEND;?> </div>
						</div>
					</div>
					<div class="row lendNewItemConatiner none">
						<input type="hidden" value="0" id="lendItemFormID">
						<hr>
						<h5> <?php echo ITEM_EDIT_LEND_ADD_HEADLINE;?> </h5> 
						<div class="col-12 col-sm-3 td">
							<span class="itemName"></span>
						</div>
						<div class="col-12 col-sm-3 td">
							<input type="number" id="lendItemFormAmount" class="generalInput" min="0" placeholder="<?php echo ITEM_OVERVIEW_SEARCH_PLACEHOLDER_AMOUNT;?>">
						</div>
						<div class="col-12 col-sm-3 td">
							<input type="date" id="lendItemFormDate" class="generalInput" placeholder="<?php echo ITEM_OVERVIEW_SEARCH_PLACEHOLDER_AMOUNT;?>">
						</div>
						<div class="col-12 col-sm-3">
							<div class="generalButton" onclick="submitLendItem()"> <?php echo WORD_ADD;?> </div>
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
							<?php echo ITEM_OVERVIEW_SEARCH_PLACEHOLDER_AMOUNT;?>
						</div>
						<div class="td col-sm-2 col-3">
							Action
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
					loadItems("addLend");
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

?>