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
			<div class="inputField"><input type="radio" name="accStatus" value="1"> Alive <input type="radio" name="accStatus" value="2"> Dead <input type="radio" name="accStatus" value="3"> Banned <input type="radio" name="accStatus" value="4"> Taking a Break <input type="radio" name="accStatus" value="5" checked="checked"> All  </div>
		</div>
		<div class="fieldHolder">
			<div class="labelItem"><?=gettext("Activity:")?></div>
			<div class="inputField"><input type="radio" name="accActivity" value="1"> Last 10 minutes <input type="radio" name="accActivity" value="2"> Last hour <input type="radio" name="accActivity" value="3"> Last 24 hours <input type="radio" name="accActivity" value="4"> Within last week <input type="radio" name="accActivity" value="5" checked="checked"> Ever</div>
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
