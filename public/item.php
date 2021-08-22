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
			if($session->checkRights("view_all_items") == True) {
				?>
				<div class="generalTable">
					<div class="row generalTableSearch">
						<div class="td col-md-4 col-sm-12">
							<input type="text" class="generalInput searchInput" data-search-name="name" placeholder="<?php echo "test";?>">
						</div>
						<div class="td col-md-4 col-sm-12">
							<?php echo ItemType::getSelect(array("class"=>"generalSelect searchInput","data"=>array("search-name","type")),0,"Test");?>
						</div>
						<div class="td col-md-4 col-sm-12">
							<?php echo Storage::getSelect(array("class"=>"generalSelect searchInput","data"=>array("search-name","storage")),0,"Test");?>
						</div>
						<div class="td d-block d-md-none col-12">
							<div class="generalButton" onclick="loadItems()"> <?php echo WORD_SEARCH;?> </div>
						</div>
					</div>
					<div class="row generalTableHeader">
						<div class="td col-sm-4 col-6">
							<?php echo "test";?>
						</div>
						<div class="td col-sm-4 d-none d-sm-block">
							<?php echo "test";?>
						</div>
						<div class="td col-sm-4 col-6">
							<?php echo "test";?>
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
					loadItems();
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

