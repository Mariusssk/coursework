<?php

include_once("../resources/config.php");

//check if user is logged-in

if($session->loggedIn() === True) {
	include(TEMPLATES."/header/header_small.php");
	
	//Check if specific reuqest is send
	
	$requestAllowed = array("listUser","editUser","listUserRoles","editUserRole");
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
	if($request == "listUserRoles") {
		if($session->checkRights("edit_user_role") == True) {
			?>
			<div class="page role roleList">
				<div class="generalTable">
					<div class="row generalTableSearch">
						<div class="td col-md-6 col-sm-12">
							<input type="text" class="generalInput searchInput" data-search-name="roleName" placeholder="<?php echo ROLE_LIST_HEADLINE_NAME;?>">
						</div>
						<div class="td col-md-3 col-12">
							<div class="generalSearchBarButton" onclick="loadRoles()"> <?php echo WORD_SEARCH;?> </div>
						</div>
						<div class="td col-md-3 col-12">
							<div class="generalSearchBarButton newRoleButton" onclick="createNewRole()"> <?php echo WORD_ADD;?> </div>
						</div>
					</div>
					<div class="row generalTableHeader">
						<div class="td col-sm-6 col-7">
							<?php echo ROLE_LIST_HEADLINE_NAME;?>
						</div>
						<div class="td col-sm-4 col-5">
							<?php echo ROLE_LIST_HEADLINE_PRE_DEFINED;?>
						</div>
						<div class="td col-sm-2 col-12">
							<?php echo WORD_ACTION;?>
						</div>
					</div>
					<div class="tableContent roleListContainer">
						<div class="row generalTableContentRow">
							<div class="td col-12">
								<?php echo WORD_LOADING;?>
							</div>
						</div>
					</div>
				</div>
				<script>
					loadRoles();
				</script>
			</div>
			<?php
		} else {
			include(TEMPLATES."/user/missing_rights.php");
		}
	} else if($request == "editUserRole") {
		if($session->checkRights("edit_user_role") == True) {
			?>
			<div class="page role editRole">
			<?php
			$role = new UserRole;
			if(isset($_GET['ID']) AND !empty($_GET['ID']) AND $role->loadData($_GET['ID'])) {
				?>
				<div class="row editRoleContainer">
					<div class="col-12">
						<input type="text" class="generalInput" data-input-name="name" placeholder="<?php echo ROLE_EDIT_FORM_PLACEHOLDER_NAME;?>">
					</div>
				</div>
				<?php
			} else {
				?>
				<br>
				<div class="center">
					<h3> <?php echo ROLE_EDIT_NOT_FOUND;?> </h3>
				</div>
				<?php
			}
			?>
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
