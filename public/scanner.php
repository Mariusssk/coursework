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
		}
		?>
	</div>
	<?php
	include(TEMPLATES."/footer/footer.php");
} else {
	include(TEMPLATES."/page/login_needed.php");
}

