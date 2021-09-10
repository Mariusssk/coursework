//load all storage entrys

function loadItems(displayType = "item") {
	
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
	
	if(displayType == "consumable") {
		search['consumable'] = 1;
	}
	
	//send request to php page
	$.post(INCLUDES+"/item_functions.php",{
		requestType: "loadItems",
		search: search
	},
	function(data, status){
		var items = "";
		var dataCut = parsePostData(data);
		//check if request is valid
		if(dataCut == "error" || dataCut == "") {
			headerNotification(LANG.ERROR_REQUEST_FAILED,"red");
		} else if(dataCut == "missingRights") {
			headerNotification(LANG.USER_RIGHTS_MISSING,"red");
		} else if(dataCut != "") {
			data = JSON.parse(data);

			//Check how items should be displayed

			if(displayType == "consumable") {
				items = displayConsumeable(data);
			} else {
				items = displayItems(data);
			}
			
		}
		
		//Put in message if no storage was found
		if(items == "") {
			items = '<div class="col-12 td">'+LANG.ITEM_OVERVIEW_NON_FOUND+'</td>';
		}
		
		document.getElementById("itemList").innerHTML = items;
	});
}


function displayItems(data) {
	var items = "";
	
	//run through every item in array
	for(i = 0; i < Object.keys(data).length;i++) {
		if(data[i]['consumeable'] == "1") {
			var consumeable = '<i class="fa fa-check" aria-hidden="true"></i>';
		} else {
			var consumeable = "";
		}
		items += `
		<div class="row generalTableContentRow" onclick="viewItem('`+data[i]['ID']+`')">
			<div class="td col-6 col-sm-4">`+data[i]['name']+`</div>
			<div class="td d-none d-sm-block col-sm-4">`+data[i]['typeName']+`</div>
			<div class="td col-3 col-sm-2">`+consumeable+`</div>
			<div class="td col-3 col-sm-2">`+data[i]['amount']+`</div>
		</div>
		<div class="row"><div class="col-12 hr"><hr></div></div>
		`;
	}
	
	//return the display data
	return(items);
}

function displayConsumeable(data) {
	var items = "";
	
	//run through every item in array
	for(i = 0; i < Object.keys(data).length;i++) {
		items += `
		<div class="row generalTableContentRow" onclick="viewItem('`+data[i]['ID']+`')">
			<div class="td col-6 col-sm-4">`+data[i]['name']+`</div>
			<div class="td d-none d-sm-block col-sm-4">`+data[i]['typeName']+`</div>
			<div class="td col-2 col-sm-1"><i class="fa fa-minus" aria-hidden="true"></i></div>
			<div class="td col-2 col-sm-2">`+data[i]['amount']+`</div>
			<div class="td col-2 col-sm-1"><i class="fa fa-plus" aria-hidden="true"></i></div>
		</div>
		<div class="row"><div class="col-12 hr"><hr></div></div>
		`;
	}
	
	//return the display data
	return(items);
}

//redirect to page of item

function viewItem(ID) {
	redirect(URL+"/item/edit/"+ID);
}

//Functions when page loaded

$(document).ready(function() {
	//detect enter on search
	$(".page.item.overview .searchInput").on('keyup', function (e) {
		if (e.key === 'Enter' || e.keyCode === 13) {
			loadItems();
		}
	});
});