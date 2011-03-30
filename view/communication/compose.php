<h1><?=gettext("Compose Message")?></h1>

<?php if(isset($fail)) { echo errorbox($fail); } elseif(isset($success)) { successbox($success); } ?>

<?=form_open_this("compose/send")?>
	<div class="contentContainer">
		<div class="fieldHolder">
			<div class="labelItem"><?=gettext("Send to:")?></div>
			<div class="inputField"><input type="text" class="text" name="sendTo" value="<?=set_value('sendTo')?>"></div>
		</div>
		<div class="fieldHolder">
			<div class="labelItem"><?=gettext("Message:")?></div>
			<div class="inputField"><textarea name="messageBox" class="textArea"><?=set_value('messageBox')?></textarea></div>
		</div>
		<div class="inputButton">
			<input type="submit" class="submitButton" value="<?=gettext("Send Message")?>" name="sendMessage">
		</div>
	</div>
</form>