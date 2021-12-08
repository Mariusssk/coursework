<?php

include_once("../resources/config.php");

//check if user is logged-in

if($session->loggedIn() === True) {
	include(TEMPLATES."/header/header_small.php");
	
	//Check if specific reuqest is send
	
	$requestAllowed = array("generateCode");
	if(isset($_GET['request']) AND !empty($_GET['request']) AND in_array($_GET['request'],$requestAllowed)) {
		$request = $_GET['request'];
	} else {
		$request = "generateCode";
	}

	//Page
	
	?>
	<script> var protectedPage = true; </script>
	<div class="page scan <?php echo $request;?>">
		<?php 
		//Load page data based on request
		if($request == "generateCode") {
			?>
			<img src="https://chart.apis.google.com/chart?chs=500x500&cht=qr&chl=ddd">
			<?php
		}
		?>
	</div>
	<?php
	include(TEMPLATES."/footer/footer.php");
} else {
	include(TEMPLATES."/page/login_needed.php");
}

