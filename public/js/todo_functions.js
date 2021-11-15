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

//create new todo list

function createNewTodoList(type) {
	$.post(INCLUDES+"/todo_functions.php",{
		requestType: "createNewToDoList",
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
			location.reload();
		}
	});
	
}

//open overlay for todo list

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
				
				if(data['listType'] == "global") {
					overlay.querySelector(".comments").classList.remove("none");
				} else if(data['listType'] == "personal") {
					overlay.querySelector(".comments").classList.add("none");
				}
				
				//Basic Data
				overlay.querySelector(".todoListName").innerHTML = '<span class="nameContainer">'+data['name']+'</span>';
				
				if(data['rights'] == "edit") {
					overlay.querySelector(".todoListName").innerHTML += `
						<span class="editToDoListName pointer" onclick="editToDoListName('`+listID+`')"><i class="fa fa-pencil" aria-hidden="true"></i></span>
					`;
				}
				
				overlay.dataset.todoListId = listID;
				
				//Comments
				
				var commentContainer = overlay.querySelector(".commentsContainer");
				
				loadCommentSection("todoList",listID,"commentSection");
				
				//Tags
				
				loadListTags(listID,overlay,"view");
				
				//Entries
				
				loadListEntries(listID,overlay,"edit");
				
				//Edit Entries
				
				if(data['rights'] == "edit") {
					closeToDoListEdit(listID, 1);
				} else {
					overlay.querySelector(".editEntriesContainer").innerHTML = "";
				}
				
			}
		}
	});
}

function deleteToDoList() {
	var overlay = document.querySelector(".todo .overlayContainer");
	
	listID = overlay.dataset.todoListId;
	
	$.post(INCLUDES+"/todo_functions.php",{
		requestType: "deleteToDoList",
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
		} else if(dataCut == "success") {
			location.reload();
		} else {
			console.log(data);
		}
	});
	
}

function editToDoListName(listID, saveData = 0) {
	var overlay = document.querySelector(".todo .overlayContainer");
	if(saveData == 0) {
		var nameContainer = overlay.querySelector(".todoListName .nameContainer");
		var nameDiv = overlay.querySelector(".todoListName");
		var name = nameContainer.innerHTML;
		
		$.post(INCLUDES+"/todo_functions.php",{
			requestType: "getToDoListCategories",
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
				
				categories = "";
				
				for(i = 0; i < Object.keys(data).length;i++) {
					categories += `<option value="`+data[i]['categoryID']+`" `+data[i]['selected']+`> `+data[i]['name']+` </option>`;
				}
				
				nameDiv.innerHTML = `
					<input type="text" class="todoListNameInput generalInput" value="`+name+`">
					<select class="generalSelect todoListCategoryInput">
						<option value="0">`+LANG.TODO_LIST_OVERVIEW_PLACEHOLDE_CATEGORY+`</option>
						`+categories+`
					</select>
					<span class="editToDoListName pointer" onclick="editToDoListName('`+listID+`',1)"><i class="fa fa-check-square" aria-hidden="true"></i></span>
				`;
			}
		});
		
	} else if(saveData == 1) {
		var name = overlay.querySelector(".todoListName .todoListNameInput").value;
		var category = overlay.querySelector(".todoListName .todoListCategoryInput").value;
		if(name == "") {
			headerNotification(LANG.TODO_LIST_OVERVIEW_EDIT_LIST_NAME_ERROR,"red");
		} else {
			$.post(INCLUDES+"/todo_functions.php",{
				requestType: "editToDoListName",
				name: name,
				category: category,
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
				} else if(dataCut == "success") {
					location.reload();
				}
			});
		}
	}
}


//display todo list edit view

function openTodoListEdit(listID) {
	var overlay = document.querySelector(".todo .overlayContainer");
	
	loadListEntries(listID,overlay,"remove");
	
	var editEntriesButton = `
	<div class="row">
		<div class="col-6">
			<div class="generalButton" onclick="closeToDoListEdit('`+listID+`')"> `+LANG.WORD_ABORT+` </div>
		</div>
		<div class="col-6">
			<div class="generalButton" onclick="addEntryToListForm('`+listID+`')"> `+LANG.WORD_ADD+` </div>
		</div>
	</div>`;
	
	
	overlay.querySelector(".editEntriesContainer").innerHTML = editEntriesButton;
}

//close todo list edit mode

function closeToDoListEdit(listID, init = 0) {
	var overlay = document.querySelector(".todo .overlayContainer");
	var editEntriesButton = `<div class="generalButton" onclick="openTodoListEdit('`+listID+`')"> `+LANG.WORD_EDIT+` </div>`;
	
	overlay.querySelector(".editEntriesContainer").innerHTML = editEntriesButton;

	if(init == 0) {
		loadListEntries(listID,overlay,"edit");
	}
}

//Display add entry form

function addEntryToListForm(listID) {
	
	var overlay = document.querySelector(".todo .overlayContainer");
	
	//Load entries from PHP
	$.post(INCLUDES+"/todo_functions.php",{
		requestType: "loadToDoListEntries",
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
			
			var options = "";
			
			var entries = data['entries'];
			
			options = displayEntriesForSelect(entries)
	
			var editEntriesForm = `
			<div class="row newToDoListEntryForm">
				<div class="col-12">
					<select class="entryParent generalSelect">
						<option value="0" selected> `+LANG.TODO_LIST_OVERVIEW_PLACEHOLDE_ADD_ENTRY_PARENT+` </option>
						`+options+`
					</select>
				</div>
				<div class="col-12">
					<input type="text" placeholder="`+LANG.TODO_LIST_OVERVIEW_PLACEHOLDE_ADD_ENTRY_NAME+`" class="entryName generalInput">
				</div>
				<div class="col-6">
					<div class="generalButton" onclick="openTodoListEdit('`+listID+`')"> `+LANG.WORD_ABORT+` </div>
				</div>
				<div class="col-6">
					<div class="generalButton" onclick="saveToDoListEntry('`+listID+`')"> `+LANG.WORD_SAVE+` </div>
				</div>
			</div>
			`;
			
			overlay.querySelector(".editEntriesContainer").innerHTML = editEntriesForm;
			
		}
	});
}

//Save new entry to todo list

function saveToDoListEntry(listID) {
	var overlayForm = document.querySelector(".todo .overlayContainer .newToDoListEntryForm");
	var name = overlayForm.querySelector(".entryName").value;
	var parentID = overlayForm.querySelector(".entryParent").value;
	
	if(name == "") {
		headerNotification(LANG.TODO_LIST_OVERVIEW_ADD_ENTRY_NAME_NEEDED,"red"); 
	} else {
		
		$.post(INCLUDES+"/todo_functions.php",{
			requestType: "saveNewListEntry",
			name: name,
			parentID: parentID,
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
			} else if(dataCut == "success") {
				closeToDoListEdit(listID);
			}
		});
	}
}


//delete element from todo list

function removeToDoListEntry(entryID) {
	var overlay = document.querySelector(".todo .overlayContainer");
	
	listID = overlay.dataset.todoListId;
	
	
	$.post(INCLUDES+"/todo_functions.php",{
		requestType: "removeListEntry",
		entryID: entryID,
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
		} else if(dataCut == "hasChildren") {
			headerNotification(LANG.TODO_LIST_OVERVIEW_REMOVE_ENTRY_ERROR_CHILDREN,"red");
		} else if(dataCut == "success") {
			closeToDoListEdit(listID);
		}
	});
}

//display entries in format for the select

function displayEntriesForSelect(entryList, level = 0, entryText = "") {
	var tmpText = "";
	var keyLength = Object.keys(entryList).length;
	if(keyLength > 0) {
		for(var x = 0; x < keyLength; x++) {
			
			tmpText += `<option value="`+entryList[x]['currentID']+`">`+"--".repeat(level)+` `+entryList[x]['name']+`</option>`;
			
			//Load child entries
			
			if(typeof entryList[x]['children'] !== "undefined") {
				if(Object.keys(entryList[x]['children']).length > 0) {
					var children = displayEntriesForSelect(entryList[x]['children'],level + 1);
					tmpText += children;
				}
			}
		}
	}
	return(entryText + tmpText);
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
		$.post(INCLUDES+"/todo_functions.php",{
			requestType: "addToDoListTag",
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
			} else if(dataCut == "alreadyAdded") {
				headerNotification(LANG.TODO_LIST_OVERVIEW_TAG_ALREADY_ADDED,"red");
			} else if(dataCut == "success") {
				headerNotification(LANG.TODO_LIST_OVERVIEW_TAG_ADDED_SUCCESS,"green");
			}
		});
		loadListTags(listID,overlay,"view");
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

//load all entries of todo list

function loadListEntries(listID, overlay, displayType = "view") {
	//Load entries from PHP
	$.post(INCLUDES+"/todo_functions.php",{
		requestType: "loadToDoListEntries",
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
			if((displayType == "edit" || displayType == "remove") && rights == "view") {
				displayType = "view";
			}

			//Display entries
			
			var entries =  "";
			
			entries = displayEntries(data['entries'],entries,displayType);
			
			overlay.querySelector(".entriesContainer").innerHTML = entries;
	
			//detect if checkbox changes
			$(".page.todo.overviewGlobal .overlayContainer .todoListOverlay .todoListCheckbox, .page.todo.overviewPersonal .overlayContainer .todoListOverlay .todoListCheckbox").change(function() {
				var entryID = this.dataset.entryId;
				var checked = +this.checked;
				todoListEntryStatusChange(entryID,checked,this);
			});
		}
	});
}


//Recoursively display entries

function displayEntries(entryList, entryText = "", displayType) {
	var tmpText = "";
	var keyLength = Object.keys(entryList).length;
	if(keyLength > 0) {
		tmpText += "<ul>";
		for(var x = 0; x < keyLength; x++) {
			tmpText += "<li>";
			
			//check if checked
			
			var checked = "";
			if(entryList[x]['checked'] == "1") {
				checked = "checked";
			}
			
			//Add entry text
			
			tmpText += '<span class="todoListEntryNameBox '+checked+'">';
			
			if(displayType == "edit") {
				tmpText += '<input type="checkbox" class="todoListCheckbox" data-entry-id="'+entryList[x]['currentID']+'" '+checked+'>';
			} else if(displayType != "remove") {
				tmpText += '<input disabled type="checkbox" class="todoListCheckbox" data-entry-id="'+entryList[x]['currentID']+'" '+checked+'>';
			}
			
			if(displayType == "remove") {
				tmpText += `<span class="removeToDoListEntryButton" onclick="removeToDoListEntry('`+entryList[x]['currentID']+`')"><i class="fa fa-trash" aria-hidden="true"></i></span>`;
			}
			
			tmpText += '&nbsp&nbsp'+entryList[x]['name']+'</span>';

			//Load child entries
			
			if(typeof entryList[x]['children'] !== "undefined") {
				if(Object.keys(entryList[x]['children']).length > 0) {
					var children = displayEntries(entryList[x]['children'],"",displayType);
					tmpText += children;
				}
			}
			
			tmpText += "</li>";
		}
		tmpText += "</ul>";
	}
	return(entryText + tmpText);
}

//list entry check or uncheck

function todoListEntryStatusChange(entryID, checked, object) {
	$.post(INCLUDES+"/todo_functions.php",{
		requestType: "changeEntryStatus",
		entryID: entryID,
		checked: checked
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
			var nameBox = object.parentNode;
			if(checked == 1) {
				nameBox.classList.add("checked");
			} else {
				nameBox.classList.remove("checked");
			}
		}
	});
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

