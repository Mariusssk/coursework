<?php

//-----------------New PHP Functions File---------------------
//File for all functions for the to-do lists
//-----------------New PHP Functions File---------------------

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

ob_start();

include "../../resources/config.php";

if(isset($_POST['requestType']) AND !empty($_POST['requestType'])) {
	$request = $_POST['requestType'];
	
	
	if($session->loggedIn() == True) {
		//load todo categories
		//Objectives 7.3
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
		//Objectives 7.3.1
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
		//Objectives 7.3.1
		else if($request == "saveCategoryData") {

			$category = new ToDoCategory;
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
		
		//get data for todo list
		//Objectives 7.4
		else if($request == "getListData") {
			$list = new ToDoList;
			if(isset($_POST['listID']) AND $list->loadData($_POST['listID'])) {
				if($list->checkRights("view",$session) == True) {
					$post = array();
					
					//Basic data
					$post['listID'] = $list->getID();
					$post['name'] = $list->getName();
					
					if($list->checkRights("edit",$session) == True) {
						$post['rights'] = "edit";
					} else {
						$post['rights'] = "view";
					}
					
					if(empty($list->getUserID())) {
						$post['listType'] = "global";
					} else {
						$post['listType'] = "personal";
					}
					
					if(NotificationRequest::checkIfRequestActivated($session->getSessionUserID(), 3, $list->getID())) {
						$post['notifications'] = True;
					} else {
						$post['notifications'] = False;
					}
					
					//Entries
					
					$entriesArray = ToDoListEntry::loadEntriesArray($list->getID(),"external");
					
					$post['entries'] = $entriesArray;
					
					
					
					echo json_encode($post);
				} else {
					echo "missingRights";
				}
			} else {
				echo "error";
			}
		}
		
		
		//Load all tags for specific list
		//Objectives 8.2
		else if($request == "loadToDoListTags") {
			$list = new ToDoList;
			if(isset($_POST['listID']) AND $list->loadData($_POST['listID'])) {
				if($list->checkRights("view",$session) == True) {
					
					//Tags
					
					$post = array();
					
					$tags = TagAssignment::loadTagsByAttribute(3,$list->getID());
					
					$post['tags'] = array();
					
					foreach($tags as $tmpTag) {
						$tag = new Tag;
						if($tag->loadData($tmpTag)) {
							array_push($post['tags'], array("ID" => $tag->getID(), "name" => $tag->getName(), "colour" => $tag->getColour()));
						}
					}
					
					//Rights
					
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
		
		//Load all entries for specific list
		//Objectives 7.2
		else if($request == "loadToDoListEntries") {
			$list = new ToDoList;
			if(isset($_POST['listID']) AND $list->loadData($_POST['listID'])) {
				if($list->checkRights("view",$session) == True) {
					
					//Entries
					
					$entriesArray = ToDoListEntry::loadEntriesArray($list->getID(),"external");
					
					$post['entries'] = $entriesArray;
					
					//Rights
					
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
		
		
		//Remove tag from list
		//Objectives 8.2
		else if($request == "removeToDoListTag") {
			$assigment = new TagAssignment;
			$list = new ToDoList;
			if(isset($_POST['listID']) AND isset($_POST['tagID']) AND 
				$assigment->loadDataOnTagAndAttributeID($_POST['tagID'],$_POST['listID'],3) AND
				$list->loadData($_POST['listID'])
			) {
				if($list->checkRights("edit",$session) == True) {
					if($assigment->deleteData()) {
						echo "success";
					} else {
						echo "error";
					}
				} else {
					echo "missingRights";
				}
			} else {
				echo "error";
			}
		}
		
		//add tag to list
		//Objectives 8.2
		else if($request == "addToDoListTag") {
			$assigment = new TagAssignment;
			$list = new ToDoList;
			if(isset($_POST['listID']) AND isset($_POST['tagID']) AND 
				$list->loadData($_POST['listID'])
			) {
				if($list->checkRights("edit",$session) == True) {
					if($list->checkIfListHasTag($_POST['tagID']) == False) {
						if($assigment->createTagForList($_POST['listID'],$_POST['tagID'])) {
							echo "success";
						} else {
							echo "error";
						}
					} else {
						echo "alreadyAdded";
					}	
				} else {
					echo "missingRights";
				}
			} else {
				echo "error";
			}
		}
		
		//change checked status of todo list entry
		//Objectives 7.2
		else if($request == "changeEntryStatus") {
			$entry = new ToDoListEntry;
			if(isset($_POST['entryID']) AND $entry->loadData($_POST['entryID'])) {
				$list = new ToDoList;
				if($list->loadData($entry->getListID())) {
					if($list->checkRights("edit",$session) == True) {
						if(
							isset($_POST['checked']) AND
							$entry->setChecked($_POST['checked']) AND
							$entry->saveData()
						) {
							echo "success";
						} else {
							echo "error";
						}
					} else {
						echo "missingRights";
					}
				} else {
					echo "error";
				}
			} else {
				echo "error";
			}
		}
		
		
		//Add new entry to todo list
		//Objectives 7.2
		else if($request == "saveNewListEntry") {
			$list = new ToDoList;
			if(
				isset($_POST['listID']) AND
				$list->loadData($_POST['listID'])
			) {
				if($list->checkRights("edit",$session) == True) {
					$entry = new ToDoListEntry;
					
					if(
						isset($_POST['name']) AND $entry->setName($_POST['name']) AND
						isset($_POST['parentID']) AND $entry->setParentID($_POST['parentID']) AND
						isset($_POST['listID']) AND $entry->setListID($_POST['listID']) AND
						$entry->createNewData()
					) {
						echo "success";
					} else {
						echo "error";
					}
				} else {
					echo "missingRights";
				}
			} else {
				echo "error";
			}
		}
		
		//remove entry from todo list
		//Objectives 7.2
		else if($request == "removeListEntry") {
			$list = new ToDoList;
			$entry = new ToDoListEntry;
			if(
				isset($_POST['listID']) AND $list->loadData($_POST['listID']) AND
				isset($_POST['entryID']) AND $entry->loadData($_POST['entryID'])
			) {
				if($list->checkRights("edit",$session) == True) {
					if($list->getID() == $entry->getListID()) {
						if(count($entry->getChilds()) > 0) {
							echo "hasChildren";
						} else if($entry->deleteData()){
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
			} else {
				echo "error";
			}
		}
		
		
		//create new todo list
		//Objectives 7.1
		else if($request == "createNewToDoList") {
			if(
				isset($_POST['type']) AND
				($_POST['type'] == "personal" AND $session->checkRights("edit_personal_todo_list") == True) OR 
				($_POST['type'] == "global" AND $session->checkRights("create_global_todo_list") == True)
			) {
				$list = new ToDoList;
				if(
					(($_POST['type'] == "personal" AND $list->setUserID($session->getSessionUserID())) OR
					$_POST['type'] == "global") AND
					$list->createNewList()
				) {
					echo "success";
				} else {
					echo "error";
				}
				
			} else {
				echo "missingRights";
			}
		}
		
		//save name of todo list
		//Objectives 7
		else if($request == "editToDoListName") {
			$list = new ToDoList;
			if(
				isset($_POST['listID']) AND $list->loadData($_POST['listID'])
			) {
				if($list->checkRights("edit",$session) == True) {
					if(
						isset($_POST['name']) AND
						$list->setName($_POST['name']) AND
						isset($_POST['category']) AND
						$list->setCategoryID($_POST['category']) AND
						$list->saveData()
					) {
						echo "success";
					} else {
						echo "error";
					}
				} else {
					echo "missingRights";
				}
			} else {
				echo "error";
			}
		}
		
		//get all possible categories for todo list
		//Objectives 7.3
		else if($request == "getToDoListCategories") {
			$list = new ToDoList;
			if(
				isset($_POST['listID']) AND $list->loadData($_POST['listID'])
			) {
				if($list->checkRights("edit",$session) == True) {
					if(empty($list->getUserID())) {
						$categories = ToDoCategory::getGlobalCategories();
					} else {
						$categories = ToDoCategory::getPersonalCategories($list->getUserID());
					}
					
					$post = array();
					
					foreach($categories as $tmpCategory) {
						$category = new ToDoCategory;
						if($category->loadData($tmpCategory)) {
							$selected = "";
							if($category->getID() == $list->getCategoryID()) {
								$selected = "selected";
							}
							array_push($post, array("categoryID" => $category->getID(), "name" => $category->getName(), "selected" => $selected));
						}
					}
					
					echo json_encode($post);
					
				} else {
					echo "missingRights";
				}
			} else {
				echo "error";
			}
		}
		
		//delete todo list
		//Objectives 7
		else if($request == "deleteToDoList") {
			$list = new ToDoList;
			if(
				isset($_POST['listID']) AND $list->loadData($_POST['listID'])
			) {
				if($list->checkRights("edit",$session) == True) {
					if($list->deleteList()) {
						echo "success";
					}
					
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
?>