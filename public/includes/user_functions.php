<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

ob_start();

include "../../resources/config.php";

if(isset($_POST['requestType']) AND !empty($_POST['requestType'])) {
	$request = $_POST['requestType'];
	
	
	if($session->loggedIn() == True) {
		
		//load list of all user roles
		
		if($request == "loadRoleList") {
			//check user rights
			if($session->checkRights("edit_user_role") == True) {
				
				//set serach parameters
				if(isset($_POST['search'])) {
					$search = $_POST['search'];
				} else {
					$search = array();
				}
				
				
				$roles = UserRole::getAll();
				
				$post = array('roles' => array());

				//create list of all items
				
				foreach($roles as $tmpRole) {
					$role = new UserRole;
					$tmpRoleArray = array();
					if($role->loadData($tmpRole)) {
						$searchFail = 0;
						$tmpRoleArray = array();
						$tmpRoleArray['ID'] = $role->getID();
						$tmpRoleArray['name'] = $role->getName();
						
						if($role->getPreDefined() == "1") {
							$tmpRoleArray['preDefined'] = True;
						} else {
							$tmpRoleArray['preDefined'] = False;
						}
				
						
						if($searchFail == 0) {
							array_push($post['roles'], $tmpRoleArray);
						}
						
					}
				}
				
				if(count($post) > 0) {
					echo json_encode($post);
				} else {
					echo json_encode(array());
				}
			} else {
				echo "missingRights";
			}
			
		} 
		
		//Create a new role template
		
		else if($request == "createNewRole") {
			//check user rights
			if($session->checkRights("edit_user_role") == True) {
				
				//Create new role
				
				$role = new UserRole;
				
				if($role->createNewRole()) {
					echo "success";
				} else {
					echo "error";
				}
				
			} else {
				echo "missingRights";
			}
		}
		
		//delete role if unused
		
		
		else if($request == "deleteRole") {
			//check user rights
			if($session->checkRights("edit_user_role") == True) {
				//load role data
				$role = new UserRole;
				if(isset($_POST['roleID']) AND $role->loadData($_POST['roleID'])) {
					if($role->checkIfUsed()) {
						echo "inUse";
					} else if($role->deleteData()) {
						echo "success";
					} else {
						echo "error";
					}
				} else {
					echo "error";
				}
				
			} else {
				echo "missingRights";
			}
		}
		
	} 
	
	

	
}

ob_flush();
 