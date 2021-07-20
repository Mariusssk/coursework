			</div>
			
			<!-- Footer Section Begin -->
			<footer class="footer spad">
			</footer>
			<!-- Footer Section End -->

		</div>
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