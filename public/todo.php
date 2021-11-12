<?php

include_once("../resources/config.php");

//check if user is logged-in

if($session->loggedIn() === True) {
	include(TEMPLATES."/header/header_small.php");
	
	//Check if specific reuqest is send
	
	$requestAllowed = array("overviewPersonal","overviewGlobal","categoryOverview","categoryNew","categoryEdit");
	if(isset($_GET['request']) AND !empty($_GET['request']) AND in_array($_GET['request'],$requestAllowed)) {
		$request = $_GET['request'];
	} else {
		$request = "overview";
	}
	//Page
	
	?>
	<script> var protectedPage = true; </script>
	<div class="page todo <?php echo $request;?>">
		<?php 
		//Load page data based on request
		if($request == "categoryOverview") {
			if($session->checkRights("edit_personal_todo_list") == True OR $session->checkRights("edit_todo_list_categories") == True) {
				?>
				<div class="generalTable">
					<div class="row generalTableSearch">
						<div class="td col-md-6 col-sm-12">
							<input type="text" class="generalInput searchInput" data-search-name="name" placeholder="<?php echo TODO_CATEGORY_OVERVIEW_HEADER_NAME;?>">
						</div>
						<div class="td d-none d-md-block col-md-3">
							<div class="generalSearchBarButton" onclick="loadTodoCategories()"> <?php echo WORD_SEARCH;?> </div>
						</div>
						<div class="td d-block d-md-none col-12">
							<div class="generalButton" onclick="loadTodoCategories()"> <?php echo WORD_SEARCH;?> </div>
						</div>
						<div class="td d-none d-md-block col-md-3">
							<div class="generalSearchBarButton createNewToDoBtn" onclick="createNewCategory()"> <?php echo WORD_NEW;?> </div>
						</div>
						<div class="td d-block d-md-none col-12">
							<div class="generalButton createNewToDoBtn" onclick="createNewCategory()"> <?php echo WORD_NEW;?> </div>
						</div>
					</div>
					<div class="row generalTableHeader">
						<div class="td col-8">
							<?php echo TODO_CATEGORY_OVERVIEW_HEADER_NAME;?>
						</div>
						<div class="td col-4">
							<?php echo TODO_CATEGORY_OVERVIEW_HEADER_TYPE;?>
						</div>
					</div>
					<div class="tableContent" id="categroyList">
						<div class="row generalTableContentRow">
							<div class="td col-12">
								<?php echo WORD_LOADING;?>
							</div>
						</div>
					</div>
				</div>
				<script>
					//load all categories
					loadTodoCategories();
				</script>
				<?php
			} else {
				include(TEMPLATES."/user/missing_rights.php");
			}
		} else if($request == "categoryNew" OR $request == "categoryEdit") {
			//check rights for edit/new
			if($session->checkRights("edit_personal_todo_list") == True OR $session->checkRights("edit_todo_list_categories") == True) {
				$category = new ToDoCategory;
				if(isset($_GET['ID'])) {
					$category->loadData($_GET['ID']);
				}
				
				if(($request == "categoryEdit" AND ($session->checkRights("edit_todo_list_categories") == True AND $category->getGlobal() == True) OR ($session->checkRights("edit_personal_todo_list") == True AND $category->getGlobal() == False)) OR $request == "categoryNew") {
					//display form
					?>
					<div class="row">
						<div class="col-sm-12 inputBlock">
							<?php echo TODO_CATEGORY_EDIT_NAME;?>*:<br>
							<input type="text" class="generalInput dataInput" data-input-name="name" value="<?php echo $category->getName();?>"><br>
						</div>
						<?php 
						if($session->checkRights("edit_personal_todo_list") == True AND $session->checkRights("edit_todo_list_categories") == True) {
							?>
							
							<div class="col-sm-12 inputBlock">
								<br>
								<?php echo TODO_CATEGORY_EDIT_GLOBAL;?>:
								<input type="checkbox" class="generalCheckbox dataInput" data-input-name="global" <?php echo $category->getGlobalChecked();?>><br>
								<br>
							</div>
							<?php
						}
						//check if user has right do delete
						if($request == "categoryEdit") {
							?>
							<div class="col-12 col-md-6">
								<div class="generalButton" onclick="saveCategoryData('<?php echo $category->getID();?>')"> <?php echo WORD_SAVE;?> </div>
							</div>
							<div class="col-12 col-md-6" id="checkDeleteContainer">
								<div class="generalButton" onclick="deleteCategory('<?php echo $category->getID();?>','check')"> <?php echo WORD_DELETE;?> </div>
							</div>
							<div class="col-12 col-md-6 none" id="confirmDeleteContainer">
								<div class="generalButton" onclick="deleteCategory('<?php echo $category->getID();?>','confirm')"> <?php echo WORD_DELETE;?> </div> <div class="generalButton" onclick="deleteCategory('<?php echo $category->getID();?>','abort')"> <?php echo WORD_ABORT;?> </div>
							</div>
							<?php
						} else {
							?>
							<div class="col-12">
								<div class="generalButton" onclick="saveCategoryData('<?php echo $category->getID();?>')"> <?php echo WORD_SAVE;?> </div>
							</div>
							<?php
						}?>
						
					</div>
					<?php
				} else {
					include(TEMPLATES."/user/missing_rights.php");
				}
				
			} else {
				include(TEMPLATES."/user/missing_rights.php");
			}
		}  
		
		//Display todo list
		
		else if($request == "overviewPersonal" OR $request == "overviewGlobal") {
			//check rights to view list
			if(($request == "overviewPersonal" AND $session->checkRights("edit_personal_todo_list") == True) OR ($request == "overviewGlobal" AND $session->checkRights("view_all_todo_lists") == True)) {
				//load todo list categories based on view type
				if($request == "overviewPersonal") {
					$categories = ToDoCategory::getPersonalCategories($session->getSessionUserID());
					$requestType = "personal";
				} else if($request == "overviewGlobal") {
					$categories = ToDoCategory::getGlobalCategories();
					$requestType = "global";
				}
				
				//add overflow categories
				if($request == "overviewGlobal") {
					array_unshift($categories, "event");
					array_unshift($categories, "uncategorized");
				} else if($request == "overviewPersonal") {
					array_unshift($categories, "uncategorizedPersonal");
				}
				
				?>
				<div class="overlayContainer none">
					<div class="todoListOverlay">
						<div class="head">
							<div class="todoListName">
								<?php echo WORD_LOADING;?>
							</div>
							<div class="closeButton">
								<i class="fa fa-trash" aria-hidden="true" onclick="deleteToDoList()"></i>
								<i class="fa fa-window-close" aria-hidden="true" onclick="closeOverlay(this)"></i>
							</div>
						</div>
						<div class="labels">
							<div class="labelHead"> <?php echo WORD_LABELS;?></div>
							<div class="labelContainer"><?php echo WORD_LOADING;?></div>
						</div>
						<hr>
						<div class="todoListEntries">
							<div class="entriesContainer"><?php echo WORD_LOADING;?></div>
							<div class="editEntriesContainer"><?php echo WORD_LOADING;?></div>
						</div>
						<hr>
						<div class="comments">
							<span class="commentsHead"> <?php echo WORD_COMMENTS;?></span>
							<div class="commentsContainer"> <?php echo WORD_LOADING;?> </div>
						</div>
					</div>
				</div>
				<div class="todoListContainer">
					
				<?php
				if(count($categories) > 0) {
					//load lists based on category
					foreach($categories as $tmpCategory) {
						$category = new ToDoCategory;
						
						//set name of category
						if(is_int($tmpCategory) AND $category->loadData($tmpCategory)) {
							$name = $category->getName();
						} else if($tmpCategory == "event") {
							$name = TODO_LISTS__GLOBAL_HEAD_EVENT;
						} else if($tmpCategory == "uncategorized" OR $tmpCategory == "uncategorizedPersonal") {
							$name = TODO_LISTS_HEAD_UNCATEGORIZED;
						}
						
						//check lists are existing in category
						$lists = ToDoList::findListByCategory($tmpCategory, $session->getSessionUserID());
						if(count($lists) > 0 OR $tmpCategory == "uncategorized" OR $tmpCategory == "uncategorizedPersonal") {
							?>
							<div class="todoListCategoryContainer">
								<div class="todoListCategoryHead">
									<span class="todoListCategoryName"> <?php echo $name; ?> </span>
								</div>
								<div class="todoListCategoryBody">
									<?php
									if(
										($tmpCategory == "uncategorized" OR $tmpCategory == "uncategorizedPersonal") AND (
											($request == "overviewPersonal" AND $session->checkRights("edit_personal_todo_list") == True) OR ($request == "overviewGlobal" AND $session->checkRights("create_global_todo_list") == True)
										)
									) {
										?>
										<div class="generalButton createNewToDoBtn" onclick="createNewTodoList('<?php echo $requestType;?>')"> <?php echo WORD_NEW;?> </div>
										<?php
									}
										//Display all lists
										foreach($lists as $tmpList) {
											$list = new ToDoList;
											if($list->loadData($tmpList)) {
												$tags = $list->loadTags();
												?>
												<div class="todoList container" onclick="openToDoList('<?php echo $list->getID();?>')">
													<div class="todoListTags row">
														<?php
														foreach($tags as $tmpTag) {
															$tag = new Tag;
															if($tag->loadData($tmpTag)) {
																?>
																<div class="col tag" style="background-color: <?php echo $tag->getColour();?>"> &nbsp </div>
																<?php
															}
														}
														?>
													</div>
													<div class="todoListName">
														<?php echo $list->getName();?>
													</div>
												</div>
												<?php
											}
										}
									?>
								</div>
							</div>
							<?php
						}
					}
					
				} 

				?>
				</div>
				<?php
			} else {
				include(TEMPLATES."/user/missing_rights.php");
			}
		}
		?>
	</div>
	<?php
	include(TEMPLATES."/footer/footer.php");
} else {
	include(TEMPLATES."/page/login_needed.php");
}

