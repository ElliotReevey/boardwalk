<h1>Create Ticket</h1>

<?php if(isset($fail)) { echo errorbox($fail); } elseif(isset($success)) { successbox($success); } ?>

<?=form_open_this("helpdesk/send")?>
	<div class="contentContainer">
		<div class="fieldHolder">
			<div class="labelItem"><?=gettext("Ticket Subject:")?></div>
			<div class="inputField"><input type="text" class="text" name="ticketSubject" value="<?=set_value('ticketSubject')?>"></div>
		</div>
		<div class="fieldHolder">
			<div class="labelItem"><?=gettext("Ticket Type:")?></div>
			<div class="inputField">
				<select name="subjectType" class="selectBox">
					<option selected="selected" value="">-- <?=gettext("Select Subject")?> --</option>
					<option value="1"><?=gettext("Game Help")?></option>
					<option value="2"><?=gettext("Login Problems")?></option>
					<option value="3"><?=gettext("Payments")?></option>
					<option value="4"><?=gettext("Bug")?></option>
					<option value="5"><?=gettext("Idea")?></option>
				</select>
			</div>
		</div>
		<div class="fieldHolder">
			<div class="labelItem"><?=gettext("Message:")?></div>
			<div class="inputField"><textarea name="messageBox" class="textArea"><?=set_value('messageBox')?></textarea></div>
		</div>
		<div class="fieldHolder">
			<div class="labelItem"><?=gettext("Anti Script:")?></div>
			<div class="antiScriptCode"></div>
			<div class="antiScriptField"><input type="text" name="antiScript" class="antiScript"></div>
		</div>
		<div class="inputButton">
			<input type="submit" class="submitButton" value="<?=gettext("Submit Ticket")?>" name="submitTicket">
		</div>
	</div>
</form>