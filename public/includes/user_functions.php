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
		//Objectives 9.2
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
						
						if(isset($search['name']) AND !empty($search['name']) AND strpos(strtoupper($tmpRoleArray['name']),strtoupper($search['name'])) === False) {
							$searchFail = 1;
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
		//Objectives 9.2
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
		//Objectives 9.2
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
		
		//save role data after editing
		//Objectives 9.2
		else if($request == "saveRoleData") {
			//check user rights
			if($session->checkRights("edit_user_role") == True) {
				//load role data
				$role = new UserRole;
				if(isset($_POST['roleID']) AND $role->loadData($_POST['roleID'])) {
					
					if(isset($_POST['rights']) AND isset($_POST['roleName']) AND !empty($_POST['roleName'])) {
						
						//Save role name
						$rights = $_POST['rights'];
						$role->setName($_POST['roleName']);
						if($role->saveData()) {
							//Save role rights
							$rightSaveSuccess = True;
							foreach($rights as $rightKey => $tmpRight) {
								if(!$role->updateData($rightKey,$tmpRight)) {
									$rightSaveSuccess = False;
								}
							}
							
							if($rightSaveSuccess == True) {
								echo "success";
							} else {
								echo "errorSavingRights";
							}
							
						} else {
							echo "error";
						}
					}
					
				} else {
					echo "error";
				}
				
			} else {
				echo "missingRights";
			}
		}
		
		//get list of all users
		//Objectives 9.3
		else if($request == "loadUserList") {
			//check user rights
			if($session->checkRights("edit_user") == True) {
				
				//set serach parameters
				if(isset($_POST['search'])) {
					$search = $_POST['search'];
				} else {
					$search = array();
				}
				
				
				$users = User::getAll();
				
				$post = array('user' => array());

				//create list of all items
				
				foreach($users as $tmpUser) {
					$user = new User;

					if($user->loadData($tmpUser)) {
						$searchFail = 0;
						$tmpUserArray = array();
						$tmpUserArray['ID'] = $user->getID();
						$tmpUserArray['username'] = $user->getUsername();
						$tmpUserArray['firstname'] = $user->getName(1,0);
						$tmpUserArray['lastname'] = $user->getName(0,1);
						$tmpUserArray['roleName'] = $user->getRoleName();
						
						
						if(isset($search['name']) AND !empty($search['name']) AND strpos(strtoupper($user->getName(1,1)),strtoupper($search['name'])) === False) {
							$searchFail = 1;
						}
						
						if(isset($search['username']) AND !empty($search['username']) AND strpos(strtoupper($tmpUserArray['username']),strtoupper($search['username'])) === False) {
							$searchFail = 1;
						}
						
						if(isset($search['roleName']) AND !empty($search['roleName']) AND strpos(strtoupper($tmpUserArray['roleName']),strtoupper($search['roleName'])) === False) {
							$searchFail = 1;
						}
					
						if($searchFail == 0) {
							array_push($post['user'], $tmpUserArray);
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
		
		//delete user
		//Objectives 9.3
		else if($request == "deleteUser") {
			//check user rights
			if($session->checkRights("delete_user") == True) {
				
				$user = new User;
				
				if(isset($_POST['userID']) AND $user->loadData($_POST['userID'])) {
					if($user->getID() != $session->getSessionUserID()) {
						if($user->checkAdmins("delete")) {
							if($user->deleteData()) {
								echo "success";
							} else {
								echo "error";
							}
						} else {
							echo "adminNeeded";
						}
					} else {
						echo "userIsActive";
					}
				} else {
					echo "error";
				}
				
			} else {
				echo "missingRights";
			}
			
		}
		
		//save data for user after it was editited
		//Objectives 9.3
		else if($request == "saveUserData") {
			//check user rights
			if($session->checkRights("edit_user") == True) {
				
				$user = new User;

				if(
					isset($_POST['userData']) AND(
					(isset($_POST['userData']['userID']) AND $user->loadData($_POST['userData']['userID'])) OR 
					!isset($_POST['userData']['userID']))
				) {
					$error = False;
					
					$userData = $_POST['userData'];
					
					if(isset($userData['firstname']) AND !$user->setFirstname($userData['firstname'])) {
						$error = True;
					}
					
					if(isset($userData['lastname']) AND !$user->setLastname($userData['lastname'])) {
						$error = True;
					}
					
					if(isset($userData['language']) AND !$user->setPreferredLanguage($userData['language'])) {
						$error = True;
					}
					
					if(isset($userData['active']) AND !$user->setActive($userData['active'])) {
						$error = True;
					}
					

					if($user->getRoleID() != 1 OR $user->checkAdmins() == True OR (isset($userData['roleID']) AND $userData['roleID'] == 1)) {
						if(isset($userData['roleID']) AND $user->setRoleID($userData['roleID'])) {
							
							$username = "";
							if(isset($userData['username'])) {
								$username = $user->setUsername($userData['username']);
							}
							
							if($username === True OR empty($username)) {
								$email = "";
								if(isset($userData['email'])) {
									$email = $user->setEmail($userData['email']);
								}
								if($email === True OR empty($email)) {
									$schoolEmail = "";
									if(isset($userData['school_email'])) {
										$schoolEmail = $user->setSchoolEmail($userData['school_email']);
									}
									
									if($schoolEmail === True OR empty($schoolEmail)) {
										
										if($error == False) {
											if(empty($user->getID())) {
												if($user->createNewData()) {
													echo "created";
												}
											} else {
												if($user->saveData()) {
													echo "success";
												}
											}
										} else {
											echo "error";
										}
										
									} else if($schoolEmail == "alreadyInUse") {
										echo "schoolEmailAlreadyUsed";
									}
								} else if($email == "alreadyInUse") {
									echo "emailAlreadyUsed";
								}
							} else if($username == "alreadyInUse") {
								echo "usernameAlreadyUsed";
							}
						} else {
							echo "error";
						}
					} else {
						echo "adminNeeded";
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
 