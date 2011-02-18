<p><?=sprintf(gettext("Your account has been banned by the %s Staff."),$gamename)?></p>
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
		<div class="labelItem"><?=gettext("Banned Until:")?></div>
		<div class="inputField"><?=$ban_time?></div>
	</div>
	<div class="fieldHolder">
		<div class="labelItem"><?=gettext("Ban Reason:")?></div>
		<div class="inputField"><?=date("H:i d/m/Y",$ban_reason)?></div>
	</div>
</div>

<p><?=gettext("Try and log in again once your time has expired and continue playing the game.")?></p>
<p><?=gettext("Repeat offenders will be punished more severely, you have been warned.")?></p>
<p><?=sprintf(gettext("If you think your account was banned unfairly or you think your account has a reason to be re-instated please contact %s Staff at: <a href='mailto:%s'>%s</a>."),$gamename,$systememail,$systememail)?></p>