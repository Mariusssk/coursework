<?php

include_once("../resources/config.php");

//check if user is logged-in

if($session->loggedIn() === True) {
	include(TEMPLATES."/header/header_small.php");
	
	//Check if specific reuqest is send
	
	$requestAllowed = array("listUser","editUser","listUserRoles","editUserRole","viewUserRole");
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
	} else if($request == "editUserRole" OR $request == "viewUserRole") {
		if($session->checkRights("edit_user_role") == True) {
			?>
			<div class="page role editRole">
			<?php
			$role = new UserRole;
			if(isset($_GET['ID']) AND !empty($_GET['ID']) AND $role->loadData($_GET['ID'])) {
				if($role->getPreDefined() == 1 AND $request == "editUserRole") {
					$request = "viewUserRole";
				}
				
				$disabled = "";
				if($request == "viewUserRole") {
					$disabled = "disabled";
				}
				?>
				<div class="row editRoleContainer">
					<div class="col-12">
						<input type="text" class="generalInput" value="<?php echo $role->getName();?>" <?php echo $disabled;?> data-input-name="name" placeholder="<?php echo ROLE_EDIT_FORM_PLACEHOLDER_NAME;?>">
					</div>
					<?php
					$options = $role->getRightOptions();
				
					foreach($options as $tmpOption) {
						?>
						<div class="col-8 col-md-6 rightName">
							<?php echo $tmpOption['name'];?>
						</div>
						<div class="col-4 col-md-6 rightInput">
							<?php
							$checked = "";
							if($tmpOption['value'] == 1) {
								$checked = "checked";
							}
							?>
							<input type="checkbox" data-right-name="<?php echo $tmpOption['name'];?>" <?php echo $disabled;?> class="generalCheckbox rightInput" <?php echo $checked;?>>
						</div>
						<?php
					}
					if($request == "editUserRole") {
					?>
					<div class="col-12 col-md-6">
						<div class="generalButton" onclick="saveRoleData(<?php echo $role->getID();?>)"><?php echo WORD_SAVE;?></div>
					</div>
					<div class="col-12 col-md-6 questionDeleteButton">
						<div class="generalButton" onclick="deleteRole()"><?php echo WORD_DELETE;?></div>
					</div>
					<div class="col-12 deleteButtons none">
						<div class="generalButton" onclick="deleteRole('delete','<?php echo $role->getID();?>')"><?php echo WORD_DELETE;?></div>
						<div class="generalButton" onclick="deleteRole('abort')"><?php echo WORD_ABORT;?></div>
					</div>
					<?php
					} else {
						?>
					<div class="col-12">
						<a href="<?php echo URL;?>/settings/user/roles"><div class="generalButton"><?php echo WORD_BACK;?></div></a>
					</div>
					<?php
					}
					?>
					
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

