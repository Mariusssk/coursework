//CLient Lits

//load list of all clients

function loadClientList() {
	
	var search = {};
	
	var searchInputs = document.querySelectorAll(".generalTableSearch .searchInput");
	
	for(i = 0;i < Object.keys(searchInputs).length;i++) {
		var searchKey = searchInputs[i].dataset.searchName;
		if(searchInputs[i].classList.contains("generalCheckbox")) {
			if(searchInputs[i].checked  == true) {
				search[searchKey] = "1";
			} else {
				search[searchKey] = "";
			}
		} else {
			search[searchKey] = searchInputs[i].value;
		}
	}
	

	//send request to php page
	$.post(INCLUDES+"/event_functions.php",{
		requestType: "loadCLientList",
		search: search
	},
	function(data, status){
		var clients = "";
		var dataCut = parsePostData(data);
		//check if request is valid
		if(dataCut == "error" || dataCut == "") {
			headerNotification(LANG.ERROR_REQUEST_FAILED,"red");
		} else if(dataCut == "missingRights") {
			headerNotification(LANG.USER_RIGHTS_MISSING,"red");
		} else if(dataCut != "") {
			data = JSON.parse(data);
			for(var i = 0; i < Object.keys(data).length; i++){
				var client = data[i];
				
				//set external check
				
				var external = "";
				
				if(client['external'] == 1) {
					external = `<span class="icon generalIcon"><i class="fa fa-check-square" aria-hidden="true"></i></span>`;
				}
				
				//set description
				var description = "";
				
				if(client['description'] != null) {
					description = client['description'];
				}
				
				//check description length
				
				if(description.length > 70 || description.split(/\r?\n/).length > 3) {
					var tmpDescription = description.slice(70);
					var previewdescription = description.slice(0,tmpDescription.indexOf(' ')+71);
					description = `
					<span class="descriptionPreview">`+previewdescription+`</span>
					<span class="descriptionFull none">`+description+`</span>
					<span class="readMoreButton descriptionButton" onclick="toggleDescriptionContainer(`+client['ID']+`)"><br>`+LANG.EVENT_CLIENT_LIST_DESCRIPTION_READ_MORE+`</span>
					<span class="readLessButton none descriptionButton" onclick="toggleDescriptionContainer(`+client['ID']+`)"><br>`+LANG.EVENT_CLIENT_LIST_DESCRIPTION_READ_LESS+`</span>
					`;
				} else {
					description = `<span class="descriptionFull">`+description+`</span>`;
				}
				
				
				
				description.replace(/\\n/g, "<br />");
				
				clients += `
				<div class="row generalTableContentRow" data-id="`+client['ID']+`">
					<div class="td col-8 col-md-4 data" data-field-name="name">`+client['name']+`</div>
					<div class="td col-4 col-md-2 ">
						`+external+`
						<div class="none data" data-field-name="external">`+client['external']+`</div>
					</div>
					<div class="td col-8 col-md-4 data" data-field-name="description">
						`+description+`
					</div>
					<div class="td col-4 col-md-2 actionButtons noselect">
						<span class="icon generalIcon" onclick="editClient('`+client['ID']+`')">
							<i class="fa fa-pencil-square" aria-hidden="true"></i>
						</span>
						<span class="icon generalIcon" onclick="deleteData('client','`+client['ID']+`')">
							<i class="fa fa-trash" aria-hidden="true"></i>
						</span>
					</div>
				</div>
				<div class="row">
					<div class="col-12"><hr></div>
				</div>
				`;
				
			}
			
			
		}
		
		
		
		//Put in message if no data was found
		if(clients == "") {
			clients = '<div class="col-12 td">'+LANG.EVENTS_CLIENT_LIST_NON_FOUND+'</td>';
		}
		
		document.getElementById("clientList").innerHTML = clients;
	});
}


//toggle if full description should be shown

function toggleDescriptionContainer(dataId) {	
	var clientContainer = document.querySelector(".page.event .generalTableContentRow[data-id='"+dataId+"']");

	if(clientContainer) {
		var fullDesc = clientContainer.querySelector(".descriptionFull");
		var previewDesc = clientContainer.querySelector(".descriptionPreview");
		
		var moreButton = clientContainer.querySelector(".readMoreButton");
		var lessButton = clientContainer.querySelector(".readLessButton");
		
		previewDesc.classList.toggle("none");
		fullDesc.classList.toggle("none");
		moreButton.classList.toggle("none");
		lessButton.classList.toggle("none");
		
	}
	
}

//toggle form to edit client data

function editClient(clientID) {
	var clientContainer = document.querySelector(".page.event.clients .generalTableContentRow[data-id='"+clientID+"']");
	
	//check if form is already display
	
	//if yes: remove form and display data
	//if no: display form
	
	if(clientContainer.querySelector(".clientEditForm")) {
		
		var clientData = clientContainer.querySelector(".clientData").innerHTML;
		
		clientContainer.innerHTML = clientData;
		
	} else {
		
		var clientData = clientContainer.innerHTML;
		
		var name = clientContainer.querySelector(".data[data-field-name='name']").innerHTML;
		var description = clientContainer.querySelector(".data[data-field-name='description'] .descriptionFull").innerHTML;
		var external = clientContainer.querySelector(".data[data-field-name='external']").innerHTML;
		
		var checked = "";
		if(external == "1") {
			var checked = "checked";
		}
		
		clientContainer.innerHTML = `
		<div class="clientData none">`+clientData+`</div>
		<div class="clientEditForm col-12">
			<div class="row">
				<div class="td col-8 col-md-4">
					<input type="text" data-input-name="name" value="`+name+`" class="dataInput generalInput">
				</div>
				<div class="td col-4 col-md-2">
					<input type="checkbox" class="dataInput generalCheckbox" data-input-name="external" `+checked+`>
				</div>
				<div class="td col-8 col-md-4">
					<textarea data-input-name="description" class="dataInput generalTextarea">`+description+`</textarea>
				</div>
				<div class="td col-4 col-md-2 actionButtons noselect">
					<span class="icon generalIcon" onclick="editClient('`+clientID+`')">
						<i class="fa fa-window-close" aria-hidden="true"></i>
					</span>
					<span class="icon generalIcon" onclick="saveClientData('`+clientID+`')">
						<i class="fa fa-check-square" aria-hidden="true"></i>
					</span>
				</div>
			</div>
		</div>`;
	}
}

//save data for client change

function saveClientData(clientID) {
	var clientContainer = document.querySelector(".page.event.clients .generalTableContentRow[data-id='"+clientID+"'] .clientEditForm");
	
	//get form data
	
	var name = clientContainer.querySelector(".dataInput[data-input-name='name']").value;
	var description = clientContainer.querySelector(".dataInput[data-input-name='description']").value;
	var external = clientContainer.querySelector(".dataInput[data-input-name='external']").checked;
	
	if(external === true) {
		external = 1;
	} else {
		external = 0;
	}


	//check form data
	if(name == "") {
		headerNotification(LANG.EVENT_CLIENT_EDIT_NAME_EMPTY,"red");
	} else {
		
		//send data to PHP
		$.post(INCLUDES+"/event_functions.php",{
			requestType: "saveClientData",
			clientID: clientID,
			name: name,
			description: description,
			external: external
		},
		function(data, status){
			var options = "";
			var dataCut = parsePostData(data);
			//check if request is valid
			if(dataCut == "error" || dataCut == "") {
				headerNotification(LANG.ERROR_REQUEST_FAILED,"red");
			} else if(dataCut == "missingRights") {
				headerNotification(LANG.USER_RIGHTS_MISSING,"red");
			} else if(dataCut == "success") {
				headerNotification(LANG.PHRASE_SAVED_SUCCESS,"green");
				loadClientList();
			}
		});
	}
}

//delete client

function deleteData(type, dataID) {
	//send data to PHP
	$.post(INCLUDES+"/event_functions.php",{
		requestType: "deleteData",
		type: type,
		dataID: dataID
	},
	function(data, status){
		var options = "";
		var dataCut = parsePostData(data);
		//check if request is valid
		if(dataCut == "error" || dataCut == "") {
			headerNotification(LANG.ERROR_REQUEST_FAILED,"red");
		} else if(dataCut == "missingRights") {
			headerNotification(LANG.USER_RIGHTS_MISSING,"red");
		} else if(dataCut == "inUse") {
			headerNotification(LANG.EVENT_DELETE_STILL_USED,"red");
		} else if(dataCut == "success") {
			headerNotification(LANG.PHRASE_REQUEST_SUCCSESSFUL,"green");
			if(type == "client") {
				loadClientList();
			} else if(type == "location") {
				loadLocationList();
			}
		} else {
			console.log(data);
		}
	});
}

//create a new template client

function createNew(type) {
	
	//send data to PHP
	$.post(INCLUDES+"/event_functions.php",{
		requestType: "createNew",
		type: type
	},
	function(data, status){
		var options = "";
		var dataCut = parsePostData(data);
		//check if request is valid
		if(dataCut == "error" || dataCut == "") {
			headerNotification(LANG.ERROR_REQUEST_FAILED,"red");
		} else if(dataCut == "missingRights") {
			headerNotification(LANG.USER_RIGHTS_MISSING,"red");
		} else if(dataCut == "success") {
			headerNotification(LANG.PHRASE_REQUEST_SUCCSESSFUL,"green");
			if(type == "client") {
				loadClientList();
			} else if(type == "location") {
				loadLocationList();
			}
		}
	});
}

//load list of all location

function loadLocationList() {
	
	var search = {};
	
	var searchInputs = document.querySelectorAll(".generalTableSearch .searchInput");
	
	for(i = 0;i < Object.keys(searchInputs).length;i++) {
		var searchKey = searchInputs[i].dataset.searchName;
		if(searchInputs[i].classList.contains("generalCheckbox")) {
			if(searchInputs[i].checked  == true) {
				search[searchKey] = "1";
			} else {
				search[searchKey] = "";
			}
		} else {
			search[searchKey] = searchInputs[i].value;
		}
	}
	

	//send request to php page
	$.post(INCLUDES+"/event_functions.php",{
		requestType: "loadLocationList",
		search: search
	},
	function(data, status){
		var locations = "";
		var dataCut = parsePostData(data);
		//check if request is valid
		if(dataCut == "error" || dataCut == "") {
			headerNotification(LANG.ERROR_REQUEST_FAILED,"red");
		} else if(dataCut == "missingRights") {
			headerNotification(LANG.USER_RIGHTS_MISSING,"red");
		} else if(dataCut != "") {
			data = JSON.parse(data);
			for(var i = 0; i < Object.keys(data).length; i++){
				var locationData = data[i];
				
				//set description
				var description = "";
				
				if(locationData['description'] != null) {
					description = locationData['description'];
				}
				
				//check description length
				
				if(description.length > 70 || description.split(/\r?\n/).length > 3) {
					var tmpDescription = description.slice(70);
					var previewdescription = description.slice(0,tmpDescription.indexOf(' ')+71);
					description = `
					<span class="descriptionPreview">`+previewdescription+`</span>
					<span class="descriptionFull none">`+description+`</span>
					<span class="readMoreButton descriptionButton" onclick="toggleDescriptionContainer(`+locationData['ID']+`)"><br>`+LANG.EVENT_CLIENT_LIST_DESCRIPTION_READ_MORE+`</span>
					<span class="readLessButton none descriptionButton" onclick="toggleDescriptionContainer(`+locationData['ID']+`)"><br>`+LANG.EVENT_CLIENT_LIST_DESCRIPTION_READ_LESS+`</span>
					`;
				} else {
					description = `<span class="descriptionFull">`+description+`</span>`;
				}
				
				
				
				description.replace(/\\n/g, "<br />");
				
				locations += `
				<div class="row generalTableContentRow" data-id="`+locationData['ID']+`">
					<div class="td col-12 col-md-5 data" data-field-name="name">`+locationData['name']+`</div>
					<div class="td col-12 col-md-5 data" data-field-name="description">
						`+description+`
					</div>
					<div class="td col-4 col-md-2 actionButtons noselect">
						<span class="icon generalIcon" onclick="editLocationData('`+locationData['ID']+`')">
							<i class="fa fa-pencil-square" aria-hidden="true"></i>
						</span>
						<span class="icon generalIcon" onclick="deleteData('location','`+locationData['ID']+`')">
							<i class="fa fa-trash" aria-hidden="true"></i>
						</span>
					</div>
				</div>
				<div class="row">
					<div class="col-12"><hr></div>
				</div>
				`;
				
			}
			
			
		}
		
		
		
		//Put in message if no data was found
		if(locations == "") {
			locations = '<div class="col-12 td">'+LANG.EVENTS_LOCATION_LIST_NON_FOUND+'</td>';
		}
		
		document.getElementById("locationList").innerHTML = locations;
	});
}

//toggle form to edit location data

function editLocationData(locationID) {
	var locationContainer = document.querySelector(".page.event.locations .generalTableContentRow[data-id='"+locationID+"']");
	
	//check if form is already display
	
	//if yes: remove form and display data
	//if no: display form
	
	if(locationContainer.querySelector(".clientEditForm")) {
		
		var clientData = locationContainer.querySelector(".clientData").innerHTML;
		
		locationContainer.innerHTML = clientData;
		
	} else {
		
		var clientData = locationContainer.innerHTML;
		
		var name = locationContainer.querySelector(".data[data-field-name='name']").innerHTML;
		var description = locationContainer.querySelector(".data[data-field-name='description'] .descriptionFull").innerHTML;
		
		locationContainer.innerHTML = `
		<div class="clientData none">`+clientData+`</div>
		<div class="clientEditForm col-12">
			<div class="row">
				<div class="td col-12 col-md-5">
					<input type="text" data-input-name="name" value="`+name+`" class="dataInput generalInput">
				</div>
				<div class="td col-12 col-md-5">
					<textarea data-input-name="description" class="dataInput generalTextarea">`+description+`</textarea>
				</div>
				<div class="td col-4 col-md-2 actionButtons noselect">
					<span class="icon generalIcon" onclick="editLocationData('`+locationID+`')">
						<i class="fa fa-window-close" aria-hidden="true"></i>
					</span>
					<span class="icon generalIcon" onclick="saveLocationData('`+locationID+`')">
						<i class="fa fa-check-square" aria-hidden="true"></i>
					</span>
				</div>
			</div>
		</div>`;
	}
}


//save data for location change

function saveLocationData(locationID) {
	var locationContainer = document.querySelector(".page.event.locations .generalTableContentRow[data-id='"+locationID+"'] .clientEditForm");
	
	//get form data
	
	var name = locationContainer.querySelector(".dataInput[data-input-name='name']").value;
	var description = locationContainer.querySelector(".dataInput[data-input-name='description']").value;

	//check form data
	if(name == "") {
		headerNotification(LANG.EVENT_CLIENT_EDIT_NAME_EMPTY,"red");
	} else {
		
		//send data to PHP
		$.post(INCLUDES+"/event_functions.php",{
			requestType: "saveLocationData",
			locationID: locationID,
			name: name,
			description: description
		},
		function(data, status){
			var options = "";
			var dataCut = parsePostData(data);
			//check if request is valid
			if(dataCut == "error" || dataCut == "") {
				headerNotification(LANG.ERROR_REQUEST_FAILED,"red");
			} else if(dataCut == "missingRights") {
				headerNotification(LANG.USER_RIGHTS_MISSING,"red");
			} else if(dataCut == "success") {
				headerNotification(LANG.PHRASE_SAVED_SUCCESS,"green");
				loadLocationList();
			}
		});
	}
}

//create new template event

function createNewEvent() {
	
	//send data to PHP
	$.post(INCLUDES+"/event_functions.php",{
		requestType: "createNewEvent"
	},
	function(data, status){
		var options = "";
		var dataCut = parsePostData(data);
		//check if request is valid
		if(dataCut == "error" || dataCut == "") {
			headerNotification(LANG.ERROR_REQUEST_FAILED,"red");
		} else if(dataCut == "missingRights") {
			headerNotification(LANG.USER_RIGHTS_MISSING,"red");
		} else if(dataCut == "success") {
			location.reload();
		}
	});

}

//open page to edit event data

function openEventPage(eventID) {
	redirect(URL+"/events/edit/"+eventID);
}

//display delete form and delete if selected

function deleteEvent(eventID, request) {
	var buttonContainer = document.querySelector(".page.event.editEvent .buttonConatiner");
	
	var saveBox = buttonContainer.querySelector(".saveButtonBox");
	var deleteBox = buttonContainer.querySelector(".deleteButtonBox");
	
	if(request == "openForm") {
		saveBox.classList.add("none");
		deleteBox.classList.remove("none");
	} else if(request == "abort") {
		saveBox.classList.remove("none");
		deleteBox.classList.add("none");
	} else if(request == "delete") {
		//send data to PHP
		$.post(INCLUDES+"/event_functions.php",{
			requestType: "deleteEvent",
			eventID: eventID
		},
		function(data, status){
			var options = "";
			var dataCut = parsePostData(data);
			//check if request is valid
			if(dataCut == "error" || dataCut == "") {
				headerNotification(LANG.ERROR_REQUEST_FAILED,"red");
			} else if(dataCut == "missingRights") {
				headerNotification(LANG.USER_RIGHTS_MISSING,"red");
			} else if(dataCut == "success") {
				redirect(URL+"/events");
			} else {
				console.log(data);
			}
		});
	}
}


//remove a tag from event

function deleteEventTag(tagID, eventID) {
	//send data to PHP
	$.post(INCLUDES+"/event_functions.php",{
		requestType: "deleteTag",
		tagID: tagID,
		eventID: eventID
	},
	function(data, status){
		var options = "";
		var dataCut = parsePostData(data);
		//check if request is valid
		if(dataCut == "error" || dataCut == "") {
			headerNotification(LANG.ERROR_REQUEST_FAILED,"red");
		} else if(dataCut == "missingRights") {
			headerNotification(LANG.USER_RIGHTS_MISSING,"red");
		} else if(dataCut == "success") {
			location.reload();
		}
	});
}

//display form to add a tag

function addEventTagForm() {
	document.querySelector(".page.event.editEvent .addTagContainer").classList.toggle("none");
}

//add tag to event 

function addEventTag(eventID) {
	var tagID = document.querySelector(".page.event.editEvent .addTagContainer .addTagSelect").value;
	//send data to PHP
	$.post(INCLUDES+"/event_functions.php",{
		requestType: "addTag",
		tagID: tagID,
		eventID: eventID
	},
	function(data, status){
		var options = "";
		var dataCut = parsePostData(data);
		//check if request is valid
		if(dataCut == "error" || dataCut == "") {
			headerNotification(LANG.ERROR_REQUEST_FAILED,"red");
		} else if(dataCut == "missingRights") {
			headerNotification(LANG.USER_RIGHTS_MISSING,"red");
		} else if(dataCut == "success") {
			location.reload();
		} else if(dataCut == "alreadyUsed") {
			headerNotification(LANG.EVENTS_EDIT_TAGS_ALREADY_USED,"red");
		} else {
			console.log(data);
		}
	});
}

//display form to add a event responsible

function addResposibleForm() {
	document.querySelector(".page.event.editEvent .addResposibleContainer").classList.toggle("none");
}


//add tag to event 

function addEventResponsible(eventID) {
	var userID = document.querySelector(".page.event.editEvent .addResposibleContainer .addResponsibleSelect").value;
	//send data to PHP
	$.post(INCLUDES+"/event_functions.php",{
		requestType: "addResponsible",
		userID: userID,
		eventID: eventID
	},
	function(data, status){
		var options = "";
		var dataCut = parsePostData(data);
		//check if request is valid
		if(dataCut == "error" || dataCut == "") {
			headerNotification(LANG.ERROR_REQUEST_FAILED,"red");
		} else if(dataCut == "missingRights") {
			headerNotification(LANG.USER_RIGHTS_MISSING,"red");
		} else if(dataCut == "success") {
			location.reload();
		} else if(dataCut == "alreadyUsed") {
			headerNotification(LANG.EVENTS_EDIT_USER_ALREADY_ADDED,"red");
		} else {
			console.log(data);
		}
	});
}

//remove a responsible user from event

function deleteEventResponsible(userID, eventID) {
	//send data to PHP
	$.post(INCLUDES+"/event_functions.php",{
		requestType: "deleteResponsible",
		userID: userID,
		eventID: eventID
	},
	function(data, status){
		var options = "";
		var dataCut = parsePostData(data);
		//check if request is valid
		if(dataCut == "error" || dataCut == "") {
			headerNotification(LANG.ERROR_REQUEST_FAILED,"red");
		} else if(dataCut == "missingRights") {
			headerNotification(LANG.USER_RIGHTS_MISSING,"red");
		} else if(dataCut == "success") {
			location.reload();
		} else {
			console.log(data);
		}
	});
}

//save edited event data

function saveEventData(eventID) {
	
	var container = document.querySelector(".page.event.editEvent .editEventContainer");
	
	var name = container.querySelector(".dataInput[data-input-name='name']").value;
	var locationID = container.querySelector(".dataInput[data-input-name='location']").value;
	var clientID = container.querySelector(".dataInput[data-input-name='client']").value;
	var startDate = container.querySelector(".dataInput[data-input-name='startDate']").value;
	var endDate = container.querySelector(".dataInput[data-input-name='endDate']").value;
	var startTime = container.querySelector(".dataInput[data-input-name='startTime']").value;
	var endTime = container.querySelector(".dataInput[data-input-name='endTime']").value;
	var description = container.querySelector(".dataInput[data-input-name='description']").value;
	

	if(startDate == "" | startTime == "" | name == "") {
		headerNotification(LANG.EVENTS_EDIT_MANDATORY_EMPTY,"red");
	} else {
		//send data to PHP
		$.post(INCLUDES+"/event_functions.php",{
			requestType: "saveEventData",
			eventID: eventID,
			name: name,
			locationID: locationID,
			clientID: clientID,
			startDate: startDate,
			endDate: endDate,
			startTime: startTime,
			endTime: endTime,
			description: description
		},
		function(data, status){
			var options = "";
			var dataCut = parsePostData(data);
			//check if request is valid
			if(dataCut == "error" || dataCut == "") {
				headerNotification(LANG.ERROR_REQUEST_FAILED,"red");
			} else if(dataCut == "missingRights") {
				headerNotification(LANG.USER_RIGHTS_MISSING,"red");
			} else if(dataCut == "success") {
				headerNotification(LANG.PHRASE_SAVED_SUCCESS,"green");
			} else {
				console.log(data);
			}
		});
	}
	
}

//toggle comment notifications for event

//toggle notifications for todo list

function toggleEventNotifications(type, attributeID, currentState) {
	
	var overlay = document.querySelector(".page.event.editEvent");
	
	var bellContainer = overlay.querySelector(".bellContainer");
				
	var notificationsBell = "";
	
	notificationsBell += `<span class="onclick generalIcon" onclick="toggleEventNotifications('event','`+attributeID+`',`+!currentState+`)">`;
	
	if(currentState == false) {
		notificationsBell += `<i class="fa fa-bell" aria-hidden="true"></i>`;
	} else {
		notificationsBell += `<i class="fa fa-bell-slash" aria-hidden="true"></i>`;
	}
	
	notificationsBell += `</span>`;
	
	toggleCommentNotifications(type, attributeID, !currentState);
	
	bellContainer.innerHTML = notificationsBell;
}

