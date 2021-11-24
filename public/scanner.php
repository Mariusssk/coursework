<?php

include_once("../resources/config.php");

include(TEMPLATES."/header/header_basic.php");
//check if code has been included in URL
if(isset($_GET['code'])) {
	$code = $_GET['code'];
} else {
	$code = "";
}
?>
<div class="barcodeReaderContainer none"><div class="closeWindow" onclick="closeScanner()"> <i class="fa fa-window-close" aria-hidden="true"></i></div><div id="barcodeReader" class="barcodeReader"></div></div>

<div class="fullPageContent">
	<div class="generalButton" onclick="openScanner('scan')">hh</div>
</div>
<?php


?>

<?php

include(TEMPLATES."/footer/footer.php");
