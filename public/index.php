<?php

//-----------------New Page---------------------
//Startpage / Dashboard
//-----------------New Page---------------------

include_once("../resources/config.php");

if($session->loggedIn()) {

	include(TEMPLATES."/header/header_small.php");


	?>

	<script> var protectedPage = true; </script>

	<div class="page index">
		<div class="row">
			<div class="col-12 col-md-6 dataBox">
				<div class="boxHeading">
					<?php echo DASHBORD_BOX_LEND_HEADER;?>
				</div>
				<div class="boxContent">
					<?php
					//Get items lend by user
					$lendItems = Lend::getAllLendByUser($session->getSessionUserID());
					//check if user has lend items
					if(count($lendItems) > 0) {
						?>
						<table class="lendItems">
							<tr>
								<th> <?php echo ITEM_OVERVIEW_SEARCH_PLACEHOLDER_NAME;?> </th>
								<th> <?php echo ITEM_EDIT_LEND_HEADLINE_RETURN_DATE;?> </th>
							</tr>
						<?php
						//if yes display them
						foreach($lendItems as $tmpLend) {
							$lend = new Lend;
							if($lend->loadData($tmpLend)) {
								
								echo "<tr><td>".$lend->getItemName()."</td><td> ".$lend->getReturnDate("display")."</td></tr>";
							}
						}
						echo "</table>";
					} else {
						//if no display message that no items are lend
						echo DASHBORD_BOX_LEND_EMPTY;
					}
					?>
				</div>
			</div>
		</div>
	</div>


	<?php

	include(TEMPLATES."/footer/footer.php");
} else {
	include(TEMPLATES."/page/login_needed.php");
}
?>
