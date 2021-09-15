//load all items 

function loadItems(displayType = "lend") {
	
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
	
	if(displayType == "lend") {
		search['lend'] = 1;
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
			}else if(displayType == "lend") {
				items = displayLend(data);
			} else {
				items = displayItems(data);
			}
			
		}
		
		//Put in message if no storage was found
		if(items == "") {
			items = '<div class="col-12 td">'+LANG.ITEM_OVERVIEW_NON_FOUND+'</td>';
		}
		
		document.getElementById("itemList").innerHTML = items;
		
		setConsumableColour();
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
		<div class="row generalTableContentRow" onclick="viewItem('`+data[i]['ID']+`')" data-item-id="`+data[i]['ID']+`">
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
		<div class="row generalTableContentRow" data-item-id="`+data[i]['ID']+`">
			<div class="td col-6 col-sm-4">`+data[i]['name']+`</div>
			<div class="td d-none d-sm-block col-sm-4">`+data[i]['typeName']+`</div>
			<div class="td col-2 col-sm-1 consumableAmountButton" onclick="changeItemAmount('`+data[i]['ID']+`','-1')"><i class="fa fa-minus" aria-hidden="true"></i></div>
			<div class="td col-2 col-sm-2 consumableAmount">`+data[i]['amount']+`</div>
			<div class="td col-2 col-sm-1 consumableAmountButton" onclick="changeItemAmount('`+data[i]['ID']+`','1')"><i class="fa fa-plus" aria-hidden="true"></i></div>
		</div>
		<div class="row"><div class="col-12 hr"><hr></div></div>
		`;
	}
	
	//return the display data
	return(items);
}

function displayLend(data) {
	var items = "";
	
	//run through every item in array
	for(i = 0; i < Object.keys(data).length;i++) {
		if(data[i]['consumeable'] == "1") {
			var consumeable = '<i class="fa fa-check" aria-hidden="true"></i>';
		} else {
			var consumeable = "";
		}
		items += `
		<div class="row generalTableContentRow" onclick="viewItem('`+data[i]['ID']+`')" data-item-id="`+data[i]['ID']+`">
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

//Change amount of item 

function changeItemAmount(itemID, amount = 0) {
	$.post(INCLUDES+"/item_functions.php",{
		requestType: "changeItemAmount",
		itemID: itemID,
		amount: amount
	},
	function(data, status){
		var dataCut = parsePostData(data);
		
		//check if request is valid
		if(dataCut == "error" || dataCut == "") {
			headerNotification(LANG.ERROR_REQUEST_FAILED,"red");
		} else if(dataCut == "missingRights") {
			headerNotification(LANG.USER_RIGHTS_MISSING,"red");
		} else if(dataCut != "") {
			var newAmount = dataCut;
			
			document.querySelector('[data-item-id="'+itemID+'"] .consumableAmount').innerHTML = newAmount;
		}
		
		setConsumableColour();
	});
	
}

//redirect to page of item

function viewItem(ID) {
	redirect(URL+"/item/edit/"+ID);
}

//set colour for consumable depending on amount

function setConsumableColour() {
	//load all consumable rows
	var amounts = document.querySelectorAll('.consumable [data-item-id].generalTableContentRow');
		
	for(i = 0;i < Object.keys(amounts).length;i++) {
		//find amount field object
		var amountField = amounts[i].querySelector(".consumableAmount");
		if(amountField) {
			//save amount to var
			var amount = amountField.innerHTML;
			//remove previous colours
			removeConsumableColours(amountField);
			
			//set new colour
			if(amount <= 0) {
				amountField.classList.add("consumableAmountVeryLow");
			} else if(amount > 0 && amount <= 2) {
				amountField.classList.add("consumableAmountLow");
			} else if(amount > 2 && amount <= 6) {
				amountField.classList.add("consumableAmountMedium");
			} else if(amount > 6) {
				amountField.classList.add("consumableAmountHigh");
			}
		}
	}
}

//removeAllColourTagsOnConsumable

function removeConsumableColours(amountField) {
	amountField.classList.remove("consumableAmountHigh");
	amountField.classList.remove("consumableAmountMedium");
	amountField.classList.remove("consumableAmountLow");
	amountField.classList.remove("consumableAmountVeryLow");
}


//delete storage

function deleteItem(itemID,action = "check") {
	//check stage of deleting
	if(action == "check") {
		//show confirm delete button
		document.getElementById("checkDeleteContainer").classList.add("none");
		document.getElementById("confirmDeleteContainer").classList.remove("none");
	} else if(action == "confirm") {
		//send request to PHP
		$.post(INCLUDES+"/item_functions.php",{
			requestType: "deleteItem",
			itemID: itemID
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
				redirect(URL+"/item");
			}
		});
	} else if(action == "abort") {
		//hide confirm button and show normal button
		document.getElementById("checkDeleteContainer").classList.remove("none");
		document.getElementById("confirmDeleteContainer").classList.add("none");
	}
}

//save data 

function saveItemData(itemID) {
	
	var itemData = {};
	
	//get all input containers
	var inputs = document.querySelectorAll(".page.item .inputBlock .dataInput");
	
	//run through every input
	for(i = 0;i < Object.keys(inputs).length;i++) {
		var inputKey = inputs[i].dataset.inputName;
		itemData[inputKey] = inputs[i].value;
	}
	
	//check if mandatory fields are filled
	if(typeof itemData['name'] === "undefined" || itemData['name'] == "" || itemData['name'] == 0 || typeof itemData['type'] === "undefined" || itemData['type'] == "" || itemData['type'] == 0) {
		headerNotification(LANG.PHRASE_FILL_ALL,"red");
	} else {
		//send request to PHP
		$.post(INCLUDES+"/item_functions.php",{
			requestType: "saveItemData",
			itemID: itemID,
			itemData: itemData
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
					//redirect to edit page or overview is new item was created
					if(data['newID'] != "") {
						viewItem(data['newID']);
					} else {
						redirect(URL+"/item");
					}
				} else if(data['result'] == "saved") {
					headerNotification(LANG.PHRASE_SAVED_SUCCESS);
				} else {
					headerNotification(LANG.ERROR_REQUEST_FAILED,"red");
					console.log(data);
				}
			}
		});
	}
	
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

