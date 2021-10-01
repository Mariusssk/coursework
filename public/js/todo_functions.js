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

function viewItem(ID) {
	redirect(URL+"/item/edit/"+ID);
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

