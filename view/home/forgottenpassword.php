<p><?=gettext("Lost your password? Dont worry! Reseting it is very simple.")?></p>
<p><?=gettext("Enter the email address which you used to sign up and click the 'Recover' button we will then send further details to your email address about resetting your password.")?></p>

<?php if(isset($fail)) { echo errorbox($fail); } ?>

<?=form_open_this("home/forgottenpassword/submit")?>
	<div class="contentContainer">
		<div class="fieldHolder">
			<div class="labelItem"><?=gettext("Email Address:")?></div>
			<div class="inputField"><input type="text" name="emailAddress" class="text" value="<?=set_value('emailAddress')?>"></div>
		</div>
		<div class="inputButton">
			<input type="submit" class="submitButton" value="<?=gettext("Recover")?>" name="submitButton">
		</div>
	</div>
</form>