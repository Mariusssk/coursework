//load all categories 

function loadTodoCategories() {
	
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
	$.post(INCLUDES+"/todo_functions.php",{
		requestType: "loadTodoCategories",
		search: search
	},
	function(data, status){
		var categories = "";
		var dataCut = parsePostData(data);
		//check if request is valid
		if(dataCut == "error" || dataCut == "") {
			headerNotification(LANG.ERROR_REQUEST_FAILED,"red");
		} else if(dataCut == "missingRights") {
			headerNotification(LANG.USER_RIGHTS_MISSING,"red");
		} else if(dataCut != "") {
			data = JSON.parse(data);

			categories = displayToDoCategories(data);
			
		}
		
		//Put in message if no category was found
		if(categories == "") {
			categories = '<div class="col-12 td">'+LANG.TODO_CATEGORY_OVERVIEW_NOT_FOUND+'</td>';
		}
		
		document.getElementById("categroyList").innerHTML = categories;
	});
}


function displayToDoCategories(data) {
	var items = "";
	
	//run through every item in array
	for(i = 0; i < Object.keys(data).length;i++) {
		items += `
		<div class="row generalTableContentRow" onclick="editCategroy('`+data[i]['ID']+`')" data-item-id="`+data[i]['ID']+`">
			<div class="td col-8">`+data[i]['name']+`</div>
			<div class="td col-4">`+data[i]['type']+`</div>
		</div>
		<div class="row"><div class="col-12 hr"><hr></div></div>
		`;
	}
	
	//return the display data
	return(items);
}

//redirect to page of category

function editCategroy(ID) {
	redirect(URL+"/todo/category/edit/"+ID);
}

//redirect to new page

function createNewCategory(ID) {
	redirect(URL+"/todo/category/new");
}

//open overlay for todo listStyleType

function openToDoList(listID) {
	//request list data from php
	$.post(INCLUDES+"/todo_functions.php",{
		requestType: "getListData",
		listID: listID
	},
	function(data, status){
		var options = "";
		var dataCut = parsePostData(data);
		//check if request is valid
		if(dataCut == "error" || dataCut == "") {
			headerNotification(LANG.ERROR_REQUEST_FAILED,"red");
		} else if(dataCut == "missingRights") {
			headerNotification(LANG.USER_RIGHTS_MISSING,"red");
		} else {
			data = JSON.parse(data);
			
			var overlay = document.querySelector(".todo .overlayContainer");
			if(typeof(overlay) !== undefined) {
				overlay.classList.remove("none");
				
				//Basic Data
				overlay.querySelector(".todoListName").innerHTML = data['name'];
				
				overlay.dataset.todoListId = listID;
				
				//Tags
				
				loadListTags(listID,overlay,"view");
				
				//Entries
				
				if(Object.keys(data['entries']).length > 0) {
					var entries = "";
					for(i = 0; i < Object.keys(data['entries']).length; i++) {
						entries += displayToDoListEntries(data['entries'][i]);
					}
					overlay.querySelector(".todoListEntries").innerHTML = entries;
				}
			}
		}
	});
}

//Display the todo list entries
function displayToDoListEntries(entry) {
	return(entry['name'])
}

//load all tags

function loadListTags(listID, overlay, displayType = "view") {
	//Load tags from PHP
	$.post(INCLUDES+"/todo_functions.php",{
		requestType: "loadToDoListTags",
		listID: listID
	},
	function(data, status){
		var options = "";
		var dataCut = parsePostData(data);
		//check if request is valid
		if(dataCut == "error" || dataCut == "") {
			headerNotification(LANG.ERROR_REQUEST_FAILED,"red");
		} else if(dataCut == "missingRights") {
			headerNotification(LANG.USER_RIGHTS_MISSING,"red");
		} else {
			data = JSON.parse(data);
			var rights = data['rights'];
			if(displayType == "edit" && rights == "view") {
				displayType = "view";
			}
	
			//Display Tags
			
			var tags =  "";
			if(Object.keys(data['tags']).length > 0) {
				for(i = 0; i < Object.keys(data['tags']).length; i++) {
					var backgroundRGB = hexToRgb(data['tags'][i]['colour']);
					if((backgroundRGB['r']*0.299)+(backgroundRGB['g']*0.587)+(backgroundRGB['b']*0.114)>186) {
						var textColour = "black";
					} else {
						var textColour = "white";
					}
					if(displayType == "edit") {
						tags += `<span data-tag-id="`+data['tags'][i]['ID']+`" class="tag noselect" style="background-color: `+data['tags'][i]['colour']+`;color: `+textColour+`;">`+data['tags'][i]['name']+` <span onclick="deleteTag('`+data['tags'][i]['ID']+`')" class="editTags"><i class="fa fa-times-circle" aria-hidden="true"></i></span></span>`;
					} else {
						tags += `<span data-tag-id="`+data['tags'][i]['ID']+`" class="tag noselect" style="background-color: `+data['tags'][i]['colour']+`;color: `+textColour+`;">`+data['tags'][i]['name']+`</span>`;
					}
				}
			} else {
				tags = LANG.TODO_LIST_OVERVIEW_NO_TAGS_FOUND;
			}
			if(displayType == "view" && rights == "edit") {
				tags += `<span class="editTags" onclick="editTags('`+listID+`')"><i class="fa fa-pencil" aria-hidden="true"></i></span>`;
			} else if(displayType == "edit") {
				tags += `<span class="editTags" onclick="addTagForm('`+listID+`')"><i class="fa fa-plus-square" aria-hidden="true"></i></span>`;
			}
			overlay.querySelector(".labelContainer").innerHTML = tags;
		}
	});
}

//Edit todo list tags

function editTags(listID) {
	var overlay = document.querySelector(".todo .overlayContainer");
	loadListTags(listID,overlay,"edit");
}

//add new tag form

function addTagForm() {
	var overlay = document.querySelector(".todo .overlayContainer");
	
	$.post(INCLUDES+"/post_functions.php",{
		requestType: "loadTags"
	},
	function(data, status){
		var options = "";
		var dataCut = parsePostData(data);
		//check if request is valid
		if(dataCut == "error" || dataCut == "") {
			headerNotification(LANG.ERROR_REQUEST_FAILED,"red");
		} else if(dataCut == "missingRights") {
			headerNotification(LANG.USER_RIGHTS_MISSING,"red");
		} else {
			data = JSON.parse(data);
			
			//Add select to div
			
			data = overlay.querySelector(".labelContainer").innerHTML + `
			<div class="addTagFormContainer noselect">
				<div class="addTagSelect">`+data+`</div>
				<span class="editTags" onclick="addTag()"><i class="fa fa-check-square" aria-hidden="true"></i></span>
			</div>`;
			
			overlay.querySelector(".labelContainer").innerHTML = data;
		}
	});
}

//add tag from 

function addTag() {
	var overlay = document.querySelector(".todo .overlayContainer");
	
	var listID = overlay.dataset.todoListId;
	var tagID = document.querySelector(".addTagFormContainer .addTagSelect select").value;
	
	if(tagID == 0) {
		headerNotification(LANG.TODO_LIST_OVERVIEW_SELECT_TAG,"red");
	} else {
		
	}
}

//delete tag from list 
function deleteTag(tagID) {
	var overlay = document.querySelector(".todo .overlayContainer");
	var listID = overlay.dataset.todoListId;
	
	$.post(INCLUDES+"/todo_functions.php",{
		requestType: "removeToDoListTag",
		listID: listID,
		tagID: tagID
	},
	function(data, status){
		var dataCut = parsePostData(data);
		//check if request is valid
		if(dataCut == "error" || dataCut == "") {
			headerNotification(LANG.ERROR_REQUEST_FAILED,"red");
		} else if(dataCut == "missingRights") {
			headerNotification(LANG.USER_RIGHTS_MISSING,"red");
		} else if(dataCut == "success") {
			headerNotification(LANG.TODO_LIST_OVERVIEW_TAG_REMOVED_SUCCESS,"green");
		}
	});
	loadListTags(listID,overlay,"edit");
}

//delete category

function deleteCategory(categoryID,action = "check") {
	//check stage of deleting
	if(action == "check") {
		//show confirm delete button
		document.getElementById("checkDeleteContainer").classList.add("none");
		document.getElementById("confirmDeleteContainer").classList.remove("none");
	} else if(action == "confirm") {
		//send request to PHP
		$.post(INCLUDES+"/todo_functions.php",{
			requestType: "deleteCategory",
			categoryID: categoryID
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
				redirect(URL+"/todo/category");
			}
		});
	} else if(action == "abort") {
		//hide confirm button and show normal button
		document.getElementById("checkDeleteContainer").classList.remove("none");
		document.getElementById("confirmDeleteContainer").classList.add("none");
	}
}

//save data

function saveCategoryData(categoryID) {
	
	var objectData = {};
	
	//get all input containers
	var inputs = document.querySelectorAll(".page.todo.categoryEdit .inputBlock .dataInput , .page.todo.categoryNew .inputBlock .dataInput");
	
	//run through every input
	for(i = 0;i < Object.keys(inputs).length;i++) {
		var inputKey = inputs[i].dataset.inputName;
		if(inputs[i].classList.contains("generalCheckbox")) {
			if(inputs[i].checked  == true) {
				objectData[inputKey] = "1";
			} else {
				objectData[inputKey] = "";
			}
		} else {
			objectData[inputKey] = inputs[i].value;
		}
	}
	
	//check if mandatory fields are filled
	if(typeof objectData['name'] === "undefined" || objectData['name'] == "") {
		headerNotification(LANG.PHRASE_FILL_ALL,"red");
	} else {
		//send request to PHP
		$.post(INCLUDES+"/todo_functions.php",{
			requestType: "saveCategoryData",
			categoryID: categoryID,
			categoryData: objectData
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
					//redirect to edit page or overview if creation succesfull
					if(data['newID'] != "") {
						editCategroy(data['newID']);
					} else {
						redirect(URL+"/todo/category");
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

//Functions when page loaded

$(document).ready(function() {
	//detect enter on search
	$(".page.todo.categoryOverview .searchInput").on('keyup', function (e) {
		if (e.key === 'Enter' || e.keyCode === 13) {
			loadTodoCategories();
		}
	});
});

