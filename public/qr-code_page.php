<?php

include_once("../resources/config.php");

//check if user is logged-in

if($session->loggedIn() === True) {
	
	
	//Check if specific reuqest is send
	
	$requestAllowed = array("displayQrCode","selectData");
	if(isset($_GET['request']) AND !empty($_GET['request']) AND in_array($_GET['request'],$requestAllowed)) {
		$request = $_GET['request'];
	} else {
		$request = "displayQrCode";
	}
	//Page
	
	
		//Load page data based on request
		if($request == "displayQrCode") {
			include(TEMPLATES."/header/header_basic.php");
			?>
			<script> var protectedPage = true; </script>
			<div class="page qrCode <?php echo $request;?>">
			<?php 
				$itemIDs = array();
				$storageIDs = array();
				
				//check if ids have been transmitted
				//if yes put in array depend on if storage or item
				if(isset($_GET['type']) AND isset($_GET['IDs'])) {
					$IDs = explode(";",$_GET['IDs']);
					$type = $_GET['type'];
					if($type == "item") {
						$itemIDs = $IDs;
					} else if($type == "storage") {
						$storageIDs = $IDs;
					}
				}
				?>
				<style>
				
				</style>
				<page size="A4">
				<div class="qrCodeContainer center print">
					<?php
					foreach($itemIDs as $tmpItem) {
						$item = new Item;
						if($item->loadData($tmpItem)) {
							?>
							<div class="codeBox">
								<img class="qrCode" src="https://chart.googleapis.com/chart?cht=qr&chs=500x500&chl=<?php echo $item->generateQrCodeURL();?>">
								<span class="qrCodeText"><b><h3> <?php echo $item->generateQrCodeKey();?> </h3></b></span>
							</div>
							<?php
						}
					}
					?>
					<?php
					foreach($storageIDs as $tmpStorage) {
						$storage = new Storage;
						if($storage->loadData($tmpStorage)) {
							?>
							<div class="codeBox">
								<img class="qrCode" src="https://chart.googleapis.com/chart?cht=qr&chs=500x500&chl=<?php echo $storage->generateQrCodeURL();?>">
								<span class="qrCodeText"><b><h3> <?php echo $storage->generateQrCodeKey();?> </h3></b></span>
							</div>
							<?php
						}
					}
					?>
				</div>
				</page>
			</div>
			<?php
		} else if($request == "selectData") {
			include(TEMPLATES."/header/header_small.php");
			?>
			<script> var protectedPage = true; </script>
			<div class="page qrCode <?php echo $request;?>">
				<div class="row">
					<div class="col-12">
						<select class="generalSelect selectType">
							<option value=""><?php echo QR_GENERATOR_DATA_SELECT_TYPE_PLACEHOLDER;?></option>
							<option value="item"><?php echo WORD_ITEM;?></option>
							<option value="storage"><?php echo WORD_STORAGE;?></option>
						</select>
					</div>
					<div class="col-12">
						<hr>
					</div>
					<div class="col-12 storageContainer  center">
						<div>
						<input type="checkbox" class="generalCheckbox">
						ddd
						</div>
					</div>
					<div class="col-12 itemContainer none center">
						
					</div>
				</div>
			</div>
			<?php 
			include(TEMPLATES."/footer/footer.php");
		}
	
	
} else {
	include(TEMPLATES."/page/login_needed.php");
}

