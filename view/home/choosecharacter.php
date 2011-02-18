<h1><?=gettext("Choose character")?></h1>

<?php if(isset($fail)) { echo errorbox($fail); } ?>

<?=form_open_this("home/choosecharacter/submit")?>
	<div class="contentContainer">
		<div class="fieldHolder">
			<div class="labelItem"><?=gettext("Character Name:")?></div>
			<div class="inputField"><input type="text" name="characterName" class="text" value="<?=set_value('characterName')?>"></div>
		</div>
		<div class="fieldHolder">
			<div class="labelItem"><?=gettext("Character Gender:")?></div>
			<div class="inputField">
				<select name="genderType" class="selectBox">
					<option selected="selected" value="">-- <?=gettext("Select Gender")?> --</option>
					<option><?=gettext("Male")?></option>
					<option><?=gettext("Female")?></option>
				</select>
			</div>
		</div>
		<div class="fieldHolder">
			<div class="labelItem"><?=gettext("Starting Location:")?></div>
			<div class="inputField">
				<select name="startLocation" class="selectBox">
					<option selected="selected" value="">-- <?=gettext("Select Location")?> --</option>
					<option><?=gettext("Amsterdam")?></option>
					<option><?=gettext("London")?></option>
					<option><?=gettext("Brussels")?></option>
					<option><?=gettext("Kazakhstan")?></option>
				</select>
			</div>
		</div>
		<div class="inputButton">
			<input type="submit" class="submitButton" value="<?=gettext("Submit")?>" name="submitButton">
		</div>
	</div>
</form>