<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

ob_start();

include "../../resources/config.php";

if(isset($_POST['requestType']) AND !empty($_POST['requestType'])) {
	$request = $_POST['requestType'];
	
	
	if($session->loggedIn() == True) {
		//load todo categories
		if($request == "loadTodoCategories") {
			//check user rights
			if($session->checkRights("edit_todo_list_categories") == True OR $session->checkRights("edit_personal_todo_list") == True) {
				
				//set serach parameters
				if(isset($_POST['search'])) {
					$search = $_POST['search'];
				} else {
					$search = array();
				}
				
				
				$categories = ToDoCategory::getUserCategories($session->getSessionUserID());
				
				$post = array();
				//load information for each item
				foreach($categories as $tmpCategory) {
					$category = new ToDoCategory;
					if($category->loadData($tmpCategory)) {
						$searchFail = 0;
						$tmpItemArray = array();
						$tmpItemArray['ID'] = $category->getID();
						$tmpItemArray['name'] = $category->getName();
						
						if(isset($search['name']) AND !empty($search['name']) AND strpos(strToUpper($tmpItemArray['name']),strToUpper($search['name'])) === False) {
							$searchFail = 1;
						}
						
						if(empty($category->getUserID())) {
							if($session->checkRights("edit_todo_list_categories") == False) {
								$searchFail = 1;
							} else {
								$tmpItemArray['type'] = TODO_CATEGORY_OVERVIEW_TYPE_GLOBAL;
							}
						} else {
							if($session->checkRights("edit_personal_todo_list") == False) {
								$searchFail = 1;
							} else {
								$tmpItemArray['type'] = TODO_CATEGORY_OVERVIEW_TYPE_PERSONAL;
							}
						}
						
						if($searchFail == 0) {
							array_push($post, $tmpItemArray);
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
		
		//delete category
		
		else if($request == "deleteCategory") {
			//check user rights
			if($session->checkRights("edit_todo_list_categories") == True OR $session->checkRights("edit_personal_todo_list") == True) {
				$category = new ToDoCategory;
				//check if storage ID is send and valid
				if(isset($_POST['categoryID']) AND $category->loadData($_POST['categoryID'])) {
					//Check if global or personal
					if($category->getGlobal() == True AND $session->checkRights("edit_todo_list_categories") == False) {
						echo "missingRights";
					} else if($category->getGlobal() == False AND $session->checkRights("edit_personal_todo_list") == False) {
						echo "missingRights";
					} else {
						if($category->deleteData() == True) {
							echo "success";
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
		
		//save data or create new
		else if($request == "saveCategoryData") {

			$category = new TodoCategory;
			if(isset($_POST['categoryID']) AND $category->loadData($_POST['categoryID'])) {
				$requestType = "edit";
			} else if((isset($_POST['categoryID']) AND empty($_POST['categoryID'])) OR !isset($_POST['categoryID'])) {
				$requestType = "new";
			} else {
				$requestType = "";
				echo "error";
			}
			
			//check user rights
			
			if($session->checkRights("edit_todo_list_categories") == False AND $session->checkRights("edit_personal_todo_list") == False) {
				echo "missingRights";
			} else if($requestType == "edit" AND ($session->checkRights("edit_todo_list_categories") == False AND $category->getGlobal() == True) OR ($session->checkRights("edit_personal_todo_list") == False AND $category->getGlobal() == False)){
				echo "missingRights";
			} else if($requestType != "") {
				$saveFail = 0;
				
				//check if data has been send correctly
				if(isset($_POST['categoryData'])) {
					$categoryData = $_POST['categoryData'];
				} else {
					$categoryData = array();
				}
				
				//set name
				if(isset($categoryData['name'])) {
					$name = $categoryData['name'];
				} else {
					$name = "";
				}
				
				if(!$category->setName($name)) {$saveFail = 1;}
				
				if($session->checkRights("edit_todo_list_categories") == True AND $session->checkRights("edit_personal_todo_list") == True) {
					if(isset($categoryData['global']) AND $categoryData['global'] != 1) {
						$category->setUserID($session->getSessionUserID());
					} else {
						$category->setUserID();
					}
					
				} else if($session->checkRights("edit_personal_todo_list") == True) {
					$category->setUserID($session->getSessionUserID());
				}
				
				if($saveFail == 0) {
					$result = "";
					if($requestType == "edit" AND $category->saveData()) {
						$result = array("result"=>"saved");
					} else if($requestType == "new" AND $category->createNewData()) {
						$result = array("result"=>"created","newID"=>$category->getID());
					}
					echo json_encode($result);
				} else {
					echo "error";
				}
				
			}
		}
		
		
		else if($request == "getListData") {
			$list = new ToDoList;
			if(isset($_POST['listID']) AND $list->loadData($_POST['listID'])) {
				if($list->checkRights("view",$session) == True) {
					$post = array();
					$post['listID'] = $list->getID();
					$post['name'] = $list->getName();
					
					if($list->checkRights("edit",$session) == True) {
						$post['rights'] = "edit";
					} else {
						$post['rights'] = "view";
					}
					
					echo json_encode($post);
				} else {
					echo "missingRights";
				}
			} else {
				echo "error";
			}
		}
		
		
	} 

	
}

ob_flush();
