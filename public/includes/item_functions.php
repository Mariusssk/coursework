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
		
	} 

	
}

ob_flush();
