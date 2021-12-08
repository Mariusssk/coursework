<?php

include_once("../resources/config.php");

//check if user is logged-in

if($session->loggedIn() === True) {
	include(TEMPLATES."/header/header_small.php");
	
	//Check if specific reuqest is send
	
	$requestAllowed = array("displayScanner","displayAttribute");
	if(isset($_GET['request']) AND !empty($_GET['request']) AND in_array($_GET['request'],$requestAllowed)) {
		$request = $_GET['request'];
	} else {
		$request = "displayScanner";
	}

	//Page
	
	?>
	<script> var protectedPage = true; </script>
	<div class="page scan <?php echo $request;?>">
		<?php 
		//Load page data based on request
		if($request == "displayScanner") {
			?>
			<div class="barcodeReaderContainer none"><div class="closeWindow" onclick="closeScanner()"> <i class="fa fa-window-close" aria-hidden="true"></i></div><div id="barcodeReader" class="barcodeReader"></div></div>

			<div class="fullPageContent">
				<div class="generalButton" onclick="openScanner('scan')"><?php echo SCANNER_SCAN_OPEN_SCANNER;?></div>
				<input type="text" class="generalInput" placeholder="<?php echo SCANNER_SCAN_DATA_INPUT;?>" data-input-name="scanData">
				<div class="generalButton" onclick="findScanData()"> <?php echo SCANNER_SCAN_FIND_DATA;?></div>
			</div>
			<?php
			if(
				isset($_GET['attribute']) AND !empty($_GET['attribute']) AND
				isset($_GET['type']) AND !empty($_GET['type'])
			) {
				?>
				<script>
					findScanData("<?php echo $_GET['type'].'#'.$_GET['attribute']?>");
				</script>
				<?php
			}
		} else if($request == "displayAttribute") {
			if(isset($_GET['type']) AND isset($_GET['attribute']) AND ($_GET['type'] == "item" OR $_GET['type'] == "storage")) {
				$type = $_GET['type'];
				$attributID = $_GET['attribute'];
				$item = new Item;
				$storage = new Storage;
				
				//check if data can be loaded
				
				if(
					($type == "item" AND $item->loadData($attributID)) OR
					($type == "storage" AND $storage->loadData($attributID))
				) {
					
					//check if user has rights
					if(
						($type == "item" AND $session->checkRights("view_specific_item") == True) OR
						($type == "storage" AND $session->checkRights("view_items_specific_storage") == True)
					) {
						
						//Display data
						
						if($type == "item") {
							
							$storageGrid = False;

							if($storage->loadData($item->getStorageID()) AND $storage->getTypeID() == 3) {
								$shelf = new StorageShelf;
								if($shelf->loadData($storage->getParentID())) {
									$storageGrid = True;
								}
							}
							
							?>
							<div class="row">
								<div class="col-12 col-md-6">
									<div class="row">
										<div class="col-12">
											<b><?php echo ITEM_EDIT_INPUTNAME_NAME;?>:</b>
										</div>
										<div class="col-12">
											<?php echo $item->getName();?>
										</div>
									</div>
								</div>
								<div class="col-12 col-md-6">
									<div class="row">
										<div class="col-6">
											<b><?php echo ITEM_EDIT_INPUTNAME_LENGTH;?>:</b>
										</div>
										<div class="col-6">
											<b><?php echo ITEM_EDIT_INPUTNAME_AMOUNT;?>:</b>
										</div>
										<div class="col-6">
											<?php echo $item->getLength();?>
										</div>
										<div class="col-6">
											<?php echo $item->getActualAmount()." (".$item->getAmount().")";?>
										</div>
									</div>
								</div>
								<div class="col-12 col-md-6">
									<div class="row">
										<div class="col-12">
											<b><?php echo ITEM_EDIT_INPUTNAME_TYPE;?>:</b>
										</div>
										<div class="col-12">
											<?php echo $item->getTypeName();?>
										</div>
									</div>
								</div>
								<div class="col-12 col-md-6">
									<div class="row">
										<div class="col-12">
											<b><?php echo ITEM_EDIT_INPUTNAME_STORAGE;?>:</b>
										</div>
										<div class="col-12">
											<?php echo $item->getStorageString();?>
										</div>
									</div>
								</div>
								<div class="col-12 col-md-6">
									<div class="row">
										<div class="col-12">
											<b><?php echo ITEM_EDIT_INPUTNAME_DESCRIPTION;?>:</b>
										</div>
										<div class="col-12">
											<?php echo $item->getDescription();?>
										</div>
									</div>
								</div>
								<div class="col-12">
									<?php
									if($storageGrid == True) {
									?>
										<hr>
										<div class="shelfAlignmentContainer">
											<div class="shelfGrid desktopGrid">
												<?php echo $shelf->displayGrid(); ?>
											</div>
											<div class="shelfGrid mobileGrid">
												<?php echo $shelf->displayGrid(); ?>
											</div>
										</div>
									<?php
									}
									?>
								</div>
							</div>
							<?php
							
						} else if($type == "storage") {
							$subStorages = $storage->loadSubStorages();
							array_push($subStorages, $storage->getID());
							
							//create string from storages
							
							$storageString = "";
							
							foreach($subStorages as $tmpStorage) {
								if(!empty($storageString)) {$storageString .= ";";}
								$storageString .= $tmpStorage;
							}
							
							
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
										<div class="generalSearchBarButton" onclick="loadItems('view')"> <?php echo WORD_SEARCH;?> </div>
									</div>
									<div class="td d-block d-md-none col-12">
										<div class="generalButton" onclick="loadItems('view')"> <?php echo WORD_SEARCH;?> </div>
									</div>
									
									<input type="hidden" class="generalInput searchInput none" data-search-name="storages" value="<?php echo $storageString;?>">
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
								loadItems("view");
							</script>
							<?php
						}
						
					} else {
						include(TEMPLATES."/user/missing_rights.php");
					}
				} else {
					?>
					<div class="center">
						<h4> <?php echo SCANNER_VIEW_DATA_NOT_FOUND;?> </h4>
					</div>
					<?php
				}
			} else {
				?>
				<div class="center">
					<h4> <?php echo SCANNER_VIEW_TYPE_NOT_VALID;?> </h4>
				</div>
				<?php
			}
		}
		?>
	</div>
	<?php
	include(TEMPLATES."/footer/footer.php");
} else {
	include(TEMPLATES."/page/login_needed.php");
}

