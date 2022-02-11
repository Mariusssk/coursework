<?php

//-----------------New Page---------------------
//Page for events (view, edit, etc)
//-----------------New Page---------------------

include_once("../resources/config.php");

//check if user is logged-in

if($session->loggedIn() === True) {
	include(TEMPLATES."/header/header_small.php");
	
	//Check if specific reuqest is send
	
	$requestAllowed = array("overview","locations","clients","editEvent");
	if(isset($_GET['request']) AND !empty($_GET['request']) AND in_array($_GET['request'],$requestAllowed)) {
		$request = $_GET['request'];
	} else {
		$request = "overview";
	}
	//Page
	
	?>
	<script> var protectedPage = true; </script>
	<div class="page event <?php echo $request;?>">
		<?php 
		//Load page data based on request
		//Objective 6.6
		//Page displaying list of all clients with the ability to edit and delete it
		if($request == "clients") {
			if($session->checkRights("edit_event_clients") == True) {
				//Load list of all event clients
				?>
				<div class="generalTable">
					<div class="row generalTableSearch">
						<!-- Search Inpus -->
						<div class="td col-md-4 col-sm-12">
							<input type="text" class="generalInput searchInput" data-search-name="name" placeholder="<?php echo EVENT_CLIENT_LIST_HEADER_NAME;?>">
						</div>
						<div class="td col-md-2 col-sm-12">
								<div class="generalCheckboxContainer"><?php echo EVENT_CLIENT_LIST_HEADER_EXTERNAL;?><input type="checkbox" class="generalCheckbox searchInput" data-search-name="external"></div>
							</div>
						<div class="td d-none d-md-block col-md-3">
							<div class="generalSearchBarButton" onclick="loadClientList()"> <?php echo WORD_SEARCH;?> </div>
						</div>
						<div class="td d-block d-md-none col-12">
							<div class="generalButton" onclick="loadClientList()"> <?php echo WORD_SEARCH;?> </div>
						</div>
						<div class="td d-none d-md-block col-md-3">
							<div class="generalSearchBarButton createNewToDoBtn" onclick="createNew('client')"> <?php echo WORD_NEW;?> </div>
						</div>
						<div class="td d-block d-md-none col-12">
							<div class="generalButton createNewToDoBtn" onclick="createNew('client')"> <?php echo WORD_NEW;?> </div>
						</div>
					</div>
					<div class="row generalTableHeader">
						<div class="td col-8 col-md-4">
							<?php echo EVENT_CLIENT_LIST_HEADER_NAME;?>
						</div>
						<div class="td col-4 col-md-2">
							<?php echo EVENT_CLIENT_LIST_HEADER_EXTERNAL;?>
						</div>
						<div class="td col-8 col-md-4">
							<?php echo EVENT_CLIENT_LIST_HEADER_DESCRIPTION;?>
						</div>
						<div class="td col-4 col-md-2">
							<?php echo WORD_ACTION;?>
						</div>
					</div>
					<div class="tableContent" id="clientList">
						<div class="row generalTableContentRow">
							<div class="td col-12">
								<?php echo WORD_LOADING;?>
							</div>
						</div>
					</div>
				</div>
				<script>
					//load all clients
					loadClientList();
				</script>
				<?php
			} else {
				include(TEMPLATES."/user/missing_rights.php");
			}
		} 
		
		//Objective 6.5
		//Page to display list of locations with the ability to edit and delete them
		else if($request == "locations") {
			if($session->checkRights("edit_event_locations") == True) {
				//Load list of all event location
				?>
				<div class="generalTable">
					<!-- Search Inputs -->
					<div class="row generalTableSearch">
						<div class="td col-md-6 col-sm-12">
							<input type="text" class="generalInput searchInput" data-search-name="name" placeholder="<?php echo EVENT_CLIENT_LIST_HEADER_NAME;?>">
						</div>
						<div class="td d-none d-md-block col-md-3">
							<div class="generalSearchBarButton" onclick="loadLocationList()"> <?php echo WORD_SEARCH;?> </div>
						</div>
						<div class="td d-block d-md-none col-12">
							<div class="generalButton" onclick="loadLocationList()"> <?php echo WORD_SEARCH;?> </div>
						</div>
						<div class="td d-none d-md-block col-md-3">
							<div class="generalSearchBarButton createNewToDoBtn" onclick="createNew('location')"> <?php echo WORD_NEW;?> </div>
						</div>
						<div class="td d-block d-md-none col-12">
							<div class="generalButton createNewToDoBtn" onclick="createNew('location')"> <?php echo WORD_NEW;?> </div>
						</div>
					</div>
					<div class="row generalTableHeader">
						<div class="td col-12 col-md-5">
							<?php echo EVENT_CLIENT_LIST_HEADER_NAME;?>
						</div>
						<div class="td col-12 col-md-5">
							<?php echo EVENT_CLIENT_LIST_HEADER_DESCRIPTION;?>
						</div>
						<div class="td col-12 col-md-2">
							<?php echo WORD_ACTION;?>
						</div>
					</div>
					<div class="tableContent" id="locationList">
						<div class="row generalTableContentRow">
							<div class="td col-12">
								<?php echo WORD_LOADING;?>
							</div>
						</div>
					</div>
				</div>
				<script>
					//load all locations
					loadLocationList();
				</script>
				<?php
			} else {
				include(TEMPLATES."/user/missing_rights.php");
			}
		} 
		
		//Objective 6
		//Page to display overview of all events split timespan
		else if($request == "overview") {
			//display overview of all events
			$events = Event::createTimeListAllEvents($session);
			
			?>
			<div class="eventsContainer">
			<?php
				foreach($events as $tmpCategoryName => $tmpEvents) {
					if(count($tmpEvents) > 0 OR $tmpCategoryName == "uncategorized") {
					?>
					<div class="eventCategoryContainer">
						<div class="categoryHeader">
							<?php
							//Set time category of event d
							if($tmpCategoryName == "running")
								echo EVENT_OVERVIEW_CATEGORY_RUNNING;
							else if($tmpCategoryName == "soon")
								echo EVENT_OVERVIEW_CATEGORY_SOON;
							else if($tmpCategoryName == "future")
								echo EVENT_OVERVIEW_CATEGORY_FUTURE;
							else if($tmpCategoryName == "past")
								echo EVENT_OVERVIEW_CATEGORY_PAST;
							else if($tmpCategoryName == "uncategorized")
								echo EVENT_OVERVIEW_CATEGORY_UNCATEGORIZED;
							?>
						</div>
						<div class="categoryContent">
							
							<?php
							//Load all events by category
							if($tmpCategoryName == "uncategorized" AND $session->checkRights("create_event") == True) {
								?>
								<div class="generalButton createNewToDoBtn" onclick="createNewEvent()"> New </div>
								<?php
							}
							foreach($tmpEvents as $tmpEvent) {
								$event = new Event;
								if($event->loadData($tmpEvent) AND $event->checkRights($session, "view")) {
									$tags = $event->loadTags();
									$client = $event->getClientName();
									$location = $event->getLocationName();
									?>
									<div class="container event" onclick="openEventPage('<?php echo $event->getID();?>')">
										<div class="eventTags row">
											<?php
											foreach($tags as $tmpTag) {
												$tag = new Tag;
												if($tag->loadData($tmpTag)) {
													?>
													<div class="col tag" style="background-color: <?php echo $tag->getColour();?>"> &nbsp; </div>
													<?php
												}
											}
											?>
										</div>
										<div class="eventName">
											<?php echo $event->getName();?>
										</div>
										<div class="eventTimes row">
											<div class="col-6  ">
												<?php echo EVENT_OVERVIEW_CONTAINER_STARTTIME;?>
											</div>
											<div class="col-6 endTime">
												<?php echo EVENT_OVERVIEW_CONTAINER_ENDTIME;?>
											</div>
											<div class="col-6 startTime">
												<?php echo $event->getDisplayTime("start");?>
											</div>
											<div class="col-6 endTime">
												<?php echo $event->getDisplayTime("end");?>
											</div>
											<?php
											if(!empty($location)) {
											?>
											<div class="col-12">
												<?php echo EVENT_OVERVIEW_CONTAINER_LOCATION;?>
											</div>
											<div class="col-12">
												<?php echo $location;?>
											</div>
											<?php
											}
											?>
											<?php
											if(!empty($client)) {
											?>
											<div class="col-12">
												<?php echo EVENT_OVERVIEW_CONTAINER_CLIENT;?>
											</div>
											<div class="col-12">
												<?php echo $client;?>
												<?php if($event->getClientExternal() == 1) echo " (".EVENT_OVERVIEW_CONTAINER_EXTERNAL.")";?>
											</div>
											<?php
											}
											?>
										</div>
									</div>
									<?php
								}
							}
							?>
						</div>
					</div>
					<?php
					}
				}
			?>
			</div>
			<?php
		} 
		//Objective 6.2
		//Display page to edit events with the ability to delete them 
		else if($request == "editEvent") {
			//display page to edit event
			$event = new Event;
			if(isset($_GET['ID']) AND $event->loadData($_GET['ID'])) {
				if($event->checkRights($session, "edit")) {
					?>
					<div class="editEventContainer row">
						<!-- Input Form data -->
						<div class="col-12 inputRow">
							<input class="generalInput dataInput" type="text" data-input-name="name" value="<?php echo $event->getName();?>" placeholder="<?php echo EVENT_EDIT_PLACEHOLDER_NAME;?>">
						</div>
						<div class="col-12 col-md-6 inputRow">
							<?php echo EventLocation::getSelect(["class" => "generalSelect dataInput", "data" => ["input-name", "location"]],$event->getLocationID(),EVENT_EDIT_PLACEHOLDER_LOCATION);?>
						</div>
						<div class="col-12 col-md-6 inputRow">
							<?php echo EventClient::getSelect(["class" => "generalSelect dataInput", "data" => ["input-name", "client"]],$event->getClientID(),EVENT_EDIT_PLACEHOLDER_CLIENT);?>
						</div>
						<div class="col-12 col-md-6 inputRow">
							<?php echo EVENT_EDIT_PLACEHOLDER_STARTDATE;?>:<br>
							<input type="date" class="generalDateinput dataInput" data-input-name="startDate" value="<?php echo $event->getTime("start","formDate");?>">
						</div>
						<div class="col-12 col-md-6 inputRow">
							<?php echo EVENT_EDIT_PLACEHOLDER_ENDDATE;?>:<br>
							<input type="date" class="generalDateinput dataInput" data-input-name="endDate" value="<?php echo $event->getTime("end","formDate");?>">
						</div>
						<div class="col-12 col-md-6 inputRow">
							<?php echo EVENT_EDIT_PLACEHOLDER_STARTTIME;?>:<br>
							<input type="time" class="generalDateinput dataInput" data-input-name="startTime" value="<?php echo $event->getTime("start","formTime");?>">
						</div>
						<div class="col-12 col-md-6 inputRow">
							<?php echo EVENT_EDIT_PLACEHOLDER_ENDTIME;?>:<br>
							<input type="time" class="generalDateinput dataInput" data-input-name="endTime" value="<?php echo $event->getTime("end","formTime");?>">
						</div>
						<div class="col-12 inputRow">
							<?php echo EVENT_EDIT_PLACEHOLDER_DESCRIPTION;?>:<br>
							<textarea class="generalTextarea dataInput" data-input-name="description"><?php echo $event->getDescription();?></textarea>
						</div>
						<div class="col-12 inputRow buttonConatiner">
							<div class="row saveButtonBox">
								<div class="col-12 col-md-6">
									<div class="generalButton" onclick="saveEventData('<?php echo $event->getID();?>')"> <?php echo WORD_SAVE;?> </div>
								</div>
								<div class="col-12 col-md-6">
									<div class="generalButton" onclick="deleteEvent('','openForm')"> <?php echo WORD_DELETE;?> </div>
								</div>
							</div>
							<div class="row deleteButtonBox none">
								<div class="col-12 col-md-6">
									<div class="generalButton" onclick="deleteEvent('','abort')"> <?php echo WORD_ABORT;?> </div>
								</div>
								<div class="col-12 col-md-6">
									<div class="generalButton" onclick="deleteEvent('<?php echo $event->getID();?>','delete')"> <?php echo WORD_DELETE;?> </div>
								</div>
							</div>
						</div>
						<div class="col-12">
							<hr class="hr">
						</div>
						<!-- Block to add and remove tags -->
						<!-- Objective 8.2 -->
						<div class="col-12">
							<h4><?php echo EVENT_EDIT_HEADLINE_TAGS;?></h4>
							<div class="tagsList">
								<?php
								$allTags = Tag::getAll();
								$tags = $event->loadTags();
								foreach($tags as $tmpTag) {
									$tag = new Tag;
									if($tag->loadData($tmpTag)) {
										?>
										<span class="tag noselect" style="color: <?php echo getContrastColor($tag->getColour());?> ;background-color: <?php echo $tag->getColour();?>">
											<?php echo $tag->getName();?>
											<span onclick="deleteEventTag('<?php echo $tag->getID();?>','<?php echo $event->getID();?>')" class="generalIcon onclick"><i class="fa fa-times-circle" aria-hidden="true"></i></span>
										</span>
										<?php
									}
								}
								?>
								<span class="generalIcon onclick" onclick="addEventTagForm()"><i class="fa fa-plus-square" aria-hidden="true"></i></span>
							</div>
							<div class="addTagContainer row none">
								<div class="col-10">
									<select class="addTagSelect generalSelect">
										<?php
										foreach($allTags as $tmpTag) {
											$tag = new Tag;
											if($tag->loadData($tmpTag)) {
												?>
												<option value="<?php echo $tag->getID();?>">
													<?php echo $tag->getName();?>
												</option>
												<?php
											}
										}
										?>
									</select>
								</div>
								<div class="col-2">
									<span class="generalIcon onclick" onclick="addEventTag('<?php echo $event->getID();?>')"><i class="fa fa-check-square" aria-hidden="true"></i></span>
								</div>
							</div>
						</div>
						<?php
						if($session->checkRights("edit_event_responsibles") == True) {
						?>
							<div class="col-12">
								<hr class="hr">
							</div>
							<!-- List event responsibles -->
							<!-- Option to edit and remove them -->
							<!-- Objective 6.4 -->
							<div class="col-12">
								<h4><?php echo EVENT_EDIT_HEADLINE_RESPONSIBLES;?></h4>
								<div class="responsibleList">
									<?php
									$allUser = User::getAll();
									$responsible = $event->getResponsible();
									foreach($responsible as $tmpUser) {
										$user = new User;
										if($user->loadData($tmpUser)) {
											?>
											<span class="tag noselect" >
												<?php echo $user->getName(1,1);?>
												<span onclick="deleteEventResponsible('<?php echo $user->getID();?>','<?php echo $event->getID();?>')" class="generalIcon onclick"><i class="fa fa-times-circle" aria-hidden="true"></i></span>
											</span>
											<?php
										}
									}
									?>
									<span class="generalIcon onclick" onclick="addResposibleForm()"><i class="fa fa-plus-square" aria-hidden="true"></i></span>
								</div>
								<div class="addResposibleContainer row none">
									<div class="col-10">
										<select class="addResponsibleSelect generalSelect">
											<?php
											foreach($allUser as $tmpUser) {
												$user = new User;
												if($user->loadData($tmpUser)) {
													?>
													<option value="<?php echo $user->getID();?>">
														<?php echo $user->getName(1,1);?>
													</option>
													<?php
												}
											}
											?>
										</select>
									</div>
									<div class="col-2">
										<span class="generalIcon onclick" onclick="addEventResponsible('<?php echo $event->getID();?>')"><i class="fa fa-check-square" aria-hidden="true"></i></span>
									</div>
								</div>
							</div>
						<?php
						}
						
						//Display if notification request is active
						//Objective 11.1
						
						$notificationsActive = NotificationRequest::checkIfRequestActivated($session->getSessionUserID(),1,$event->getID());
						
						$active = "false";
						$bell = '<i class="fa fa-bell-slash" aria-hidden="true"></i>';
						if($notificationsActive == True) {
							$active = "true";
							$bell =  '<i class="fa fa-bell" aria-hidden="true"></i>';
						}
						?>
						<div class="col-12">
							<hr class="hr">
						</div>
						<div class="col-12 commentsHeadline">
							<span><h4><?php echo WORD_COMMENTS;?></h4> <span class="bellContainer"><span class="generalIcon onclick" onclick="toggleEventNotifications('event','<?php echo $event->getID();?>',<?php echo $active;?>)"><?php echo $bell;?></span></span></span>
						</div>
						<div class="col-12" id="commentContainer">
						</div>
					</div>
					<script>
						loadCommentSection("event", <?php echo $event->getID();?>,"commentContainer");
					</script>
					<?php
				} else {
					include(TEMPLATES."/user/missing_rights.php");
				}
			} else {
				?>
				<div class="center">
					<h3> <?php echo EVENT_EDIT_ERROR_LOADING_DATA;?> </h3>
				</div>
				<?php
			}
		}
		
		?>
	</div>
	<?php
	include(TEMPLATES."/footer/footer.php");
} else {
	include(TEMPLATES."/page/login_needed.php");
}
?>
