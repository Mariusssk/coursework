//load comment container

//Objective 6.3/7.5

function loadCommentSection(type, attributeID, containerID) {
	var commentContainer = "";
	
	commentContainer += `<div class="commentSection" data-attribute-id="`+attributeID+`" data-attribute-type="`+type+`">`;
	
	//Comments List
	
	commentContainer += `<div class="commentsList">`;
	
	loadComments(type, attributeID, containerID)
	
	commentContainer += `</div>`;
	
	//new comment
	commentContainer += `<div class="commentInputContainer">`
	
	commentContainer += `<div class="generalButton openCommentForm" onclick="displayNewCommentForm('`+type+`','`+attributeID+`','`+containerID+`')">`+LANG.COMMENTS_INPUTS_NEW_COMMENT_BUTTON+`</div>`;
	
	commentContainer += `</div>`;
	
	commentContainer += `</div>`;
	
	document.getElementById(containerID).innerHTML = commentContainer;
}

//toggle notifications for comments
//Objectives 11.1

function toggleCommentNotifications(type, attributeID, newState = "") {

	$.post(INCLUDES+"/post_functions.php",{
		requestType: "toggleCommentNotifications",
		type: type,
		attributeID: attributeID,
		newState: newState
	},
	function(data, status){
		var options = "";
		var dataCut = parsePostData(data);
		//check if request is valid
		if(dataCut == "error" || dataCut == "") {
			headerNotification(LANG.ERROR_REQUEST_FAILED,"red");
		} else if(dataCut == "missingRights") {
			headerNotification(LANG.USER_RIGHTS_MISSING,"red");
		} else if(dataCut == "success"){
			headerNotification(LANG.COMMENTS_NOTIFICATIONS_REQUEST_CHANGE_SUCCESS,"green");
		} else {
			console.log(data);
		}
	});
}

//open form to put in new comment
//Objectives 6.3/7.5
function displayNewCommentForm(type, attributeID, containerID) {
	var commentContainer = "";
	
	//Add comment
	
	
	commentContainer += `
	<span>`+LANG.COMMENTS_INPUTS_NEW_COMMENT_HEADLINE+`</span>
	<input type="text" class="generalInput newComment" placeholder="`+LANG.COMMENTS_INPUTS_NEW_COMMENT_PLACEHOLDER+`">
	<span class="saveComment" onclick="saveNewComment('`+type+`','`+attributeID+`','`+containerID+`')">
		<i class="fa fa-check-square" aria-hidden="true"></i>
	</span>`;
	
	
	
	document.getElementById(containerID).querySelector(".commentInputContainer").innerHTML = commentContainer;
}

//load all comments for specific todo list and add to display
//Objectives 6.3/7.5
function loadComments(type, attributeID, containerID) {
	$.post(INCLUDES+"/post_functions.php",{
		requestType: "loadComments",
		attributeID: attributeID,
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
		} else  {
			data = JSON.parse(data);
			var comments = data['comments'];
			
			var commentString = "";
			
			for(i = 0; i < Object.keys(comments).length; i++) {
				var tmpComment = "";
				
				tmpComment += `<div class="commentContainer" data-comment-id="`+comments[i]['commentID']+`">`;
				if(comments[i]['edit'] == true) {
					tmpComment += `<span class="commentEdit" onclick="editCommentForm('`+comments[i]['commentID']+`','`+containerID+`')"><i class="fa fa-pencil-square" aria-hidden="true"></i></span>`;
				}
				tmpComment += `<span class="commentData">`+comments[i]['data']+`</span>`;
				tmpComment += `<br>`;
				tmpComment += `<span class="commentInfo">`+comments[i]['username']+` - `+comments[i]['timestamp']+`</span>`;
				tmpComment += `</div>`;
				
				commentString += tmpComment;	
			}
			
			if(commentString == "") {
				commentString = LANG.COMMENTS_LIST_EMPTY;
			}
			
			document.getElementById(containerID).querySelector(".commentsList").innerHTML = commentString;
		}
	});
}

//Display form to edit comment
//Objectives 6.3/7.5
function editCommentForm(commentID, containerID) {
	var commentContainer = document.getElementById(containerID).querySelector(".commentContainer[data-comment-id='"+commentID+"']");

	var comment = commentContainer.querySelector(".commentData").innerHTML;
	
	var commentEditForm = `
		<input type="text" class="generalInput editCommentInput" value="`+comment+`">
		<span class="commentEdit" onclick="saveEditedComment('`+commentID+`','`+containerID+`')"><i class="fa fa-check-square" aria-hidden="true"></i></span>
	`;
	
	commentContainer.innerHTML = commentEditForm;
}

//send request to php to save edited comment
//Objectives 6.3/7.5
function saveEditedComment(commentID, containerID) {
	var comment = document.getElementById(containerID).querySelector(".commentContainer[data-comment-id='"+commentID+"'] .generalInput.editCommentInput").value;
	
	
	if(comment == "") {
		headerNotification(LANG.COMMENTS_INPUTS_NEW_COMMENT_PLACEHOLDER,"red");
	} else {
		
		$.post(INCLUDES+"/post_functions.php",{
			requestType: "saveEditedComment",
			commentID: commentID,
			comment: comment
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

				headerNotification(LANG.COMMENTS_INPUTS_EDITED_COMMENT_SAVED_SUCCESS,"green");
				loadCommentSection(data['type'], data['attributeID'], containerID);

			}
		});
	}
}

//send request to php to save new comment
//Objectives 6.3/7.5
function saveNewComment(type, attributeID, containerID) {
	var comment = document.getElementById(containerID).querySelector(".commentInputContainer .generalInput.newComment").value;
	
	
	if(comment == "") {
		headerNotification(LANG.COMMENTS_INPUTS_NEW_COMMENT_PLACEHOLDER,"red");
	} else {
		
		$.post(INCLUDES+"/post_functions.php",{
			requestType: "saveNewComment",
			attributeID: attributeID,
			type: type,
			comment: comment
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
				headerNotification(LANG.COMMENTS_INPUTS_NEW_COMMENT_SAVED_SUCCESS,"green");
				loadCommentSection(type, attributeID, containerID);
			}
		});
	}
}