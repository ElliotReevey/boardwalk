<p><?=gettext("Enter your new password below to reset your password.")?></p>
<p><?=gettext("Once you have submitted the form please use your new password to login.")?></p>

<?php if(isset($fail)) { echo errorbox($fail); } ?>

<?=form_open_this("home/resetpassword/submit")?>
	<div class="contentContainer">
		<div class="fieldHolder">
			<div class="labelItem"><?=gettext("New Password:")?></div>
			<div class="inputField"><input type="password" name="newPassword" class="text"></div>
		</div>
		<div class="fieldHolder">
			<div class="labelItem"><?=gettext("Confirm New Password:")?></div>
			<div class="inputField"><input type="password" name="confirmNewPassword" class="text"></div>
		</div>
		<div class="inputButton">
			<input type="submit" class="submitButton" value="<?=gettext("Reset")?>" name="submitButton">
			<input type="hidden" name="token" value="<?=$token?>">
		</div>
	</div>
</form>