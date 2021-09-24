<div class="loginForm">
	<div class="loginNotice"></div>
	<input type="text" name="username" class="generalDataInput" data-input-name="username" placeholder="<?php echo USER_USERNAME;?>">
	<input type="password" name="password" class="generalDataInput" data-input-name="password" placeholder="<?php echo USER_PASSWORD;?>">
	<div class="loginButton" onclick="submitLogin()"><?php echo USER_LOGIN_BTN;?></div>
</div>
<div class="passwordResetRequest none">
	<input type="text" class="generalInput" data-input-name="resetUsername" placeholder="<?php echo USER_USERNAME;?>">
	<div class="generalButton" id="test" onclick="requestPasswordReset(this)"><?php echo USER_RESET_PASSWORD_BTN;?></div>
</div>