<?php

//-----------------New PHP Functions File---------------------
//File for all php functions for the items/hardware
//-----------------New PHP Functions File---------------------

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

ob_start();

include "../../resources/config.php";

if(isset($_POST['requestType']) AND !empty($_POST['requestType'])) {
	$request = $_POST['requestType'];
	
	
	if($session->loggedIn() == True) {
		
		//load items based on search
		//Objectives 4
		if($request == "loadItems") {
			//check user rights
			if($session->checkRights("view_all_items") == True) {
				
				//set serach parameters
				if(isset($_POST['search'])) {
					$search = $_POST['search'];
				} else {
					$search = array();
				}
				
				
				//load items
				if(isset($search['lend']) AND $search['lend'] == 1) {
					$items = Lend::getItemsLendByUser($session->getSessionUserID());
				} else {
					$items = Item::getAll();
				}
				
				if(isset($search['storages'])) {
					$storageList = explode(";",$search['storages']);
					$storageList = array_filter($storageList);
				} else {
					$storageList = array();
				}
				
				$post = array();
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
						
						//search through items
						//Objectives 4.6
						
						if(isset($search['lend']) AND $search['lend'] == 1) {
							$lend = new Lend;
							if($lend->loadDataByItemID($session->getSessionUserID(),$item->getID())) {
								$tmpItemArray['amount'] = $lend->getAmount(); 
								$tmpItemArray['returnDate'] = $lend->getReturnDate(); 
								$tmpItemArray['returnDateForm'] = $lend->getReturnDate('form');
							}
						} else {
							$tmpItemArray['actualAmount'] = $item->getAmount() - Lend::calculateTotalAmountLend($item->getID());
							
							$tmpItemArray['amount'] = $item->getAmount();
							
							if(isset($search['amount']) AND (!empty($search['amount']) OR $search['amount'] == 0)  AND $search['amount'] != $item->getAmount()) {
								$searchFail = 1;
							}
						}
						
						
						if(isset($search['type']) AND !empty($search['type']) AND $search['type'] != $item->getTypeID()) {
							$searchFail = 1;
						}
						
						if(isset($search['name']) AND !empty($search['name']) AND strpos(strToUpper($tmpItemArray['name']),strToUpper($search['name'])) === False) {
							$searchFail = 1;
						}
						
						if(count($storageList) > 0 AND !in_array($item->getStorageID(), $storageList)) {
							$searchFail = 1;
						}
						
						if(isset($search['consumable']) AND !empty($search['consumable']) AND $search['consumable'] != $item->getConsumeable()) {
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
		
		//Change amount of item in storage / lended
		//4.4.1 / 4.5.1.2
		else if($request == "changeItemAmount") {
			//Check if storage or lended
			if(isset($_POST['attribute']) AND $_POST['attribute'] = "lend") {
				//check user rights
				if($session->checkRights("lend_item") == True) {
					$lend = new Lend;
					if(isset($_POST['itemID']) AND $lend->loadDataByItemID($session->getSessionUserID(), $_POST['itemID']) === True AND isset($_POST['amount'])) {
						$newAmount = $lend->getAmount() + $_POST['amount'];
						if($newAmount <= 0) {$newAmount = 0;}
						if($lend->setAmount($newAmount) AND $lend->saveData()) {
							echo $lend->getAmount();
						}
					}
				} else {
					echo "missingRights";
				}
			} else {
				//check user rights
				if($session->checkRights("edit_item") == True) {
					$item = new Item;
					if(isset($_POST['itemID']) AND $item->loadData($_POST['itemID']) === True AND isset($_POST['amount'])) {
						$newAmount = $item->getAmount() + $_POST['amount'];
						if($newAmount <= 0) {$newAmount = 0;}
						if($item->setAmount($newAmount) AND $item->saveData()) {
							echo $item->getAmount();
						}
					}
				} else {
					echo "missingRights";
				}
			}
		}
		
		//delete item
		//Objectives 4.1.2
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
		
		//save lend return date
		//Objective 4.5.1.3
		else if($request == "saveReturnDate") {
			//check user rights
			if($session->checkRights("lend_item") == True) {
				$lend = new Lend;
				//check if item ID is send and valid
				if(isset($_POST['itemID']) AND $lend->loadDataByItemID($session->getSessionUserID(), $_POST['itemID']) AND isset($_POST['returnDate'])) {
					//check if item has childs
					if($lend->setReturnDate($_POST['returnDate']) == True AND $lend->saveData() == True) {
						$returnDate = $lend->getReturnDate();
						if(empty($returnDate)) {
							echo "empty";
						} else {
							echo $returnDate;
						}
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
		
		//return item which was lend
		//Objective 4.5.1.4
		else if($request == "returnItemLend") {
			//check user rights
			if($session->checkRights("lend_item") == True) {
				$lend = new Lend;
				//check if item ID is send and valid
				if(isset($_POST['itemID']) AND $lend->loadDataByItemID($session->getSessionUserID(), $_POST['itemID'])) {
					//check if item has childs
					if($lend->setReturned(1) == True AND $lend->saveData() == True) {
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
		
		//get name of item

		else if($request == "loadItemName") {
			//check user rights
			if($session->checkRights("view_all_items") == True OR $session->checkRights("view_specific_item") == True) {
				$item = new Item;
				//check if item ID is send and valid
				if(isset($_POST['itemID']) AND $item->loadData($_POST['itemID'])) {
					//return item name
					echo $item->getName();
				} else {
					echo "error";
				}
			} else {
				echo "missingRights";
			}
		}
		
		//lend item
		//Objective 4.5
		else if($request == "lendItem") {
			//check user rights
			if($session->checkRights("lend_item") == True) {
				$item = new Item;
				//check if item ID is send and valid
				if(isset($_POST['itemID']) AND $item->loadData($_POST['itemID']) AND isset($_POST['amount']) AND is_numeric($_POST['amount']) AND isset($_POST['returnDate'])) {
					$lend = new Lend;
					if($lend->loadDataByItemID($session->getSessionUserID(), $item->getID())) {
						$newAmount = $_POST['amount'] + $lend->getAmount();
						$lend->setAmount($newAmount);
						if($lend->saveData()) {
							echo "success";
						} else {echo "errror";}
					} else {
						$lend = new Lend;
						$lend->setUserID($session->getSessionUserID());
						$lend->setItemID($item->getID());
						$lend->setAmount($_POST['amount']);
						$lend->setReturnDate($_POST['returnDate']);
						if($lend->createNewData()) {
							echo "success";
						} else {echo "errror";}
					}
				} else {
					echo "error";
				}
			} else {
				echo "missingRights";
			}
		}
		
		//save data or create new
		//Objective 4.1.1
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
?>