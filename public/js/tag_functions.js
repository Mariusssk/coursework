//Load list of all notification requests 

function loadTagList() {
	
	var tagName = document.querySelector('.page.tags.tagList .searchInput[data-search-name="tagName"]').value;

	
	$.post(INCLUDES+"/settings_functions.php",{
		requestType: "loadTagList",
		tagName: tagName
	},
	function(data, status){
		var dataCut = parsePostData(data);
		
		//check if request is valid
		if(dataCut == "error" || dataCut == "") {
			headerNotification(LANG.ERROR_REQUEST_FAILED,"red");
		} else {
			data = JSON.parse(data);
			
			tags = data['tags'];
			
			var tagString = "";

			//run trough every tag send back by php
			
			for(i = 0;i < Object.keys(tags).length;i++) {
				var tmpTag = "";
				
				//create tag html elment
				
				if(tags[i]['type'] != "") {
					tmpTag += `<div class="row tagContainer" data-request-id="`+tags[i]['tagID']+`">`;
					
					
					//Name
					
					tmpTag += `<div class="tagName td col-sm-6 col-7">`+tags[i]['name']+`</div>`;
					
					//Color
					
					var backgroundRGB = hexToRgb(data['tags'][i]['colour']);
					if((backgroundRGB['r']*0.299)+(backgroundRGB['g']*0.587)+(backgroundRGB['b']*0.114)>186) {
						var textColour = "black";
					} else {
						var textColour = "white";
					}
					
					tmpTag += `<div class="td col-sm-4 col-5 tagColorContainer" ><div class="tagColor" style="color: `+textColour+`;background-color: `+tags[i]['colour']+`">`+tags[i]['colour']+`</div></div>`;
					
					//End
					
					tmpTag += `
					<div class="td col-sm-2 col-12 tagActions noselect">
						<span class="editTag icon" onclick="editTagForm('`+tags[i]['tagID']+`')">
							<i class="fa fa-pencil-square" aria-hidden="true"></i>
						</span>
						<span class="deleteTag icon" onclick="deleteTag('`+tags[i]['tagID']+`')">
							<i class="fa fa-trash" aria-hidden="true"></i>
						</span>
					</div>
					`;
					
					tmpTag += `</div>`;
					
					tagString += tmpTag;
				}
				
				
			}
			
			//check if there are any tags
			
			if(tagString.length == 0) {
				tagString = LANG.TAG_LIST_NON_FOUND
			} 
			
			document.querySelector(".page.tags.tagList .tagListContainer").innerHTML = tagString;
			
		}
		
	});
}

//Display form to edit tag

function editTagForm(tagID) {
	var tagContainer = document.querySelector('.page.tags.tagList .tagContainer[data-request-id="'+tagID+'"]');
	
	if(!tagContainer.classList.contains("edit")) {
		
		tagContainer.classList.add("edit");
		
		//Name
		
		var tagNameContainer = tagContainer.querySelector(".tagName");
		
		var tagName = tagNameContainer.innerHTML;
		
		tagNameContainer.innerHTML = `
			<input type="text" class="generalInput" data-input-name="tagName" value="`+tagName+`">
		`;
		
		//color
		
		var tagColorContainer = tagContainer.querySelector(".tagColorContainer");
		
		var tagColor = tagColorContainer.querySelector(".tagColor").innerHTML;
		
		tagColorContainer.innerHTML = `
			`+LANG.TAG_LIST_COLOR+`: <input type="color" class="colorInput" data-input-name="tagColor" value="`+tagColor+`">
		`;
		
		//Actions
		
		var tagActionsContainer = tagContainer.querySelector(".tagActions");
		
		tagActionsContainer.innerHTML = `
			<span class="icon" onclick="editTagForm('`+tagID+`')">
				<i class="fa fa-window-close" aria-hidden="true"></i>
			</span>
			<span class="icon" onclick="saveTagData('`+tagID+`')">
				<i class="fa fa-check-square" aria-hidden="true"></i>
			</span>
		`;
	} else {
		tagContainer.classList.remove("edit");
		
		loadTagList();
	}
}

//Delete tag form and submit

function deleteTag(tagID) {
	var tagContainer = document.querySelector('.page.tags.tagList .tagContainer[data-request-id="'+tagID+'"]');
	
	if(!tagContainer.classList.contains("delete")) {
		tagContainer.classList.add("delete");
		
		var tagActionsContainer = tagContainer.querySelector(".tagActions");
		
		tagActionsContainer.innerHTML = `
			<span class="icon" onclick="loadTagList('`+tagID+`')">
				<i class="fa fa-window-close" aria-hidden="true"></i>
			</span>
			<span class="icon" onclick="deleteTag('`+tagID+`')">
				<i class="fa fa-check-square" aria-hidden="true"></i>
			</span>
		`;
		
	} else {
		
		$.post(INCLUDES+"/settings_functions.php",{
			requestType: "deleteTag",
			tagID: tagID
		},
		function(data, status){
			var options = "";
			var dataCut = parsePostData(data);
			//check if request is valid
			if(dataCut == "error" || dataCut == "") {
				headerNotification(LANG.ERROR_REQUEST_FAILED,"red");
			} else if(dataCut == "missingRights") {
				headerNotification(LANG.USER_RIGHTS_MISSING,"red");
			} else if(dataCut == "used") {
				headerNotification(LANG.TAG_LIST_CURRENTLY_USED,"red");
			} else if(dataCut == "success") {
				headerNotification(LANG.PHRASE_SAVED_SUCCESS,"green");
				loadTagList();
			}
		});
	}
	
}

//save tag data

function saveTagData(tagID) {
	var tagContainer = document.querySelector('.page.tags.tagList .tagContainer[data-request-id="'+tagID+'"]');
	
	if(tagContainer.classList.contains("edit")) {
		var tagName = tagContainer.querySelector('.generalInput[data-input-name="tagName"]').value;
		var tagColor = tagContainer.querySelector('.colorInput[data-input-name="tagColor"]').value;
		
		
		if(tagName == "" || tagColor == "") {
			headerNotification(LANG.PHRASE_INPUT_EMPTY,"red");
		} else {
			
			$.post(INCLUDES+"/settings_functions.php",{
				requestType: "saveTagData",
				tagID: tagID,
				tagName: tagName,
				tagColor: tagColor
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
					loadTagList();
				}
			});
		}
	}
}

//Create a new tag

function createNewTag() {
	$.post(INCLUDES+"/settings_functions.php",{
		requestType: "createNewTag"
	},
	function(data, status){
		var options = "";
		var dataCut = parsePostData(data);
		//check if request is valid
		if(dataCut == "error" || dataCut == "") {
			headerNotification(LANG.ERROR_REQUEST_FAILED,"red");
		} else if(dataCut == "missingRights") {
			headerNotification(LANG.USER_RIGHTS_MISSING,"red");
		}else if(dataCut == "success") {
			headerNotification(LANG.PHRASE_SAVED_SUCCESS,"green");
			loadTagList();
		}
	});
}