<h1><?=gettext("Refer a Friend")?></h1>
<p><?=gettext("Enter your friends email address below and they will be sent an email with a referral code which they must enter upon registration.")?></p>

<?php if(isset($fail)) { echo errorbox($fail); } elseif(isset($success)) { successbox($success); } ?>

<?=form_open_this("personal/referafriend/submit")?>
	<div class="contentContainer">
		<div class="fieldHolder">
			<div class="labelItem"><?=gettext("Email Address:")?></div>
			<div class="inputField"><input type="text" name="emailAddress" class="text" value="<?=set_value('emailAddress')?>"></div>
		</div>
		<div class="inputButton">
			<input type="submit" class="submitButton" value="<?=gettext("Refer")?>" name="submitButton">
		</div>
	</div>
</form>