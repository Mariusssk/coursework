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

