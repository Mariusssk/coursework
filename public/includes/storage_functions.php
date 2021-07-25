<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

ob_start();

include "../../resources/config.php";

if(isset($_POST['requestType']) AND !empty($_POST['requestType'])) {
	$request = $_POST['requestType'];
	
	
	if($session->loggedIn() == True) {
		//load storages based on search
		if($request == "loadStorages") {
			//check user rights
			if($session->checkRights("view_storages") == True) {
				//load storages
				$storages = Storage::getAll();
				$post = array();
				
				if(isset($_POST['search'])) {
					$search = $_POST['search'];
				} else {
					$search = array();
				}
				
				//load information for each storage
				foreach($storages as $tmpStorage) {
					$storage = new Storage;
					if($storage->loadData($tmpStorage)) {
						$searchFail = 0;
						$tmpStorageArray = array();
						$tmpStorageArray['ID'] = $storage->getID();
						$tmpStorageArray['name'] = $storage->getName();
						$tmpStorageArray['parentName'] = $storage->getParentName();
						$tmpStorageArray['typeName'] = $storage->getTypeName();
						
						if(isset($search['type']) AND !empty($search['type']) AND $search['type'] != $storage->getTypeID()) {
							$searchFail = 1;
						}
						
						if(isset($search['name']) AND !empty($search['name']) AND strpos($tmpStorageArray['name'],$search['name']) === False) {
							$searchFail = 1;
						}
						
						if(isset($search['parentName']) AND !empty($search['parentName']) AND strpos($tmpStorageArray['parentName'],$search['parentName']) === False) {
							$searchFail = 1;
						}
						
						if($searchFail == 0) {
							array_push($post, $tmpStorageArray);
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
		
		//Load parent options for storage edit/new
		else if($request == "loadParentOptions") {
			//check if user has rights to edit or create new storage
			if($session->checkRights("create_new_storage") == True OR $session->checkRights("edit_storage") == True) {
				if(isset($_POST['typeID']) AND !empty($_POST['typeID'])) {
					//set type of parent storage
					if($_POST['typeID'] == 2) {
						$parentTypeID = 1;
					} else if($_POST['typeID'] == 3) {
						$parentTypeID = 2;
					} else {
						$parentTypeID = 0;
					}
					
					$storages = Storage::getAll();
					$post = array();
					
					foreach($storages as $tmpStorage) {
						$storage = new Storage;
						if($storage->loadData($tmpStorage) AND $storage->getTypeID() == $parentTypeID) {
							$tmpStorageArray = array();
							$tmpStorageArray['ID'] = $storage->getID();
							$tmpStorageArray['name'] = $storage->getName();
							
							array_push($post, $tmpStorageArray);
						}
					}
					
					if(count($post) > 0) {
						echo json_encode($post);
					} else {
						echo json_encode(array());
					}
					
				} else {
					echo "error";
				}
			} else {
				echo "missingRights";
			}
		}
		
		//save data or create new
		else if($request == "saveStorageData") {
			$storage = new Storage;
			if(isset($_POST['storageID']) AND $storage->loadData($_POST['storageID'])) {
				$requestType = "edit";
			} else if((isset($_POST['storageID']) AND empty($_POST['storageID'])) OR !isset($_POST['storageID'])) {
				$requestType = "new";
			} else {
				$requestType = "";
				echo "error";
			}
			
			//check user rights
			
			if(($requestType == "edit" AND $session->checkRights("edit_storage") == False) OR ($requestType == "new" AND $session->checkRights("create_new_storage") == False)) {
				echo "missingRights";
			} else if($requestType != "") {
				$saveFail = 0;
				
				//check if data has been send correctly
				if(isset($_POST['storageData'])) {
					$storageData = $_POST['storageData'];
				} else {
					$storageData = array();
				}
				
				//set storage name
				if(isset($storageData['name'])) {
					$name = $storageData['name'];
				} else {
					$name = "";
				}
				
				if(!$storage->setName($name)) {$saveFail = 1;}
				
				//set x
				if(isset($storageData['x'])) {
					$x = $storageData['x'];
				} else {
					$x = "";
				}
				
				if(!$storage->setX($x)) {$saveFail = 1;}
				
				//set y
				if(isset($storageData['y'])) {
					$y = $storageData['y'];
				} else {
					$y = "";
				}
				
				if(!$storage->setY($y)) {$saveFail = 1;}
				
				//set type ID
				if(isset($storageData['type'])) {
					$type = $storageData['type'];
				} else {
					$type = "";
				}
				
				if(!$storage->setTypeID($type)) {$saveFail = 1;}
				
				//set parent ID
				if(isset($storageData['parentID'])) {
					$parentID = $storageData['parentID'];
				} else {
					$parentID = "";
				}
				
				if(!$storage->setParentID($parentID)) {$saveFail = 1;}
				
				if($saveFail == 0) {
					$result = "";
					if($requestType == "edit" AND $storage->saveData()) {
						$result = array("result"=>"saved");
					} else if($requestType == "new" AND $storage->createNewData()) {
						$result = array("result"=>"created","newID"=>$storage->getID());
					}
					echo json_encode($result);
				} else {
					echo "error";
				}
				
			}
		}
		
		//delete storages
		
		else if($request == "deleteStorage") {
			//check user rights
			if($session->checkRights("delete_storage") == True) {
				$storage = new Storage;
				//check if storage ID is send and valid
				if(isset($_POST['storageID']) AND $storage->loadData($_POST['storageID'])) {
					//check if storage has childs
					if($storage->checkForChilds() === False) {
						if($storage->deleteData() == True) {
							echo "success";
						} else {
							echo "error";
						}
					} else {
						echo "childsExisiting";
					}
				} else {
					echo "error";
				}
			} else {
				echo "missingRights";
			}
		}
		
		//save coordinates of boxes
		
		else if($request == "saveGrid") {
			//check user rights
			if($session->checkRights("edit_storage") == True) {
				//check if boxes array was transmitted correctly
				if(isset($_POST['boxes']) AND is_array($_POST['boxes'])) {
					foreach($_POST['boxes'] as $tmpBox) {
						$storage = new Storage;
						if(isset($tmpBox['ID']) AND $storage->loadData($tmpBox['ID']) AND $storage->getTypeID() == 3 AND isset($tmpBox['x']) AND isset($tmpBox['y'])) {
							$storage->setX($tmpBox['x']);
							$storage->setY($tmpBox['y']);
							$storage->saveData();
						}
					}
					echo "success";
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
