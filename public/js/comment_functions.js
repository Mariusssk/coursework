//load comment container

function loadCommentSection(type, attributeID, containerID) {
	var commentContainer = "";
	
	commentContainer += `<div class="commentSection" data-attribute-id="`+attributeID+`" data-attribute-type="`+type+`">`;
	
	//Comments List
	
	commentContainer += `<div class="commentsList">`;
	
	commentContainer += `</div>`;
	
	//new comment
	commentContainer += `<div class="commentInputContainer">`
	
	commentContainer += `<div class="generalButton openCommentForm" onclick="displayNewCommentForm('`+type+`','`+attributeID+`','`+containerID+`')">`+LANG.COMMENTS_INPUTS_NEW_COMMENT_BUTTON+`</div>`;
	
	commentContainer += `</div>`;
	
	commentContainer += `</div>`;
	
	document.getElementById(containerID).innerHTML = commentContainer;
}

//open form to put in new comment

function displayNewCommentForm(type, attributeID, containerID) {
	var commentContainer = "";
	
	//Add comment
	
;
	
	commentContainer += `
	<span>`+LANG.COMMENTS_INPUTS_NEW_COMMENT_HEADLINE+`</span>
	<input type="text" class="generalInput newComment" placeholder="`+LANG.COMMENTS_INPUTS_NEW_COMMENT_PLACEHOLDER+`">
	<span class="saveComment" onclick="saveNewComment('`+type+`','`+attributeID+`','`+containerID+`')">
		<i class="fa fa-check-square" aria-hidden="true"></i>
	</span>`;
	
	
	
	document.getElementById(containerID).querySelector(".commentInputContainer").innerHTML = commentContainer;
}

//send request to php to save new comment

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
			} else {
				console.log(data);
			}
		});
	}
}