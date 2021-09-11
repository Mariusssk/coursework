<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

ob_start();

include "../../resources/config.php";

if(isset($_POST['requestType']) AND !empty($_POST['requestType'])) {
	$request = $_POST['requestType'];
	
	
	if($session->loggedIn() == True) {
		//load items based on search
		if($request == "loadItems") {
			//check user rights
			if($session->checkRights("view_all_items") == True) {
				//load items
				$items = Item::getAll();
				$post = array();
				
				if(isset($_POST['search'])) {
					$search = $_POST['search'];
				} else {
					$search = array();
				}
				
				//load information for each item
				foreach($items as $tmpItem) {
					$item = new Item;
					if($item->loadData($tmpItem)) {
						$searchFail = 0;
						$tmpItemArray = array();
						$tmpItemArray['ID'] = $item->getID();
						$tmpItemArray['name'] = $item->getName();
						$tmpItemArray['typeName'] = $item->getTypeName();
						$tmpItemArray['consumeable'] = $item->getConsumeable();
						$tmpItemArray['amount'] = $item->getAmount();
						
						if(isset($search['type']) AND !empty($search['type']) AND $search['type'] != $item->getTypeID()) {
							$searchFail = 1;
						}
						
						if(isset($search['name']) AND !empty($search['name']) AND strpos($tmpItemArray['name'],$search['name']) === False) {
							$searchFail = 1;
						}
						
						if(isset($search['consumable']) AND !empty($search['consumable']) AND $search['consumable'] != $item->getConsumeable()) {
							$searchFail = 1;
						}
						
						if(isset($search['amount']) AND (!empty($search['amount']) OR $search['amount'] == 0)  AND $search['amount'] != $item->getAmount()) {
							$searchFail = 1;
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
		
		//Change amount of item in storage 
		
		else if($request == "changeItemAmount") {
			if($session->checkRights("edit_item") == True) {
				$item = new Item;
				if(isset($_POST['itemID']) AND $item->loadData($_POST['itemID']) === True AND isset($_POST['amount'])) {
					$newAmount = $item->getAmount() + $_POST['amount'];
					if($item->setAmount($newAmount) AND $item->saveData()) {
						echo $item->getAmount();
					}
				}
			} else {
				echo "missingRights";
			}
		}
		
		//delete storages
		
		else if($request == "deleteItem") {
			//check user rights
			if($session->checkRights("delete_item") == True) {
				$item = new Item;
				//check if item ID is send and valid
				if(isset($_POST['itemID']) AND $item->loadData($_POST['itemID'])) {
					//check if item has childs
					if($item->deleteData() == True) {
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
		
		//save data or create new
		else if($request == "saveItemData") {
			$item = new Item;
			
			if(isset($_POST['itemID']) AND $item->loadData($_POST['itemID'])) {
				$requestType = "edit";
			} else if((isset($_POST['itemID']) AND empty($_POST['itemID'])) OR !isset($_POST['itemID'])) {
				$requestType = "new";
			} else {
				$requestType = "";
				echo "error";
			}
			
			//check user rights
			
			if(($requestType == "edit" AND $session->checkRights("edit_item") == False) OR ($requestType == "new" AND $session->checkRights("create_new_item") == False)) {
				echo "missingRights";
			} else if($requestType != "") {
				$saveFail = 0;
				
				//check if data has been send correctly
				if(isset($_POST['itemData'])) {
					$itemData = $_POST['itemData'];
				} else {
					$itemData = array();
				}
				
				//set item name
				if(isset($itemData['name'])) {
					$name = $itemData['name'];
				} else {
					$name = "";
				}
				
				if(!$item->setName($name)) {$saveFail = 1;}
				
				//set length
				if(isset($itemData['length'])) {
					$length = $itemData['length'];
				} else {
					$length = "";
				}
				
				if(!$item->setLength($length)) {$saveFail = 1;}
				
				//set amount
				if(isset($itemData['amount'])) {
					$amount = $itemData['amount'];
				} else {
					$amount = "";
				}
				
				if(!$item->setAmount($amount)) {$saveFail = 1;}
				
				//set description
				if(isset($itemData['description'])) {
					$description = $itemData['description'];
				} else {
					$description = "";
				}
				
				if(!$item->setDescription($description)) {$saveFail = 1;}
				
				//set type ID
				if(isset($itemData['type'])) {
					$type = $itemData['type'];
				} else {
					$type = "";
				}
				
				if(!$item->setTypeID($type)) {$saveFail = 1;}
				
				//set storage ID
				if(isset($itemData['storage'])) {
					$storageID = $itemData['storage'];
				} else {
					$storageID = "";
				}
				
				if(!$item->setStorageID($storageID)) {$saveFail = 1;}
				
				if($saveFail == 0) {
					$result = "";
					if($requestType == "edit" AND $item->saveData()) {
						$result = array("result"=>"saved");
					} else if($requestType == "new" AND $item->createNewData()) {
						$result = array("result"=>"created","newID"=>$item->getID());
					}
					echo json_encode($result);
				} else {
					echo "error";
				}
				
			}
		}
		
		
	} 

	
}

ob_flush();
