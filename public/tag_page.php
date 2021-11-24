<?php

include_once("../resources/config.php");

//check if user is logged-in

if($session->loggedIn() === True) {
	include(TEMPLATES."/header/header_small.php");
	
	//Check if specific reuqest is send
	
	$requestAllowed = array("tagList");
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
	if($request == "tagList") {
		if($session->checkRights("edit_tags") == True) {
			?>
			<div class="page tags tagList">
				<div class="generalTable">
					<div class="row generalTableSearch">
						<div class="td col-md-6 col-sm-12">
							<input type="text" class="generalInput searchInput" data-search-name="tagName" placeholder="<?php echo TAG_LIST_HEADLINE_NAME;?>">
						</div>
						<div class="td col-md-3 col-12">
							<div class="generalSearchBarButton" onclick="loadTagList()"> <?php echo WORD_SEARCH;?> </div>
						</div>
						<div class="td col-md-3 col-12">
							<div class="generalSearchBarButton newTagButton" onclick="createNewTag()"> <?php echo WORD_ADD;?> </div>
						</div>
					</div>
					<div class="row generalTableHeader">
						<div class="td col-sm-6 col-7">
							<?php echo TAG_LIST_HEADLINE_NAME;?>
						</div>
						<div class="td col-sm-4 col-5">
							<?php echo TAG_LIST_HEADLINE_COLOR;?>
						</div>
						<div class="td col-sm-2 col-12">
							<?php echo WORD_ACTION;?>
						</div>
					</div>
					<div class="tableContent tagListContainer">
						<div class="row generalTableContentRow">
							<div class="td col-12">
								<?php echo WORD_LOADING;?>
							</div>
						</div>
					</div>
				</div>
				<script>
					loadTagList();
				</script>
			</div>
			<?php
		} else {
			include(TEMPLATES."/user/missing_rights.php");
		}
	}

	include(TEMPLATES."/footer/footer.php");
} else {
	include(TEMPLATES."/page/login_needed.php");
}

