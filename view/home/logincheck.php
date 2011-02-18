<p><?=sprintf(gettext("Welcome back to %s %s"),$gamename,$username)?></p>

<p><?=gettext("In order to prove you are human, complete your login by entering the code below into the adjacent box.")?></p>

<?php if(isset($fail)) { echo errorbox($fail); } ?>

<?=form_open_this("home/logincheck/submit")?>
	<div class="contentContainer">
		<div class="fieldHolder">
			<div class="labelItem"><?=gettext("Anti Script:")?></div>
			<div class="antiScriptCode"></div>
			<div class="antiScriptField"><input type="text" name="antiScript" class="antiScript"></div>
		</div>
		<div class="inputButton">
			<input type="submit" class="submitButton" value="<?=gettext("Login")?>" name="submitButton">
		</div>
	</div>
</form>