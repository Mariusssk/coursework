			</div>

		</div>	
	
		<!-- Footer Section Begin -->
		<footer class="footer spad">
			<div class="langMenu">
				<div class="flagContainer" onclick="changeLang('de')"><img class="flag" src="<?php echo IMAGES?>/flag_germany.png"></div>
				<div class="flagContainer" onclick="changeLang('en')"><img class="flag" src="<?php echo IMAGES?>/flag_uk.png"></div>
			</div>
			<div class="scannerIcon">
				<a href="<?php echo URL;?>/scan/open"><i class="fa fa-qrcode" aria-hidden="true"></i></a>
			</div>
		</footer>
		<!-- Footer Section End -->
		
	</div>
	
<?php

if(isset($_POST['headerNotification']) AND !empty($_POST['headerNotification'])) {
	$colour = "red";
	if(isset($_POST['headerNotificationColour']) AND !empty($_POST['headerNotificationColour'])) {
		$colour = $_POST['headerNotificationColour'];
	}
	echo '<script> headerNotification("'.$_POST['headerNotification'].'","'.$colour.'");</script>';
}
?>	

</body>

</html>