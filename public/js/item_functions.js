//load all items 

function loadItems(displayType = "") {
	
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
			} else if(displayType == "lend") {
				items = displayLend(data);
			} else if(displayType == "addLend") {
				items = displayAddLend(data);
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
			<div class="td col-3 col-sm-2">`+data[i]['actualAmount']+` (`+data[i]['amount']+`)</div>
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
			<div class="td col-2 col-sm-2 itemAmountField">`+data[i]['amount']+`</div>
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
		if(data[i]['returnDate'] == "") {
			data[i]['returnDate'] = '<i class="fa fa-plus-circle" aria-hidden="true"></i>'
		}
		items += `
		<div class="row generalTableContentRow" data-item-id="`+data[i]['ID']+`">
			<div class="td col-6 col-sm-4">`+data[i]['name']+`</div>
			<div class="td d-none d-sm-block col-sm-3">`+data[i]['typeName']+`</div>
			<div class="td col-2 col-sm-1 itemAmountField">`+data[i]['amount']+`</div>
			<div class="td col-2 col-sm-1 lendAmountButton" onclick="changeItemAmount('`+data[i]['ID']+`','1','lend')"><i class="fa fa-plus" aria-hidden="true"></i></div>
			<div class="td col-sm-2 col-6 returnDate"> <span onclick="changeReturnDate('`+data[i]['ID']+`')" class="returnDateText">`+data[i]['returnDate']+`</span> <input type="hidden" value="`+data[i]['returnDateForm']+`" class="returnDateFormData"></div>
			<div class="td col-6 col-sm-1 lendAmountButton" onclick="returnItemLend('`+data[i]['ID']+`')"><i class="fa fa-undo" aria-hidden="true"></i></div>
		</div>
		<div class="row"><div class="col-12 hr"><hr></div></div>
		`;
	}
	
	//return the display data
	return(items);
}

function displayAddLend(data) {
	var items = "";
	
	//run through every item in array
	for(i = 0; i < Object.keys(data).length;i++) {
		items += `
		<div class="row generalTableContentRow" data-item-id="`+data[i]['ID']+`">
			<div class="td col-6 col-sm-4">`+data[i]['name']+`</div>
			<div class="td d-none d-sm-block col-sm-4">`+data[i]['typeName']+`</div>
			<div class="td col-3 col-sm-2">`+data[i]['amount']+`</div>
			<div class="td col-3 col-sm-2"><div class="addLendButton" onclick="lendNewItem('`+data[i]['ID']+`')"> Add </div></div>
		</div>
		<div class="row"><div class="col-12 hr"><hr></div></div>
		`;
	}
	
	//return the display data
	return(items);
}

//Change amount of item 

function changeItemAmount(itemID, amount = 0, attribute) {
	//Send attributes to php script for changing db values
	$.post(INCLUDES+"/item_functions.php",{
		requestType: "changeItemAmount",
		itemID: itemID,
		amount: amount,
		attribute: attribute
	},
	function(data, status){
		var dataCut = parsePostData(data);
		
		//check if request is valid
		if(dataCut == "error" || dataCut == "") {
			headerNotification(LANG.ERROR_REQUEST_FAILED,"red");
		} else if(dataCut == "missingRights") {
			headerNotification(LANG.USER_RIGHTS_MISSING,"red");
		} else if(dataCut != "") {
			//Get return from PHP and display new amount
			var newAmount = dataCut;
			
			document.querySelector('[data-item-id="'+itemID+'"] .itemAmountField').innerHTML = newAmount;
		}
		
		setConsumableColour();
	});
	
}

//return item which was lended

function returnItemLend(itemID) {
	//send request to php to return item which was lend
	$.post(INCLUDES+"/item_functions.php",{
		requestType: "returnItemLend",
		itemID: itemID
	},
	function(data, status){
		var dataCut = parsePostData(data);
		
		//check if request is valid
		if(dataCut == "error" || dataCut == "") {
			headerNotification(LANG.ERROR_REQUEST_FAILED,"red");
		} else if(dataCut == "missingRights") {
			headerNotification(LANG.USER_RIGHTS_MISSING,"red");
		} else if(dataCut == "success") {
			headerNotification(LANG.ITEM_LEND_RETURN_SUCCESS,"green");
			
			loadItems('lend');
		}
	});
}

//change return date

function changeReturnDate(itemID) {
	//find html elemnts on page
	var returnDateField = document.querySelector(".page.item.lended #itemList .generalTableContentRow[data-item-id='"+itemID+"'] .returnDate");
	var returnDateForm = document.querySelector(".page.item.lended #itemList .generalTableContentRow[data-item-id='"+itemID+"'] .returnDate .returnDateForm");
	
	//check if form is existing
	if(returnDateForm  === null) {
		//Add form if not existing
		var returnDateFormData = returnDateField.querySelector(".returnDateFormData");
		if(returnDateFormData  !== null) {
			var returnDate = returnDateFormData.value;
		} else {
			var returnDate = "";
		}
		
		returnDateField.innerHTML = '<input type="date" class="returnDateForm generalSelect" value="'+returnDate+'"> <span  onclick="changeReturnDate('+"'"+itemID+"'"+')"><i class="fa fa-check-square" aria-hidden="true"></i></span>';
	
	} else {
		//Otherwiese get input value and send to php script
		var returnDateValue = returnDateForm.value;
		
		$.post(INCLUDES+"/item_functions.php",{
			requestType: "saveReturnDate",
			itemID: itemID,
			returnDate: returnDateValue
		},
		function(data, status){
			var dataCut = parsePostData(data);
			//process request result
			if(dataCut == "error" || dataCut == "") {
				headerNotification(LANG.ERROR_REQUEST_FAILED,"red");
			} else if(dataCut == "missingRights") {
				headerNotification(LANG.USER_RIGHTS_MISSING,"red");
			} else if(dataCut == "empty") {
				//Display new return date
				returnDateField.innerHTML = '<span onclick="changeReturnDate('+"'"+itemID+"'"+')"><i class="fa fa-plus-circle" aria-hidden="true"></i></span>';
			} else {
				//Display new return date
				returnDateField.innerHTML = `<span onclick="changeReturnDate('`+itemID+`')" class="returnDateText">`+data+`</span> <input type="hidden" value="`+returnDateValue+`" class="returnDateFormData">`;
			}
		});
	}
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
		var amountField = amounts[i].querySelector(".itemAmountField");
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

//open lend item form

function lendNewItem(itemID) {
	var newLendContainer = document.querySelector(".page.item.addLend .lendNewItemConatiner");
	
	//getItemName
	$.post(INCLUDES+"/item_functions.php",{
		requestType: "loadItemName",
		itemID: itemID
	},
	function(data, status){
		var dataCut = parsePostData(data);
		//process request result
		if(dataCut == "error" || dataCut == "") {
			headerNotification(LANG.ERROR_REQUEST_FAILED,"red");
		} else if(dataCut == "missingRights") {
			headerNotification(LANG.USER_RIGHTS_MISSING,"red");
		} else {
			//show form and add name if request was successful
			newLendContainer.classList.remove("none");
			newLendContainer.querySelector(".itemName").innerHTML = data;
			document.getElementById("lendItemFormID").value = itemID;
		}
	});
	
}

//submit lend item form

function submitLendItem() {
	var newLendContainer = document.querySelector(".page.item.addLend .lendNewItemConatiner");
	
	var itemID = newLendContainer.querySelector("#lendItemFormID").value;
	var amount = newLendContainer.querySelector("#lendItemFormAmount").value;
	var returnDate = newLendContainer.querySelector("#lendItemFormDate").value;
	
	$.post(INCLUDES+"/item_functions.php",{
		requestType: "lendItem",
		itemID: itemID,
		amount: amount,
		returnDate: returnDate,
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
			headerNotification(LANG.ITEM_LEND_SUCCESS,"green");
			newLendContainer.classList.add("none");
		} else {
			alert(data);
		}
	});
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

//Open page to add lend

function addLendItem(returnHome = 0) {
	if(returnHome == 0) {
		redirect(URL+"/item/lend/add");
	} else {
		redirect(URL+"/item/lended");
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

