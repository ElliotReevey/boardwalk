<h1><?=gettext("Search")?></h1>

<?php if(isset($fail)) { echo errorbox($fail); } elseif(isset($success)) { successbox($success); } ?>

<h2><?=gettext("Search Players")?></h2>
<?=form_open_this("world/search/player")?>
	<div class="contentContainer">
		<div class="fieldHolder">
			<div class="labelItem"><?=gettext("Name:")?></div>
			<div class="inputField"><input type="text" name="searchName" class="text"></div>
		</div>
		<div class="fieldHolder">
			<div class="labelItem"><?=gettext("Status:")?></div>
			<div class="inputField"><input type="radio" name="accStatus" value="1"> <?=gettext("Alive")?> <input type="radio" name="accStatus" value="2"> <?=gettext("Dead")?> <input type="radio" name="accStatus" value="3"> <?=gettext("Banned")?> <input type="radio" name="accStatus" value="4"> <?=gettext("Taking a Break")?> <input type="radio" name="accStatus" value="5" checked="checked"> <?=gettext("All")?></div>
		</div>
		<div class="fieldHolder">
			<div class="labelItem"><?=gettext("Activity:")?></div>
			<div class="inputField"><input type="radio" name="accActivity" value="1"> <?=gettext("Last 10 minutes")?> <input type="radio" name="accActivity" value="2"> <?=gettext("Last hour")?> <input type="radio" name="accActivity" value="3"> <?=gettext("Last 24 hours")?> <input type="radio" name="accActivity" value="4"> <?=gettext("Within last week")?> <input type="radio" name="accActivity" value="5" checked="checked"> <?=gettext("Ever")?></div>
		</div>
		<div class="inputButton">
			<input type="submit" class="submitButton" value="<?=gettext("Search Players")?>" name="playerSearch">
		</div>
	</div>
</form>

<h2><?=gettext("Search Crews")?></h2>
<?=form_open_this("world/search/crew")?>
	<div class="contentContainer">
		<div class="fieldHolder">
			<div class="labelItem"><?=gettext("Name:")?></div>
			<div class="inputField"><input type="text" name="searchCrew" class="text"></div>
		</div>
		<div class="inputButton">
			<input type="submit" class="submitButton" value="<?=gettext("Search Crews")?>" name="crewSearch">
		</div>
	</div>
</form>
