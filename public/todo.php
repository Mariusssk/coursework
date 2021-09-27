<?php

include_once("../resources/config.php");

//check if user is logged-in

if($session->loggedIn() === True) {
	include(TEMPLATES."/header/header_small.php");
	
	//Check if specific reuqest is send
	
	$requestAllowed = array("overview","new","categoryOverview","categoryNew","categoryEdit");
	if(isset($_GET['request']) AND !empty($_GET['request']) AND in_array($_GET['request'],$requestAllowed)) {
		$request = $_GET['request'];
	} else {
		$request = "overview";
	}
	//Page
	
	?>
	<script> var protectedPage = true; </script>
	<div class="page todo <?php echo $request;?>">
		<?php 
		//Load page data based on request
		if($request == "categoryOverview") {
			if($session->checkRights("edit_all_todo_lists") == True) {
				?>
				<div class="generalTable">
					<div class="row generalTableSearch">
						<div class="td col-md-9 col-sm-12">
							<input type="text" class="generalInput searchInput" data-search-name="name" placeholder="<?php echo TODO_CATEGORY_OVERVIEW_HEADER_NAME;?>">
						</div>
						<div class="td d-none d-md-block col-md-3">
							<div class="generalSearchBarButton" onclick="loadTodoCategories()"> <?php echo WORD_SEARCH;?> </div>
						</div>
						<div class="td d-block d-md-none col-12">
							<div class="generalButton" onclick="loadTodoCategories()"> <?php echo WORD_SEARCH;?> </div>
						</div>
					</div>
					<div class="row generalTableHeader">
						<div class="td col-12">
							<?php echo TODO_CATEGORY_OVERVIEW_HEADER_NAME;?>
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
					//load all categories
					loadTodoCategories();
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

