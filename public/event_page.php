<?php

include_once("../resources/config.php");

//check if user is logged-in

if($session->loggedIn() === True) {
	include(TEMPLATES."/header/header_small.php");
	
	//Check if specific reuqest is send
	
	$requestAllowed = array("overview","locations","clients");
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
		if($request == "clients") {
			if($session->checkRights("edit_event_clients") == True) {
				//Load list of all event clients
				?>
				<div class="generalTable">
					<div class="row generalTableSearch">
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
		} else if($request == "locations") {
			if($session->checkRights("edit_event_locations") == True) {
				//Load list of all event location
				?>
				<div class="generalTable">
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
					//load all clients
					loadLocationList();
				</script>
				<?php
			} else {
				include(TEMPLATES."/user/missing_rights.php");
			}
		} else if($request == "overview") {
			//display overview of all events
			$events = Event::createTimeListAllEvents();
			
			?>
			<div class="eventsContainer">
			<?php
				foreach($events as $tmpCategoryName => $tmpEvents) {
					?>
					<div class="eventCategoryContainer">
						<div class="categoryHeader">
							test
						</div>
						<div class="categoryContent">
							<?php
							foreach($tmpEvents as $tmpEvent) {
								$event = new Event;
								if($event->loadData($tmpEvent)) {
									$tags = $event->loadTags();
									$client = $event->getClientName();
									$location = $event->getLocationName();
									?>
									<div class="container event">
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
			?>
			</div>
			<?php
		}
		?>
	</div>
	<?php
	include(TEMPLATES."/footer/footer.php");
} else {
	include(TEMPLATES."/page/login_needed.php");
}

