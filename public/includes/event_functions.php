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

	} 
	
	

	
}

ob_flush();
