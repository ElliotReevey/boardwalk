<p><?=gettext("Your account is currently in Hibernation, whilst in hibernation you cannot access your account")?></p>
<p><?=sprintf(gettext("Until your account has served its ban you cannot access %s."),$gamename)?></p>

<div class="contentContainer">
	<div class="fieldHolder">
		<div class="labelItem"><?=gettext("Email Address:")?></div>
		<div class="inputField"><?=$email?></div>
	</div>
	<div class="fieldHolder">
		<div class="labelItem"><?=gettext("Username:")?></div>
		<div class="inputField"><?=$username?></div>
	</div>
	<div class="fieldHolder">
		<div class="labelItem"><?=gettext("Hibernation Until:")?></div>
		<div class="inputField"><?=date("H:i d/m/Y",$hibernation_time)?></div>
	</div>
</div>

<p><?=gettext("Try and log in again once your time has expired and continue playing the game.")?></p>