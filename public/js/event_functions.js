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
	
	console.log(search);
	
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
				
				if(description.length > 100) {
					var tmpDescription = description.slice(100);
					var previewdescription = description.slice(0,tmpDescription.indexOf(' ')+101);
					description = `
					<span class="descriptionPreview">`+previewdescription+`</span>
					<span class="descriptionFull none">`+description+`</span>
					<span class="readMoreButton descriptionButton" onclick="toggleClientDescriptionContainer(`+client['ID']+`)">`+LANG.EVENT_CLIENT_LIST_DESCRIPTION_READ_MORE+`</span>
					<span class="readLessButton none descriptionButton" onclick="toggleClientDescriptionContainer(`+client['ID']+`)">`+LANG.EVENT_CLIENT_LIST_DESCRIPTION_READ_LESS+`</span>
					`;
				} else {
					description = `<span class="descriptionFull">`+description+`</span>`;
				}
				
				description.replace(/\\n/g, "<br />");
				
				clients += `
				<div class="row generalTableContentRow" data-client-id="`+client['ID']+`">
					<div class="td col-8 col-md-4 data" data-field-name="name">`+client['name']+`</div>
					<div class="td col-4 col-md-2 ">
						`+external+`
						<div class="none data" data-field-name="external">
							`+client['external']+`
						</div>
					</div>
					<div class="td col-8 col-md-4 data" data-field-name="description">
						`+description+`
					</div>
					<div class="td col-4 col-md-2 actionButtons noselect">
						<span class="icon generalIcon" onclick="editClient('`+client['ID']+`')">
							<i class="fa fa-pencil-square" aria-hidden="true"></i>
						</span>
						<span class="icon generalIcon" onclick="deleteClient('`+client['ID']+`')">
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
		
		
		
		//Put in message if no category was found
		if(clients == "") {
			clients = '<div class="col-12 td">'+LANG.EVENTS_CLIENT_LIST_NON_FOUND+'</td>';
		}
		
		document.getElementById("clientList").innerHTML = clients;
	});
}


//toggle if full description should be shown

function toggleClientDescriptionContainer(clientID) {	
	var clientContainer = document.querySelector(".page.event.clients .generalTableContentRow[data-client-id='"+clientID+"']");

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

//display form to edit client data

function editClient(clientID) {
	var clientContainer = document.querySelector(".page.event.clients .generalTableContentRow[data-client-id='"+clientID+"']");
	
	var clientData = clientContainer.innerHTML;
	
	var name = clientContainer.querySelector(".data[data-field-name='name']").innerHTML;
	var description = clientContainer.querySelector(".data[data-field-name='description'] .descriptionFull").innerHTML;
	
	clientContainer.innerHTML = `
	<div class="clientData none">`+clientData+`</div>
	<div class="clientEditForm col-12">
		<div class="row">
			<div class="td col-8 col-md-4">
				<input type="text" data-input-name="name" value="`+name+`" class="dataInput generalInput">
			</div>
			<div class="td col-4 col-md-2">
				dd
			</div>
			<div class="td col-8 col-md-4">
				<textarea data-input-name="name" class="dataInput generalTextarea">`+description+`</textarea>
			</div>
			<div class="td col-4 col-md-2 actionButtons noselect">
				<span class="icon generalIcon" onclick="editClient('1')">
					<i class="fa fa-window-close" aria-hidden="true"></i>
				</span>
				<span class="icon generalIcon" onclick="saveClientData('1')">
					<i class="fa fa-check-square" aria-hidden="true"></i>
				</span>
			</div>
		</div>
	</div>`;
}