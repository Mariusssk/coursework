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
		

		
		
	} 

	
}

ob_flush();
