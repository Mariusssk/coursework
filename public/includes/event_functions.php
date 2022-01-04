<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

ob_start();

include "../../resources/config.php";

if(isset($_POST['requestType']) AND !empty($_POST['requestType'])) {
	$request = $_POST['requestType'];
	
	
	if($session->loggedIn() == True) {
		
		
		//load list of event clients to display
		if($request == "loadCLientList") {
			//check user rights
			if($session->checkRights("edit_event_clients") == True) {
				
				//set serach parameters
				if(isset($_POST['search'])) {
					$search = $_POST['search'];
				} else {
					$search = array();
				}
				
				
				$clients = EventClient::getAll();
				
				
				
				$post = array();
				//load information for each item
				foreach($clients as $tmpClient) {
					$client = new EventClient;
					if($client->loadData($tmpClient)) {
						$searchFail = 0;
						$tmpItemArray = array();
						$tmpItemArray['ID'] = $client->getID();
						$tmpItemArray['name'] = $client->getName();
						$tmpItemArray['external'] = $client->getExternal();
						$tmpItemArray['description'] = $client->getDescription();
						
						if(isset($search['name']) AND !empty($search['name']) AND strpos(strToUpper($tmpItemArray['name']),strToUpper($search['name'])) === False) {
							$searchFail = 1;
						}
						
						if(isset($search['external']) AND !empty($search['external']) AND $tmpItemArray['external'] != $search['external']) {
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
		
		//save data for edited client
		
		else if($request == "saveClientData") {
			//check user rights
			if($session->checkRights("edit_event_clients") == True) {
				$client = new EventClient;
				
				if(isset($_POST['clientID']) AND $client->loadData($_POST['clientID'])) {
					if(
						isset($_POST['name']) AND $client->setName($_POST['name']) AND
						isset($_POST['description']) AND $client->setDescription($_POST['description']) AND
						isset($_POST['external']) AND $client->setExternal($_POST['external']) AND
						$client->saveData()
					){
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
		
		//delete data if unused
		
		else if($request == "deleteData") {
			if(isset($_POST['type']) AND isset($_POST['dataID'])) {
				
				//load transmitted data
				$type = $_POST['type'];
				$dataID = $_POST['dataID'];
				
				
				//check what to delete and delete if not used
				if($type == "client" AND $session->checkRights("edit_event_clients") == True) {
					$client = new EventClient;
					
					if($client->loadData($dataID)) {
						if($client->checkIfUsed()) {
							echo "inUse";
						} else if($client->deleteData()) {
							echo "success";
						} else {
							echo "error";
						}
					} else {
						echo "error";
					}
				} else if($type == "location" AND $session->checkRights("edit_event_clients") == True) {
					$location = new EventLocation;
					
					if($location->loadData($dataID)) {
						if($location->checkIfUsed()) {
							echo "inUse";
						} else if($location->deleteData()) {
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
		
		//create new template 
		
		else if($request == "createNew") {
			if(isset($_POST['type'])) {
				
				//load transmitted data
				$type = $_POST['type'];
				
				//check which type to create and then create new entry
				if($type == "client" AND $session->checkRights("edit_event_clients") == True) {
					$client = new EventClient;
					
					$client->setName(EVENT_CLIENT_NEW_TEMPLATE_NAME);
					$client->setExternal(0);
					
					if($client->createNewData()) {
						echo "success";
					} else {
						echo "error";
					}
				} else if($type == "location" AND $session->checkRights("edit_event_locations") == True) {
					$location = new EventLocation;
					
					$location->setName(EVENT_LOCATION_NEW_TEMPLATE_NAME);
					
					if($location->createNewData()) {
						echo "success";
					} else {
						echo "error";
					}
				} else {
					echo "missingRights";
				}
			}
		}
		
		//load list of all locations
		
		else if($request == "loadLocationList") {
			//check user rights
			if($session->checkRights("edit_event_locations") == True) {
				
				//set serach parameters
				if(isset($_POST['search'])) {
					$search = $_POST['search'];
				} else {
					$search = array();
				}
				
				
				$locations = EventLocation::getAll();
				
				
				
				$post = array();
				//load information for each item
				foreach($locations as $tmpLocation) {
					$location = new EventLocation;
					if($location->loadData($tmpLocation)) {
						$searchFail = 0;
						$tmpItemArray = array();
						$tmpItemArray['ID'] = $location->getID();
						$tmpItemArray['name'] = $location->getName();
						$tmpItemArray['description'] = $location->getDescription();
						
						if(isset($search['name']) AND !empty($search['name']) AND strpos(strToUpper($tmpItemArray['name']),strToUpper($search['name'])) === False) {
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
		
		//save data for edited location
		
		else if($request == "saveLocationData") {
			//check user rights
			if($session->checkRights("edit_event_locations") == True) {
				$location = new EventLocation;
				
				if(isset($_POST['locationID']) AND $location->loadData($_POST['locationID'])) {
					if(
						isset($_POST['name']) AND $location->setName($_POST['name']) AND
						isset($_POST['description']) AND $location->setDescription($_POST['description']) AND
						$location->saveData()
					){
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
