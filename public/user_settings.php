<?php

//-----------------New Page---------------------
//Settings Page (edit user, edit roles, view role rights, etc)
//-----------------New Page---------------------

include_once("../resources/config.php");

//check if user is logged-in

if($session->loggedIn() === True) {
	include(TEMPLATES."/header/header_small.php");
	
	//Check if specific reuqest is send
	
	$requestAllowed = array("listUser","editUser","createNewUser","listUserRoles","editUserRole","viewUserRole");
	if(isset($_GET['request']) AND !empty($_GET['request']) AND in_array($_GET['request'],$requestAllowed)) {
		$request = $_GET['request'];
	} else {
		$request = "listUser";
	}
	
	//Page
	
	?>
	<script> var protectedPage = true; </script>
	<?php
	
	//load page based on request
	
	//Page listing all user roles with the ability to create a new one
	//Objective 9.2
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
	} 
	//Page to display pre-defined role or data for created role
	//Objective 9.1 / 9.2 / 9.2.1
	else if($request == "editUserRole" OR $request == "viewUserRole") {
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
	//Page listing all users of the system
	//Objective 9.3
	else if($request == "listUser") {
		if($session->checkRights("edit_user") == True) {
			?>
			<div class="page user list">
				<div class="generalTable">
					<div class="row generalTableSearch">
						<div class="td col-md-3 col-sm-12">
							<input type="text" class="generalInput searchInput" data-search-name="username" placeholder="<?php echo WORD_USERNAME;?>">
						</div>
						<div class="td col-md-3 col-sm-12">
							<input type="text" class="generalInput searchInput" data-search-name="name" placeholder="<?php echo SETTINGS_USER_LIST_PLACEHOLDER_NAME;?>">
						</div>
						<div class="td col-md-3 col-sm-12">
							<input type="text" class="generalInput searchInput" data-search-name="roleName" placeholder="<?php echo SETTINGS_USER_LIST_HEADLINE_ROLE;?>">
						</div>
						<div class="td col-md-3 col-12">
							<div class="generalSearchBarButton" onclick="loadUserList()"> <?php echo WORD_SEARCH;?> </div>
						</div>
					</div>
					<div class="row generalTableHeader">
						<div class="td col-6 col-md-3">
							<?php echo WORD_USERNAME;?>
						</div>
						<div class="td  d-none d-md-block col-md-3">
							<?php echo WORD_FIRSTNAME;?>
						</div>
						<div class="td d-none d-md-block col-md-3">
							<?php echo WORD_LASTNAME;?>
						</div>
						<div class="td col-4 col-md-2">
							<?php echo SETTINGS_USER_LIST_HEADLINE_ROLE;?>
						</div>
						<div class="td col-2 col-md-1">
							<?php echo WORD_ACTION;?>
						</div>
					</div>
					<div class="tableContent ">
						<div class="row generalTableContentRow userList">
							<div class="td col-12">
								<?php echo WORD_LOADING;?>
							</div>
						</div>
					</div>
				</div>
				<script>
					loadUserList();
				</script>
			</div>
			<?php
		} else {
			include(TEMPLATES."/user/missing_rights.php");
		}
	} 
	//Page to edit existing or create a new user
	//Objective 9.2
	else if($request == "editUser" OR $request == "createNewUser") {
		if(
			($session->checkRights("create_user") == True AND $request == "createNewUser") OR 
			($session->checkRights("edit_user") == True AND $request == "editUser")
		) {
			?>
			<div class="page user edit">
			<?php
			$user = new User;
			if(
				$request == "createNewUser" OR 
				($request == "editUser" AND isset($_GET['ID']) AND $user->loadData($_GET['ID']))
			) {
				//select selected preffered language
				$deChecked = "";
				$enChecked = "";
				if($user->getPreferredLanguage() == "de") {
					$deChecked = "selected";
				} else if($user->getPreferredLanguage() == "en") {
					$enChecked = "selected";
				}
				

				if($request == "editUser") {
					?>
					<input type="hidden" value="<?php echo $user->getID();?>" class="dataInput" data-input-name="userID">
					<?php
				}
				?>
				<div class="row">
					<div class="col-12 col-sm-12 col-md-4 inputBlock">
						<input type="text" class="dataInput generalInput" value="<?php echo $user->getUsername();?>" data-input-name="username" placeholder="<?php echo WORD_USERNAME;?>">
					</div>
					<div class="col-12 col-sm-6 col-md-4 inputBlock">
						<input type="text" class="dataInput generalInput" value="<?php echo $user->getName(1,0);?>" data-input-name="firstname" placeholder="<?php echo WORD_FIRSTNAME;?>">
					</div>
					<div class="col-12 col-sm-6 col-md-4 inputBlock">
						<input type="text" class="dataInput generalInput" value="<?php echo $user->getName(0,1);?>" data-input-name="lastname" placeholder="<?php echo WORD_LASTNAME;?>">
					</div>
					<div class="col-12 col-sm-6 col-md-4 inputBlock">
						<input type="text" class="dataInput generalInput" value="<?php echo $user->getEmail();?>" data-input-name="email" placeholder="<?php echo SETTINGS_USER_EDIT_PLACEHOLDER_EMAIL;?>">
					</div>
					<div class="col-12 col-sm-6 col-md-4 inputBlock">
						<input type="text" class="dataInput generalInput" value="<?php echo $user->getSchoolEmail();?>" data-input-name="school_email" placeholder="<?php echo SETTINGS_USER_EDIT_PLACEHOLDER_SCHOOL_EMAIL;?>">
					</div>
					<div class="col-12 col-sm-6 col-md-4 inputBlock">
						<?php
						echo UserRole::getSelect(array("class"=>"generalSelect dataInput","data"=>array("input-name","roleID")),$user->getRoleID(),SETTINGS_USER_EDIT_PLACEHOLDER_USER_ROLE);
						?>
					</div>
					<div class="col-12 col-sm-6 inputBlock">
						<?php echo SETTINGS_USER_EDIT_PLACEHOLDER_ACTIVE;?>: <input type="checkbox" class="dataInput generalCheckbox" <?php echo $user->getActiveChecked();?> data-input-name="active">
					</div>
					<div class="col-12 col-sm-6 inputBlock">
						<select class="generalSelect dataInput" data-input-name="language">
							<option value=""><?php echo SETTINGS_USER_EDIT_PLACEHOLDER_PREFERRED_LANGUAGE;?></option>
							<option value="de" <?php echo $deChecked;?>><?php echo LANGUAGE_NAME_GERMAN;?></option>
							<option value="en" <?php echo $enChecked;?>><?php echo LANGUAGE_NAME_ENGLISH;?></option>
						</select>
					</div>
					<div class="col-12 saveUserForm">
						<div class="row">
							<div class="col-12 col-sm-6 inputBlock">
								<div class="generalButton" onclick="saveUserData('<?php echo $user->getID();?>')"><?php echo WORD_SAVE?></div>
							</div>
							<?php
							if($request == "editUser" AND $session->checkRights("delete_user")) {
							?>
							<div class="col-12 col-sm-6 inputBlock">
								<div class="generalButton" onclick="deleteUserDataForm()"><?php echo WORD_DELETE?></div>
							</div>
							<?php } ?>
						</div>
					</div>
					<?php
					if($request == "editUser" AND $session->checkRights("delete_user")) {
					?>
					<div class="col-12 deleteUserForm none">
						<div class="row">
							<div class="col-12 col-sm-6 inputBlock">
								<div class="generalButton" onclick="deleteUser('<?php echo $user->getID();?>')"><?php echo WORD_DELETE?></div>
							</div>
							
							<div class="col-12 col-sm-6 inputBlock">
								<div class="generalButton" onclick="deleteUserDataForm()"><?php echo WORD_ABORT?></div>
							</div>
						</div>
					</div>
					<?php } ?>
				</div>
				<?php
			} else {
				?>
				<div class="center">
					<b><h3><?php echo SETTINGS_USER_EDIT_LOAD_FAILED;?></h3></b>
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

?>