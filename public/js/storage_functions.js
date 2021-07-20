
//load all storage entrys

function loadStorages() {
	
	var search = {};
	
	var searchInputs = document.querySelectorAll(".generalTableSearch .searchInput");
	
	for(i = 0;i < Object.keys(searchInputs).length;i++) {
		var searchKey = searchInputs[i].dataset.searchName;
		search[searchKey] = searchInputs[i].value;
	}
	
	//send request to php page
	$.post(INCLUDES+"/storage_functions.php",{
		requestType: "loadStorages",
		search: search
	},
	function(data, status){
		var storages = "";
		var dataCut = parsePostData(data);
		//check if request is valid
		if(dataCut == "error" || dataCut == "") {
			headerNotification(LANG.ERROR_REQUEST_FAILED,"red");
		} else if(dataCut == "missingRights") {
			headerNotification(LANG.USER_RIGHTS_MISSING,"red");
		} else if(dataCut != "") {
			data = JSON.parse(data);
			
			//run through every item in array
			for(i = 0; i < Object.keys(data).length;i++) {
				storages += `
				<div class="row generalTableContentRow" onclick="viewStorage('`+data[i]['ID']+`')">
					<div class="td col-6 col-sm-4">`+data[i]['name']+`</div>
					<div class="td d-none d-sm-block col-sm-4">`+data[i]['parentName']+`</div>
					<div class="td col-6 col-sm-4">`+data[i]['typeName']+`</div>
				</div>
				<div class="row"><div class="col-12 hr"><hr></div></div>
				`;
			}
		}
		
		//Put in message if no storage was found
		if(storages == "") {
			storages = '<div class="col-12 td">'+LANG.STORAGE_OVERVIEW_NON_FOUND+'</td>';
		}
		
		document.getElementById("storageList").innerHTML = storages;
	});
}

//load options for parent elment based on storage type

function loadParentOptions(typeID = 0,parentID = 0) {
	//check if room is selected -> parent not possible
	if(typeID == 1) {
		options = '<option selected disabled value="0">'+LANG.STORAGE_EDIT_PARENT_NOT_POSSIBLE+' </option>';
		document.getElementById("storageParentSelect").innerHTML = options;
	} 
	//check if placeholder is selected
	else if(typeID == 0) {
		options = '<option selected disabled value="0">'+LANG.STORAGE_EDIT_PARENT_NO_TYPE_SELECTED+' </option>';
		document.getElementById("storageParentSelect").innerHTML = options;
	} 
	
	//if correctly selected send request to php
	else {
		$.post(INCLUDES+"/storage_functions.php",{
			requestType: "loadParentOptions",
			typeID: typeID
		},
		function(data, status){
			var options = "";
			var dataCut = parsePostData(data);
			//check if request is valid
			if(dataCut == "error" || dataCut == "") {
				headerNotification(LANG.ERROR_REQUEST_FAILED,"red");
			} else if(dataCut == "missingRights") {
				headerNotification(LANG.USER_RIGHTS_MISSING,"red");
			} else if(dataCut != "") {
				data = JSON.parse(data);
				
				//run through every item in array
				for(i = 0; i < Object.keys(data).length;i++) {
					options += `<option value="`+data[i]['ID']+`"`;
					if(parentID == data[i]['ID']) {
						options += " selected ";
					}
					options += `>`+data[i]['name']+`</option>`;
				}
			}
			
			//Put in message if no storage was found
			if(options == "") {
				options = '<option selected disabled value="0"> </option>';
			}
			
			document.getElementById("storageParentSelect").innerHTML = options;
		});
	}
}

//redirect to page of storage

function viewStorage(ID) {
	redirect(URL+"/storage/edit/"+ID);
}

//save data 

function saveStorageData(storageID) {
	
	var storageData = {};
	
	//get all input containers
	var inputs = document.querySelectorAll(".page.storage .inputBlock .dataInput");
	
	//run through every input
	for(i = 0;i < Object.keys(inputs).length;i++) {
		var inputKey = inputs[i].dataset.inputName;
		storageData[inputKey] = inputs[i].value;
	}
	
	//check if mandatory fields are filled
	if(typeof storageData['name'] === "undefined" || storageData['name'] == "" || typeof storageData['type'] === "undefined" || storageData['type'] == "") {
		headerNotification(LANG.PHRASE_FILL_ALL,"red");
	} else {
		//send request to PHP
		$.post(INCLUDES+"/storage_functions.php",{
			requestType: "saveStorageData",
			storageID: storageID,
			storageData: storageData
		},
		function(data, status){
			var options = "";
			var dataCut = parsePostData(data);
			//check if request is valid
			if(dataCut == "error" || dataCut == "") {
				headerNotification(LANG.ERROR_REQUEST_FAILED,"red");
			} else if(dataCut == "missingRights") {
				headerNotification(LANG.USER_RIGHTS_MISSING,"red");
			} else if(dataCut != "") {
				data = JSON.parse(data);
				
				//check result array
				if(data['result'] == "created") {
					//redirect to edit page or overview is new storage was created
					if(data['newID'] != "") {
						viewStorage(data['newID']);
					} else {
						redirect(URL+"/storage");
					}
				} else if(data['result'] == "saved") {
					headerNotification(LANG.PHRASE_SAVED_SUCCESS);
				} else {
					headerNotification(LANG.ERROR_REQUEST_FAILED,"red");
				}
			}
		});
	}
	
}

//delete storage

function deleteStorage(storageID,action = "check") {
	//check stage of deleting
	if(action == "check") {
		//show confirm delete button
		document.getElementById("checkDeleteContainer").classList.add("none");
		document.getElementById("confirmDeleteContainer").classList.remove("none");
	} else if(action == "confirm") {
		//send request to PHP
		$.post(INCLUDES+"/storage_functions.php",{
			requestType: "deleteStorage",
			storageID: storageID
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
				redirect(URL+"/storage");
			}  else if(dataCut == "childsExisiting") {
				headerNotification(LANG.STORAGE_DELETE_CHILD_EXISTING,"red");
			}
		});
	} else if(action == "abort") {
		//hide confirm button and show normal button
		document.getElementById("checkDeleteContainer").classList.remove("none");
		document.getElementById("confirmDeleteContainer").classList.add("none");
	}
}

//set up grid

function setUpGrid() {
	//set up non defined boxes as drag and drop
	var openBoxes = document.getElementById('openBoxes');
	
	new Sortable(openBoxes, {
		group: "shared",
		animation: 150
	});
	
	// find all grid boxes
	var gridBoxes = document.querySelectorAll(".page.storage.edit .shelfAlignmentContainer .shelfGrid .gridBox");
	
	for(i = 0;i < Object.keys(gridBoxes).length;i++) {
		new Sortable(gridBoxes[i], {
			group: "shared",
			animation: 150
		});
	}
}

//save grid data 

function saveGrid() {
	//find all boxes
	var boxesInGrid = document.querySelectorAll(".page.storage.edit .shelfAlignmentContainer .shelfGrid .gridBox .option");
	var openBoxes = document.querySelectorAll(".page.storage.edit .shelfAlignmentContainer .openBoxes .option");
	
	var boxes = [];
	
	//run through all options in grid
	for(i = 0;i < Object.keys(boxesInGrid).length;i++) {
		var gridBox = boxesInGrid[i].parentElement;
		var tmpBox = {};
		tmpBox['x'] = gridBox.dataset.gridX;
		tmpBox['y'] = gridBox.dataset.gridY;
		tmpBox['ID'] = boxesInGrid[i].dataset.storageId;
		boxes.push(tmpBox);
	}
	
	//run through all open options
	for(i = 0;i < Object.keys(openBoxes).length;i++) {
		var tmpBox = {};
		tmpBox['x'] = 0;
		tmpBox['y'] = 0;
		tmpBox['ID'] = openBoxes[i].dataset.storageId;
		boxes.push(tmpBox);
	}
	
	//send request to PHP
	$.post(INCLUDES+"/storage_functions.php",{
		requestType: "saveGrid",
		boxes: boxes
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
			headerNotification(LANG.PHRASE_SAVED_SUCCESS);
		} else {
			headerNotification(data,"red");
		}
	});
	
}

//Functions when page loaded

$(document).ready(function() {
	//detect enter on search
	$(".page.storage.overview .generalInput").on('keyup', function (e) {
		if (e.key === 'Enter' || e.keyCode === 13) {
			loadStorages();
		}
	});
	
	//detect if user changed type of storage

	$('.page.storage .generalSelect.dataInput[data-input-name="type"]').on('change', function() {
		loadParentOptions(this.value);
	});
});