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
		//Objective 6.6
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
		//Objective 6.6.1
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
		//Objectives 6.5.1 / 6.6.1
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
		//Objectivs 6.5/6.6
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
		//Objectives 6.5
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
		//Objectives 6.5.1
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
		
		//create template new event
		//Objectives 6.1
		else if($request == "createNewEvent") {
			//check user rights
			if($session->checkRights("create_event") == True) {
				$event = new Event;
				
				$event->setName(EVENT_CREATION_TEMPLATE_NAME);
				$event->setStartTime("1000-01-01","00:00");
				
				if($event->createNewData()) {
					echo "success";
				} else {
					echo "error";
				}
			} else {
				echo "missingRights";
			}
		}
		
		//delete event and attributes as tags
		//Objectives 6
		else if($request == "deleteEvent") {
			$event = new Event;
			if(isset($_POST['eventID']) AND $event->loadData($_POST['eventID'])) {
				
				if($event->checkRights($session, "delete")) {
					if($event->deleteEvent()) {
						echo "success";
					}
				} else {
					echo "missingRights";
				}
			} else {
				echo "error";
			}
			
		}
		
		//delete specific tag from event
		//Objectives 8.2
		else if($request == "deleteTag") {
			$event = new Event;
			$tag = new Tag;
			$assignment = new TagAssignment;
			if(
				isset($_POST['eventID']) AND $event->loadData($_POST['eventID']) AND
				isset($_POST['tagID']) AND $tag->loadData($_POST['tagID']) AND
				$assignment->loadDataOnTagAndAttributeID($tag->getID(), $event->getID(), 1)
			) {
				
				if($event->checkRights($session, "edit")) {
					if($assignment->deleteData()) {
						echo "success";
					}
				} else {
					echo "missingRights";
				}
			} else {
				echo "error";
			}
			
		}
		
		//add tag to event if not already added
		//Objectives 8.2
		else if($request == "addTag") {
			$event = new Event;
			$tag = new Tag;
			if(
				isset($_POST['eventID']) AND $event->loadData($_POST['eventID']) AND
				isset($_POST['tagID']) AND $tag->loadData($_POST['tagID']) 
			) {
				
				if($event->checkRights($session, "edit")) {
					$assignment = new TagAssignment;
					if(TagAssignment::checkIfAttributeHasTag(1,$event->getID(),$tag->getID())) {
						echo "alreadyUsed";
					} else if($assignment->createTagForEvent($event->getID(), $tag->getID())) {
						echo "success";
					}
				} else {
					echo "missingRights";
				}
			} else {
				echo "error";
			}
			
		}
		
		//add resonsible user to event if not already added
		//Objectives 6.4
		else if($request == "addResponsible") {
			$event = new Event;
			$user = new User;
			if(
				isset($_POST['eventID']) AND $event->loadData($_POST['eventID']) AND
				isset($_POST['userID']) AND $user->loadData($_POST['userID']) 
			) {
				
				if($event->checkRights($session, "edit") AND $session->checkRights("edit_event_responsibles") == True) {
					$responsible = new EventResponsible;
					if(EventResponsible::checkIfIsResponsible($user->getID(),$event->getID())) {
						echo "alreadyUsed";
					} else if(
						$responsible->setUserID($user->getID()) AND
						$responsible->setEventID($event->getID()) AND
						$responsible->createNewData()
					) {
						echo "success";
					}
				} else {
					echo "missingRights";
				}
			} else {
				echo "error";
			}
			
		}
		
		//delete specific event responsible from event
		//Objectives 6.4
		else if($request == "deleteResponsible") {
			$event = new Event;
			$user = new User;
			$responsible = new EventResponsible;
			if(
				isset($_POST['eventID']) AND $event->loadData($_POST['eventID']) AND
				isset($_POST['userID']) AND $user->loadData($_POST['userID']) AND
				$responsible->loadDataOnUserAndEventID($user->getID(), $event->getID())
			) {
				
				if($event->checkRights($session, "edit") AND $session->checkRights("edit_event_responsibles") == True) {
					if($responsible->deleteData()) {
						echo "success";
					}
				} else {
					echo "missingRights";
				}
			} else {
				echo "error";
			}
			
		}
		
		//save event data after it was editied
		//Objectives 6.2
		else if($request == "saveEventData") {
			$event = new Event;
			
			if(
				isset($_POST['eventID']) AND $event->loadData($_POST['eventID']) 
			) {
				
				if($event->checkRights($session, "edit")) {
					
					$name = "";
					$locationID = 0;
					$clientID = 0;
					$startDate = "";
					$endDate = "";
					$startTime = "";
					$endTime = "";
					$description = "";
					
					$dataValues = ["name","locationID","clientID","startDate","endDate","startTime","endTime","description"];
					
					foreach($dataValues as $tmpValue) {
						if(isset($_POST[$tmpValue])) {
							${$tmpValue} = $_POST[$tmpValue];
						}
					}
					
					
					if(
						$event->setName($name) AND
						$event->setLocation($locationID) AND
						$event->setClient($clientID) AND
						$event->setStartTime($startDate,$startTime) AND
						$event->setEndTime($endDate, $endTime) AND
						$event->setDescription($description) AND
						$event->saveData()
					) {
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
