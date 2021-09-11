<?php

$URL = $_SERVER['REQUEST_URI'];

$URL = str_replace(URL_PATH,"",$URL);

$URL = ltrim($URL, '/');

if($URL == "") {
	$URL = "dashbord";
}
?>

<script>

$( document ).ready(function() {
    setMenuSelected("<?php echo $URL;?>");
});

</script>

<!-- Desktop menu -->
				
<div class="desktopMenuContainer">

	<!-- Dashbord -->
	<div class="menuElement" data-destination="dashbord">
		<a href="<?php echo URL;?>">Dashbord</a>
	</div>
	
	<!-- Storages -->
	<?php 
	if($session->checkRights("view_storages")) {
		?>
		<div class="menuElement" data-sub-menu-parent="Storage" data-destination="storage">
			<a href="<?php echo URL;?>/storage">Storage</a>
		</div>
		<div class="subMenu" data-sub-menu="Storage">
			<div class="menuElement subElement" data-destination="storage">
					<a href="<?php echo URL;?>/storage"><?php echo HEADER_MENU_ITEM_STORAGE_OVERVIEW;?></a>
				</div>
			<?php 
			if($session->checkRights("create_new_storage")) {
			?>
				<div class="menuElement subElement" data-destination="storage/new">
					<a href="<?php echo URL;?>/storage/new"><?php echo HEADER_MENU_ITEM_STORAGE_CREATE;?></a>
				</div>
				<?php
			}
			?>
		</div>
		<?php
	}?>
	
	<!-- Items -->
	
	<?php
	if($session->checkRights("view_all_items")) {
	?>
		<div class="menuElement" data-sub-menu-parent="Items" data-destination="item">
			<a href="<?php echo URL;?>/item"><?php echo HEADER_MENU_ITEM_ITEM_MAIN;?></a>
		</div>
		<div class="subMenu" data-sub-menu="Items">
			<div class="menuElement subElement" data-destination="item/consumable">
				<a href="<?php echo URL;?>/item/consumable"><?php echo HEADER_MENU_ITEM_ITEM_CONSUMABLE;?></a>
			</div>
			<div class="menuElement subElement" data-destination="item">
				<a href="<?php echo URL;?>/item"><?php echo HEADER_MENU_ITEM_ITEM_OVERVIEW;?></a>
			</div>
			<?php 
			if($session->checkRights("create_new_item")) {
			?>
				<div class="menuElement subElement" data-destination="item/new">
					<a href="<?php echo URL;?>/item/new"><?php echo HEADER_MENU_ITEM_ITEM_CREATE;?></a>
				</div>
				<?php
			}
			if($session->checkRights("lend_item")) {
				?><div class="menuElement subElement" data-destination="item/lend">
					<a href="<?php echo URL;?>/item/lend"><?php echo HEADER_MENU_ITEM_ITEM_LEND;?></a>
				</div><?php
			}
			?>
		</div>
		<?php
	}
	?>
	
	<!-- To Do List -->
	
	<?php
	if($session->checkRights("view_all_todo_lists")) {
	?>
		<div class="menuElement" data-sub-menu-parent="Todo" data-destination="todo">
			<a href="<?php echo URL;?>/todo">To-Do</a>
		</div>
		<div class="subMenu" data-sub-menu="Todo">
			<?php 
			if($session->checkRights("create_todo_list")) {
			?>
				<div class="menuElement subElement" data-destination="storage/new">
					<a href="<?php echo URL;?>/todo/new"><?php echo HEADER_MENU_ITEM_TODO_CREATE;?></a>
				</div>
				<div class="menuElement subElement" data-destination="storage">
					<a href="<?php echo URL;?>/todo"><?php echo HEADER_MENU_ITEM_TODO_OVERVIEW;?></a>
				</div>
				<?php
			}
			?>
		</div>
		<?php
	}
	?>
	
	<!-- Events -->
	
	<?php
	if($session->checkRights("view_all_events") OR $session->checkRights("view_own_events")) {
	?>
		<div class="menuElement" data-sub-menu-parent="Events" data-destination="event">
			<a href="<?php echo URL;?>/events">Events</a>
		</div>
		<div class="subMenu" data-sub-menu="Events">
			<div class="menuElement subElement" data-destination="event">
				<a href="<?php echo URL;?>/event"><?php echo HEADER_MENU_ITEM_EVENT_OVERVIEW;?></a>
			</div>
			<?php 
			if($session->checkRights("create_event")) {
				?><div class="menuElement subElement" data-destination="event/new">
					<a href="<?php echo URL;?>/event/new"><?php echo HEADER_MENU_ITEM_EVENT_CREATE;?></a>
				</div><?php
			}
			
			if($session->checkRights("edit_event_locations")) {
				?><div class="menuElement subElement" data-destination="event/locations">
					<a href="<?php echo URL;?>/event/locations"><?php echo HEADER_MENU_ITEM_EVENT_LOCATIONS;?></a>
				</div><?php
			}?>
			
		</div>
		<?php
	}
	?>
	
	<!-- Settings -->
	
	<div class="menuElement" data-sub-menu-parent="Settings" data-destination="settings">
		<a href="<?php echo URL;?>/settings/user"><?php echo HEADER_MENU_ITEM_SETTINGS_MAIN;?></a>
	</div>
	<div class="subMenu" data-sub-menu="Settings">
		<div class="menuElement subElement" data-destination="settings/user">
			<a href="<?php echo URL;?>/settings/user"><?php echo HEADER_MENU_ITEM_SETTINGS_USER;?></a>
		</div>
		<div class="menuElement subElement" data-destination="settings/user/new">
			<a href="<?php echo URL;?>/settings/notifications"><?php echo HEADER_MENU_ITEM_SETTINGS_NOTIFICATIONS;?></a>
		</div>
		<?php 
		if($session->checkRights("create_user")) {
			?><div class="menuElement subElement" data-destination="settings/user/new">
				<a href="<?php echo URL;?>/settings/user/new"><?php echo HEADER_MENU_ITEM_SETTINGS_CREATE_USER;?></a>
			</div><?php
		}
		
		if($session->checkRights("edit_user")) {
			?><div class="menuElement subElement" data-destination="settings/user/list">
				<a href="<?php echo URL;?>/settings/user/list"><?php echo HEADER_MENU_ITEM_SETTINGS_USER_OVERVIEW;?></a>
			</div><?php
		}
		
		if($session->checkRights("edit_user_role")) {
			?><div class="menuElement subElement" data-destination="settings/user/roles">
				<a href="<?php echo URL;?>/settings/user/roles"><?php echo HEADER_MENU_ITEM_SETTINGS_USER_ROLE;?></a>
			</div><?php
		}?>
		
	</div>
	
	<!--<div class="menuElement " data-sub-menu-parent="subMenuTest" data-destination="">
		<a href="<?php echo URL;?>">Test</a></i>
	</div>
	<div class="subMenu" data-sub-menu="subMenuTest">
		<div class="menuElement subElement" data-destination="">
			<a href="<?php echo URL;?>">Test</a>
		</div>
		<div class="menuElement subElement" data-destination="">
			<a href="<?php echo URL;?>">Test</a>
		</div>
	</div>-->
	
</div>

<!-- Mobile menu -->

<div class="mobileMenuContainer none">
	<!--<div class="menuElement" data-destination="dashbord">
		<a href="<?php echo URL;?>">Dashbord</a>
	</div>
	<div class="menuElement" data-destination="storage">
		<a href="<?php echo URL;?>/storage">Storage</a><i class="fa fa-caret-down" data-sub-menu-button="Storage" aria-hidden="true" onclick="subMenu('Storage')"></i>
	</div>
	<div class="subMenu none" data-sub-menu="Storage">
		<div class="menuElement subElement" data-destination="storage/new">
			<a href="<?php echo URL;?>/storage/new"><?php echo HEADER_MENU_ITEM_STORAGE_CREATE;?></a>
		</div>
		<div class="menuElement subElement" data-destination="storage">
			<a href="<?php echo URL;?>/storage"><?php echo HEADER_MENU_ITEM_STORAGE_OVERVIEW;?></a>
		</div>
	</div>-->
	
	<!-- Dashbord -->
	<div class="menuElement" data-destination="dashbord">
		<a href="<?php echo URL;?>">Dashbord</a>
	</div>
	
	<!-- Storages -->
	<?php 
	if($session->checkRights("view_storages")) {
		?>
		<div class="menuElement" data-sub-menu-parent="Storage" data-destination="storage">
			<a href="<?php echo URL;?>/storage">Storage</a><i class="fa fa-caret-down" data-sub-menu-button="Storage" aria-hidden="true" onclick="subMenu('Storage')"></i>
		</div>
		<div class="subMenu none" data-sub-menu="Storage">
			<?php 
			if($session->checkRights("create_new_storage")) {
			?>
				<div class="menuElement subElement" data-destination="storage/new">
					<a href="<?php echo URL;?>/storage/new"><?php echo HEADER_MENU_ITEM_STORAGE_CREATE;?></a>
				</div>
				<div class="menuElement subElement" data-destination="storage">
					<a href="<?php echo URL;?>/storage"><?php echo HEADER_MENU_ITEM_STORAGE_OVERVIEW;?></a>
				</div>
				<?php
			}
			?>
		</div>
		<?php
	}?>
	
	<!-- Items -->
	
	<?php
	if($session->checkRights("view_all_items")) {
	?>
		<div class="menuElement" data-sub-menu-parent="Items" data-destination="item">
			<a href="<?php echo URL;?>/item"><?php echo HEADER_MENU_ITEM_ITEM_MAIN;?></a><i class="fa fa-caret-down" data-sub-menu-button="Items" aria-hidden="true" onclick="subMenu('Items')"></i>
		</div>
		<div class="subMenu none" data-sub-menu="Items">
			<?php 
			if($session->checkRights("create_new_item")) {
			?>
				<div class="menuElement subElement" data-destination="item/new">
					<a href="<?php echo URL;?>/item/new"><?php echo HEADER_MENU_ITEM_ITEM_CREATE;?></a>
				</div>
				<div class="menuElement subElement" data-destination="item">
					<a href="<?php echo URL;?>/item"><?php echo HEADER_MENU_ITEM_ITEM_OVERVIEW;?></a>
				</div>
				<?php
			}
			if($session->checkRights("lend_item")) {
				?><div class="menuElement subElement" data-destination="item/lend">
					<a href="<?php echo URL;?>/item/lend"><?php echo HEADER_MENU_ITEM_ITEM_LEND;?></a>
				</div><?php
			}
			?>
		</div>
		<?php
	}
	?>
	
	<!-- To Do List -->
	
	<?php
	if($session->checkRights("view_all_todo_lists")) {
	?>
		<div class="menuElement" data-sub-menu-parent="Todo" data-destination="todo">
			<a href="<?php echo URL;?>/todo">To-Do</a><i class="fa fa-caret-down" data-sub-menu-button="Todo" aria-hidden="true" onclick="subMenu('Todo')"></i>
		</div>
		<div class="subMenu none" data-sub-menu="Todo">
			<?php 
			if($session->checkRights("create_todo_list")) {
			?>
				<div class="menuElement subElement" data-destination="storage/new">
					<a href="<?php echo URL;?>/todo/new"><?php echo HEADER_MENU_ITEM_TODO_CREATE;?></a>
				</div>
				<div class="menuElement subElement" data-destination="storage">
					<a href="<?php echo URL;?>/todo"><?php echo HEADER_MENU_ITEM_TODO_OVERVIEW;?></a>
				</div>
				<?php
			}
			?>
		</div>
		<?php
	}
	?>
	
	<!-- Events -->
	
	<?php
	if($session->checkRights("view_all_events") OR $session->checkRights("view_own_events")) {
	?>
		<div class="menuElement" data-sub-menu-parent="Events" data-destination="event">
			<a href="<?php echo URL;?>/events">Events</a><i class="fa fa-caret-down" data-sub-menu-button="Events" aria-hidden="true" onclick="subMenu('Events')"></i>
		</div>
		<div class="subMenu none" data-sub-menu="Events">
			<div class="menuElement subElement" data-destination="event">
				<a href="<?php echo URL;?>/event"><?php echo HEADER_MENU_ITEM_EVENT_OVERVIEW;?></a>
			</div>
			<?php 
			if($session->checkRights("create_event")) {
				?><div class="menuElement subElement" data-destination="event/new">
					<a href="<?php echo URL;?>/event/new"><?php echo HEADER_MENU_ITEM_EVENT_CREATE;?></a>
				</div><?php
			}
			
			if($session->checkRights("edit_event_locations")) {
				?><div class="menuElement subElement" data-destination="event/locations">
					<a href="<?php echo URL;?>/event/locations"><?php echo HEADER_MENU_ITEM_EVENT_LOCATIONS;?></a>
				</div><?php
			}?>
			
		</div>
		<?php
	}
	?>
	
	<!-- Settings -->
	
	<div class="menuElement" data-sub-menu-parent="Settings" data-destination="settings">
		<a href="<?php echo URL;?>/settings/user"><?php echo HEADER_MENU_ITEM_SETTINGS_MAIN;?></a><i class="fa fa-caret-down" data-sub-menu-button="Settings" aria-hidden="true" onclick="subMenu('Settings')"></i>
	</div>
	<div class="subMenu none" data-sub-menu="Settings">
		<div class="menuElement subElement" data-destination="settings/user">
			<a href="<?php echo URL;?>/settings/user"><?php echo HEADER_MENU_ITEM_SETTINGS_USER;?></a>
		</div>
		<div class="menuElement subElement" data-destination="settings/user/new">
			<a href="<?php echo URL;?>/settings/notifications"><?php echo HEADER_MENU_ITEM_SETTINGS_NOTIFICATIONS;?></a>
		</div>
		<?php 
		if($session->checkRights("create_user")) {
			?><div class="menuElement subElement" data-destination="settings/user/new">
				<a href="<?php echo URL;?>/settings/user/new"><?php echo HEADER_MENU_ITEM_SETTINGS_CREATE_USER;?></a>
			</div><?php
		}
		
		if($session->checkRights("edit_user")) {
			?><div class="menuElement subElement" data-destination="settings/user/list">
				<a href="<?php echo URL;?>/settings/user/list"><?php echo HEADER_MENU_ITEM_SETTINGS_USER_OVERVIEW;?></a>
			</div><?php
		}
		
		if($session->checkRights("edit_user_role")) {
			?><div class="menuElement subElement" data-destination="settings/user/roles">
				<a href="<?php echo URL;?>/settings/user/roles"><?php echo HEADER_MENU_ITEM_SETTINGS_USER_ROLE;?></a>
			</div><?php
		}?>
		
	</div>
	<hr>
	<div class="menuElement" data-destination="login">
		<a href="<?php echo URL;?>/login"><?php echo HEADER_MENU_USER_LOGOUT;?></a>
	</div>
	<div class="menuElement"></div>
	<div class="menuElement"></div>
</div>