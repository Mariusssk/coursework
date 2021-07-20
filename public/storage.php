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
	<script> var protectedPage = true; </script>
	<div class="page storage <?php echo $request;?>">
		<?php 
		//Load page data based on request
		if($request == "overview") {
			if($session->checkRights("view_storages") == True) {
				?>
				<div class="generalTable">
					<div class="row generalTableSearch">
						<div class="td col-md-4 col-sm-12">
							<input type="text" class="generalInput searchInput" data-search-name="name" placeholder="<?php echo STORAGE_OVERVIEW_SEARCH_NAME;?>">
						</div>
						<div class="td col-md-4 col-sm-12">
							<input type="text" class="generalInput searchInput" data-search-name="parentName" placeholder="<?php echo STORAGE_OVERVIEW_SEARCH_PARENT_NAME;?>">
						</div>
						<div class="td col-md-4 col-sm-12">
							<?php echo StorageType::getSelect(array("class"=>"generalSelect searchInput","data"=>array("search-name","type")));?>
						</div>
						<div class="td d-block d-md-none col-12">
							<div class="generalButton" onclick="loadStorages()"> <?php echo WORD_SEARCH;?> </div>
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
							<div class="td col-12">
								<?php echo WORD_LOADING;?>
							</div>
						</div>
					</div>
				</div>
				<script>
					loadStorages();
				</script>
				<?php
			} else {
				include(TEMPLATES."/user/missing_rights.php");
			}
		} else if($request == "new" OR $request == "edit") {
			//check rights for edit/new
			if(($request == "edit" AND $session->checkRights("edit_storage") == True) OR ($request == "new" AND $session->checkRights("create_new_storage") == True)) {
				$storage = new Storage;
				if(isset($_GET['ID'])) {
					$storage->loadData($_GET['ID']);
				}
				
				if($storage->getTypeID() == 2) {
					$storage = new StorageShelf;
					$storage->loadData($_GET['ID']);
				}
				//display form
				?>
				<div class="row">
					<div class="col-sm-12 col-md-6 inputBlock">
						<?php echo STORAGE_OVERVIEW_SEARCH_NAME;?>*:<br>
						<input type="text" class="generalInput dataInput" data-input-name="name" value="<?php echo $storage->getName();?>"><br>
					</div>
					<div class="col-sm-6 col-md-3 inputBlock">
						X:<br>
						<input type="number" class="generalInput dataInput" data-input-name="x" value="<?php echo $storage->getX();?>"><br>
					</div>
					<div class="col-sm-6 col-md-3 inputBlock">
						Y:<br>
						<input type="number" class="generalInput dataInput" data-input-name="y" value="<?php echo $storage->getY();?>"><br>
					</div>
					<div class="col-sm-12 col-md-6 inputBlock" data-input-name="name">
						<?php echo STORAGE_OVERVIEW_SEARCH_TYPE;?>*:<br>
						<?php echo StorageType::getSelect(array("class"=>"generalSelect dataInput","data"=>array("input-name","type")),$storage->getTypeID());?>
					</div>
					<div class="col-sm-12 col-md-6 inputBlock">
						<?php echo STORAGE_PLACEHOLDER_PARENT;?>:<br>
						<select class="generalSelect dataInput" id="storageParentSelect" data-input-name="parentID">
							<option selected disabled value="0"> </option>
						</select>
					</div>
					<?php 
					//check if user has right do delete
					if($session->checkRights("delete_storage") == True AND $request == "edit") {
						?>
						<div class="col-12 col-md-6">
							<div class="generalButton" onclick="saveStorageData('<?php echo $storage->getID();?>')"> <?php echo WORD_SAVE;?> </div>
						</div>
						<div class="col-12 col-md-6" id="checkDeleteContainer">
							<div class="generalButton" onclick="deleteStorage('<?php echo $storage->getID();?>','check')"> <?php echo WORD_DELETE;?> </div>
						</div>
						<div class="col-12 col-md-6 none" id="confirmDeleteContainer">
							<div class="generalButton" onclick="deleteStorage('<?php echo $storage->getID();?>','confirm')"> <?php echo WORD_DELETE;?> </div> <div class="generalButton" onclick="deleteStorage('<?php echo $storage->getID();?>','abort')"> <?php echo WORD_ABORT;?> </div>
						</div>
						<?php
					} else {
						?>
						<div class="col-12">
							<div class="generalButton" onclick="saveStorageData('<?php echo $storage->getID();?>')"> <?php echo WORD_SAVE;?> </div>
						</div>
						<?php
					}?>
					
				</div>
				<script>
					loadParentOptions(<?php echo "'".$storage->getTypeID()."','".$storage->getParentID()."'";?>);
				</script>
				<?php
				//display grid if type is shelf and size is defined
				if($storage->getTypeID() == 2 AND $storage->getX() > 0 AND $storage->getY() > 0){
					?>
					
					<div class="shelfAlignmentContainer">
						<hr>
						<div class="openBoxes" id="openBoxes">
							<?php
							$openChilds = $storage->getChildsWithoutPosition();
							foreach($openChilds as $tmpChild) {
								$openChild = new Storage;
								if($openChild->loadData($tmpChild)) {
									?>
									<div class="option" data-storage-id="<?php echo $openChild->getID();?>"> <?php echo $openChild->getName();?> </div>
									<?php
								}
							}
							?>
						</div>
						<div class="shelfGrid">
						<?php
						/*for($x = 1;$x <= $storage->getX();$x++) {
							?>
							<div class="gridRow">
								<?php
								for($y = 1;$y <= $storage->getY();$y++) {
									?>
									<div class="gridBox" data-grid-x="<?php echo $x;?>" data-grid-y="<?php echo $y;?>">
									</div>
									<?php
								}
								?>
							</div>
							<?php
						}*/
						echo $storage->displayGrid();
						?>
						</div>
						<div class="generalButton" onclick="saveGrid()"> <?php echo WORD_SAVE;?> </div>
					</div>
					
					<script>
						setUpGrid();
					</script>
					<?php
				}
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

