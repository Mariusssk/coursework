			</div>
			
			<!-- Footer Section Begin -->
			<footer class="footer spad">
			</footer>
			<!-- Footer Section End -->

			<!-- Js Plugins -->
			<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
			<script src="<?php echo JS;?>/jquery.nice-select.min.js"></script>
			<script src="<?php echo JS;?>/functions.js"></script>
			<script src="<?php echo JS;?>/user_functions.js"></script>
		
		</div>
	</div>
	
<?php

// Include js Language file
if(isset($_SESSION['lang'])){
	$langFileDir = LANG."/lang_".$_SESSION['lang'].".js.php";
	if(file_exists($langFileDir)){
		include $langFileDir;
	}
}

include LANG."/lang_en.js.php";

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