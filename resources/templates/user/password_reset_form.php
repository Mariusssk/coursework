<h4><?php echo USER_RESET_PASSWORD_BTN;?>:</h4>
<input class="generalInput" data-input-name="password" type="password" placeholder="<?php echo USER_PASSWORD;?>*">
<input class="generalInput" data-input-name="passwordRepeat" type="password" placeholder="<?php echo USER_REPEAT_PASSWORD;?>*">
<div class="generalButton" onclick="resetPassword()"><?php echo WORD_RESET;?></div>
<a class="generalButton" href="<?php echo URL;?>"><?php echo WORD_ABORT;?></a>
<div class="passwordRequirements none">
	<ul>
		<li><?php echo USER_PASSWORD_REQUIREMENTS_LENGTH;?></li>
		<li><?php echo USER_PASSWORD_REQUIREMENTS_NUMBER;?></li>
		<li><?php echo USER_PASSWORD_REQUIREMENTS_LOWERCASE;?></li>
		<li><?php echo USER_PASSWORD_REQUIREMENTS_UPPERCASE;?></li>
		<li><?php echo USER_PASSWORD_REQUIREMENTS_SPECIAL_CHAR_A;?></li>
		<li><?php echo USER_PASSWORD_REQUIREMENTS_SPECIAL_CHAR_B;?></li>
	</ul>
</div>
